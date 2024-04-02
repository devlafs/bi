<?php

namespace app\controllers;

use app\models\RelatorioDataItem;
use app\models\searches\RelatorioDinamicoSearch;
use Yii;
use app\models\UrlShare;
use app\models\UrlShareAccess;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\magic\SqlMagic;
use app\magic\CacheMagic;

class ShareController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' =>
                        [
                            [
                                'actions' => ['v'], //all
                                'allow' => true,
                            ],
                        ],
                    ]
        ];
    }

    public function actionV($c, $t, $h = true, $p = null, $index = 0, $token = '') {
        $this->layout = '//url-publica/main';
        $url = $this->findModel($c, $t);

        if (!$url) {
            return $this->render("_notfound");
        }

        if($url->view == UrlShare::VIEW_CONSULTA)
        {
            return $this->renderConsulta($url, $h, $p, $index, $token);
        }
        elseif($url->view == UrlShare::VIEW_PAINEL)
        {
            return $this->renderPainel($url, $h, $p);
        }
        else
        {
            return $this->renderRelatorio($url, $h, $p);
        }
    }

    protected function renderConsulta($url, $h, $p = null, $index, $token) {
        $model = $url->consulta;

        if($model->privado && $p && $p != $url->password) {
            return $this->render("_notallowed");
        }

        $user_choices = ($url->type != 'automatico');

        $data = SqlMagic::getData($model, $index, null, $token, ($token != null), 100000000, $user_choices, $url->id_usuario);

        if ($data['error']) {
            return $this->render("_error", compact('model'));
        } else {
            $this->accessLog($url->id);

            return $this->render('_view-consulta', [
                        'model' => $model,
                        'index' => 0,
                        'data' => $data,
                        'action' => 'share',
                        'header' => $h,
                        'p' => $p
            ]);
        }
    }

    protected function renderPainel($url, $h, $p = null) {
        $model = $url->painel;

        if($model->privado && $p && $p != $url->password) {
            return $this->render("_notallowed");
        }

        $this->accessLog($url->id);

        return $this->render('_view-painel', [
                    'model' => $model,
                    'header' => $h,
                    'p' => $p
        ]);
    }

    protected function renderRelatorio($url, $h, $p = null) {
        $model = $url->relatorio;

        $this->accessLog($url->id);

        $argumentos = RelatorioDataItem::find()->andWhere(['id_relatorio_data'=> $model->id, 'is_ativo' => 1, 'is_excluido' => 0, 'parametro' => 'argumento'])
            ->orderBy('ordem ASC')->all();

        $valores = RelatorioDataItem::find()->andWhere(['id_relatorio_data'=> $model->id, 'is_ativo' => 1, 'is_excluido' => 0, 'parametro' => 'valor'])
            ->orderBy('ordem ASC')->all();

        $data = [];

        if($argumentos)
        {
            foreach ($argumentos as $argumento)
            {
                $data['x'][] = ['campo' => $argumento->campo, 'id' => $argumento->id];
            }

        }

        if($valores)
        {
            foreach ($valores as $valor)
            {
                $data['y'] = ['campo' => $valor->campo, 'id' => $argumento->id];
            }

        }

        $campos = $data;
        $searchModel = new RelatorioDinamicoSearch();
        $searchModel->relatorio = $model;
        $searchModel->campos = $campos;
        $searchModel->filtros = $model->condicao;
        $searchModel->getAttributesDynamicFields();

        foreach($model->condicao as $fpadrao)
        {
            if(isset($fpadrao[1]) && isset($fpadrao[1]['value']) && $fpadrao[1]['value'] != '')
            {
                $filtro = 'dynamic_' . $fpadrao[1]['field'];
                $searchModel[$filtro] = $fpadrao[1]['value'];
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_view-relatorio', [
            'model' => $model,
            'url' => $url,
            'header' => $h,
            'p' => $p,
            'campos' => $campos,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function accessLog($url_id) {
        $model = new UrlShareAccess();
        $model->id_url = $url_id;
        $model->is_expired = FALSE;
        $model->ip = Yii::$app->getRequest()->getUserIP();
        $model->save();
    }

    protected function findModel($c, $t) {
        $model = UrlShare::find()->andWhere([
                    'id' => $c,
                    'token' => $t,
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->one();

//        if ($model) {
//
//            if($model->view == UrlShare::VIEW_CONSULTA)
//            {
//                $aux_model = $model->consulta;
//            }
//            elseif($model->view == UrlShare::VIEW_PAINEL)
//            {
//                $aux_model = $model->painel;
//            }
//            else
//            {
//                $aux_model = $model->relatorio;
//            }
//
//            $days = ($model->view == UrlShare::VIEW_CONSULTA) ? CacheMagic::getSystemData('urlShareDaysExpiration') :
//                    CacheMagic::getSystemData('emailShareDaysExpiration');
//
//            if ($model->view == UrlShare::VIEW_CONSULTA) {
//                if ($aux_model && $aux_model->tempo_expiracao_email) {
//                    $days = $aux_model->tempo_expiracao_email;
//                }
//            }
//
//            $expirate_date = strtotime(date('Y-m-d H:s', strtotime("-{$days} day")));
//            $created_at = strtotime($model->created_at);
//
//            if ($created_at < $expirate_date || !$aux_model->is_ativo || $aux_model->is_excluido) {
//                $model = null;
//            }
//        }

        return $model;
    }

}
