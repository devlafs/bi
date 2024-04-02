<?php

namespace app\magic;

use Yii;
use app\models\Email;
use app\models\AdminUsuario;
use app\lists\FrequenciaList;
use app\magic\CacheMagic;
use app\models\UrlShare;
use app\models\EmailLog;
use Da\QrCode\QrCode;
use app\magic\SqlMagic;
use kartik\mpdf\Pdf;
use app\lists\TemplateEmailList;

class EmailMagic {

    public static function enviar($id = null) {
        $emails = ($id) ? Email::find()->andWhere(['id' => $id])->all() : Email::find()->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->all();

        foreach ($emails as $email) {
            if (($email->id_consulta && (!$email->consulta->indicador->is_ativo || $email->consulta->indicador->is_excluido)) || ($email->id_consulta && (!$email->consulta->is_ativo || $email->consulta->is_excluido)) || ($email->id_painel && (!$email->painel->is_ativo || $email->painel->is_excluido))) {
                continue;
            }

            $destinatarios = self::getDestinatario($email);

            $frequencia = $email->frequencia;

            if ($id) {
                $enviar = TRUE;
            } else {
                $enviar = FALSE;

                switch ($frequencia) {
                    case Email::FREQUENCIA_DIARIA:

                        $enviar = self::verificaEnvioDiario($email);

                        break;

                    case Email::FREQUENCIA_SEMANAL:

                        $enviar = self::verificaEnvioSemanal($email);

                        break;

                    case Email::FREQUENCIA_MENSAL:

                        $enviar = self::verificaEnvioMensal($email);
                }
            }

            if ($enviar) {
                foreach ($destinatarios as $destinatario) {
                    self::sendMail($email, $destinatario);
                }
            }
        }
    }

    public static function verificaEnvioDiario($email) {
        $hora_envio = $email->hora;
        $string_hora = ($hora_envio < 10) ? "0{$hora_envio}" : $hora_envio;

        $dt = new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'));
        $dt->setTimestamp(time());

        $hora_atual = (int) $dt->format('H');
        $dia_hoje = $dt->format('Y-m-d');
        $data_envio = "{$dia_hoje} {$string_hora}:00:00";

        return (strtotime($email->sent_at) < strtotime($data_envio) && $hora_atual >= $hora_envio);
    }

    public static function verificaEnvioSemanal($email) {
        $hora_envio = $email->hora;
        $dia_semana_envio = $email->dia_semana;
        $string_hora = ($hora_envio < 10) ? "0{$hora_envio}" : $hora_envio;
        $week_name = FrequenciaList::getWeekName($dia_semana_envio);
        $data_semana_envio = date('Y-m-d', strtotime("{$week_name} this week"));
        $data_envio = "{$data_semana_envio} {$string_hora}:00:00";
        $dt = new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'));
        $dt->setTimestamp(time());

        $dia_semana_atual = (int) $dt->format('w');
        $hora_atual = (int) $dt->format('H');

        return !$email->sent_at || (strtotime($email->sent_at) < strtotime($data_envio) && $dia_semana_atual == $dia_semana_envio && $hora_atual >= $hora_envio);
    }

    public static function verificaEnvioMensal($email) {
        $hora_envio = $email->hora;
        $dia_mes_envio = $email->dia_mes;
        $string_hora = ($hora_envio < 10) ? "0{$hora_envio}" : $hora_envio;

        $dt = new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'));
        $dt->setTimestamp(time());

        $dia_mes_atual = (int) $dt->format('Y');
        $ano_atual = (int) $dt->format('Y');
        $mes_atual = (int) $dt->format('m');
        $hora_atual = (int) $dt->format('H');

        $data_envio = "{$ano_atual}-{$mes_atual}-{$dia_mes_envio} {$string_hora}:00:00";

        return !$email->sent_at || (strtotime($email->sent_at) < strtotime($data_envio) && $dia_mes_atual == $dia_mes_envio && $hora_atual >= $hora_envio);
    }

    public static function getDestinatario($email) {
        $destinatarios = [];

        if ($email->id_perfil) {
            $usuarios = AdminUsuario::find()->joinWith('perfil')->andWhere([
                        'admin_perfil.is_ativo' => TRUE,
                        'admin_perfil.is_excluido' => FALSE,
                        'admin_perfil.acesso_bi' => TRUE,
                        'admin_usuario.status' => 'Ativo',
                        'admin_usuario.is_ativo' => TRUE,
                        'admin_usuario.is_excluido' => FALSE,
                        'admin_usuario.acesso_bi' => TRUE,
                        'admin_perfil.id' => $email->id_perfil
                    ])->andWhere('admin_usuario.email is not null')->all();

            if ($usuarios) {
                foreach ($usuarios as $usuario) {
                    $destinatarios[] = $usuario->email;
                }
            }
        } elseif ($email->id_departamento || $email->departamento) {
            $beeIntegration = Yii::$app->params['beeIntegration'];

            if ($beeIntegration) {
                $sqlDepartamento = "SELECT email 
                    FROM
                        admin_usuario_departamento dep
                            JOIN
                        admin_usuario usu ON usu.id = dep.usuario_id
                            AND usu.status = 'Ativo'
                            AND usu.is_ativo = TRUE
                            AND usu.is_excluido = FALSE
                            AND usu.acesso_bi = TRUE
                    WHERE
                        dep.departamento_id = {$email->id_departamento};";
            } else {
                $sqlDepartamento = "SELECT 
                        usu.email
                    FROM
                        admin_usuario usu 
                    WHERE usu.status = 'Ativo'
                            AND usu.is_ativo = TRUE
                            AND usu.is_excluido = FALSE
                            AND usu.acesso_bi = TRUE
                            AND usu.departamento = '{$email->departamento}'";
            }

            $usuarios = Yii::$app->userDb->createCommand($sqlDepartamento)->queryAll();

            if ($usuarios) {
                foreach ($usuarios as $usuario) {
                    $destinatarios[] = $usuario['email'];
                }
            }
        } elseif ($email->id_usuario) {
            $usuario = AdminUsuario::find()->andWhere([
                        'status' => 'Ativo',
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'acesso_bi' => TRUE,
                        'id' => $email->id_usuario
                    ])->andWhere('email is not null')->one();

            if ($usuario) {
                $destinatarios = [$usuario->email];
            }
        } elseif ($email->email) {
            $emails = is_array($email->email) ?: explode(';', $email->email);

            foreach ($emails as $email) {
                if (!empty(trim($email))) {
                    $destinatarios[] .= $email;
                }
            }
        }

        return $destinatarios;
    }

    public static function sendMail($model, $destinatario) {
        \Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";

        $aux_model = ($model->view == Email::VIEW_CONSULTA) ? $model->consulta : $model->painel;
        $field = ($model->view == Email::VIEW_CONSULTA) ? 'id_consulta' : 'id_painel';
        $token = Yii::$app->security->generateRandomString() . time();
        $pass = Yii::$app->security->generateRandomString();

        if($model->view == Email::VIEW_CONSULTA)
        {
            $data = SqlMagic::getData($aux_model, 0, null, '', FALSE, 100000000, FALSE, null, TRUE);

            if(!isset($data['dataProvider']) || !$data['dataProvider'])
            {
                self::saveLogs($model->id, $destinatario, "Consulta vazia", 0);
                return true;
            }
        }

        $urlShare = new UrlShare();
        $urlShare->view = $model->view;
        $urlShare->{$field} = $aux_model->id;
        $urlShare->id_usuario = $model->created_by;
        $urlShare->token = $token;
        $urlShare->password = $pass;
        $urlShare->type = 'automatico';
        $urlShare->email = $destinatario;

        if ($urlShare->save()) {
            $url = CacheMagic::getSystemData('url') . "/share/v?c={$urlShare->id}&t={$token}";

            $qrCode = (new QrCode($url))
                    ->setSize(130)
                    ->setMargin(10)
                    ->useForegroundColor(34, 117, 132)
                    ->useBackgroundColor(255, 255, 255)
                    ->useEncoding('UTF-8');

            $filename = $model->id . '_' . time() . '.png';
            $qrCode->writeFile(\Yii::getAlias('@app/web/qrcode/' . $filename));
            $cid = (\Yii::getAlias('@app/web/qrcode/' . $filename));

            $title = ($model->view == Email::VIEW_CONSULTA) ? "Consulta" : "Painel";

            if ($model->id_template) {
                $message = \Yii::$app->mailer->compose();
                $message->setHtmlBody(self::getConverterdHtml($message, $model->template, $url, $aux_model->nome, $cid));
            } else {
                $message = \Yii::$app->mailer->compose(['html' => '@app/mail/views/send-email'], ['model' => $urlShare, 'title' => $title, 'aux_model' => $aux_model, 'url' => $url, 'cid' => $cid]);
            }

            $message->setFrom(CacheMagic::getSystemData('systemEmail'));
            $message->setTo($destinatario);
            $message->setSubject("{$model->assunto}");

            if ($model->send_pdf && $model->view == Email::VIEW_CONSULTA) {
                $filename = str_replace('/', '_', ' ' . $aux_model->nome . '.pdf');
                self::getPdf($aux_model, $filename);
                $message->attach($filename);
            }

            $sent = $message->send();

            if ($sent) {
                if ($model->send_pdf && $model->view == Email::VIEW_CONSULTA) {
                    unlink($filename);
                }

                self::saveLogs($model->id, $destinatario, "Email enviado com sucesso");
                $model->sent_at = new \yii\db\Expression('NOW()');
                $model->tipo_destinatario = $model->getTipoDestinatario();
                $model->save();
            } else {
                self::saveLogs($model->id, $destinatario, "Erro ao enviar email", 0);
            }
        } else {
            self::saveLogs($model->id, $destinatario, "Erro ao enviar email", 0);
        }
    }

    public function getPdf($model, $filename) {
        ini_set("pcre.backtrack_limit", "5000000");

        $data = SqlMagic::getData($model, 0, null, '', FALSE, 100000000, FALSE, null, TRUE);

        $type = pathinfo(CacheMagic::getSystemData('logo'), PATHINFO_EXTENSION);
        $img_content = file_get_contents(\Yii::$app->basePath . '/web/' . CacheMagic::getSystemData('logo'));
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img_content);

        if (!$data['error']) {
            $company_name = CacheMagic::getSystemData('name');

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'destination' => Pdf::DEST_FILE,
                'marginTop' => 30,
                'content' => Yii::$app->controller->renderPartial("/_graficos/_print", compact('data', 'model')),
                'filename' => $filename,
                'options' =>
                [
                    'title' => ' ' . $model->nome,
                ],
                'methods' => [
                    'SetTitle' => ' ' . $model->nome,
                    'SetHeader' => ['<img style="width:120px;" src="' . $base64 . '" /> | <p><h3>' . $company_name . '</h3></p> <p>' . mb_strtoupper($model->nome) . '</p> |'],
                    'SetFooter' => [mb_strtoupper('Página {PAGENO} | <span style="font-size: 10px;">Powered by</span><br><span style="font-size: 14px;">BP1 Sistemas</span> |' . date('d/m/Y H:i:s', time()))],
                ]
            ]);

            return $pdf->render();
        }

        return false;
    }

    public static function saveLogs($id, $destinatario, $log, $status = 1) {
        $model = new EmailLog();
        $model->id_email = $id;
        $model->destinatario = $destinatario;
        $model->log = $log;
        $model->status = $status;
        $model->save();
    }

    public static function getConverterdHtml($message, $template, $url, $objeto_nome, $cid) {
        $tags = [
//            '#' . TemplateEmailList::TAG_LOGO_BP1 => $message->embed(Yii::getAlias('@app/web/') . 'email_header.png'),
//            '#' . TemplateEmailList::TAG_SITE_BP1 => 'https://www.bp1bi.com.br/',
                    '#' . TemplateEmailList::TAG_LOGO_EMPRESA => $message->embed(Yii::getAlias('@app/web/') . CacheMagic::getSystemData('logo')),
                    '#' . TemplateEmailList::TAG_NOME_EMPRESA => CacheMagic::getSystemData('name'),
                    '#' . TemplateEmailList::TAG_QRCODE => $message->embed($cid),
                    '#' . TemplateEmailList::TAG_LINK_ACESSO => $url,
                    '#' . TemplateEmailList::TAG_NOME_OBJETO => $objeto_nome,
                    '#' . TemplateEmailList::TAG_PERIODO_DE_VALIDADE => CacheMagic::getSystemData('emailShareDaysExpiration'),
//            TemplateEmailList::TAG_PERFIL_USUARIO => 'perfilusuario',
//            TemplateEmailList::TAG_NOME_USUARIO => 'nomeusuario',
//            TemplateEmailList::TAG_EMAIL_USUARIO => 'emailusuario',
//            TemplateEmailList::TAG_DEPARTAMENTO_USUARIO => 'departamentousuario',
//            TemplateEmailList::TAG_CARGO_USUARIO => 'cargousuario',
                    '#' . TemplateEmailList::TAG_DATA_HOJE => date('d/m/Y')
        ];

        $append = <<<HTML
                
            <a style="color: #0068A5; text-decoration: underline" href="https://www.bp1bi.com.br/" target="_blank" rel="noreferrer">
                BP1 Sistemas - www.bp1bi.com.br
            </a>
   
                
HTML;

        $find = array_keys($tags);
        $replace = array_values($tags);
        return str_ireplace($find, $replace, $template->html . $append);
    }
}
