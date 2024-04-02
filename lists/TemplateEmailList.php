<?php

namespace app\lists;

use Yii;

class TemplateEmailList {

    CONST TIPO_SENHA = 1;
    CONST TIPO_CONSULTA_MANUAL = 2;
    CONST TIPO_PAINEL_MANUAL = 3;
    CONST TIPO_CONSULTA_AUTOMATICA = 4;
    CONST TIPO_PAINEL_AUTOMATICO = 5;
//    CONST TAG_LOGO_BP1 = 'logobp1';
//    
//    CONST TAG_SITE_BP1 = 'sitebp1';

    CONST TAG_LOGO_EMPRESA = 'logoempresa';
    CONST TAG_NOME_EMPRESA = 'nomeempresa';
    CONST TAG_QRCODE = 'qrcode';
    CONST TAG_LINK_ACESSO = 'linkacesso';
    CONST TAG_NOME_OBJETO = 'nomeobjeto';
    CONST TAG_PERIODO_DE_VALIDADE = 'periodovalidade';
//
//    CONST TAG_PERFIL_USUARIO = 'perfilusuario';
//
//    CONST TAG_NOME_USUARIO = 'nomeusuario';
//
//    CONST TAG_EMAIL_USUARIO = 'emailusuario';
//    
//    CONST TAG_DEPARTAMENTO_USUARIO = 'departamentousuario';
//    
//    CONST TAG_CARGO_USUARIO = 'cargousuario';

    CONST TAG_DATA_HOJE = 'datahoje';

    public static $tags = [
//        self::TAG_LOGO_BP1 => 'Logo - BP1', // Obrigatório
//        self::TAG_SITE_BP1 => 'Site - BP1', // Obrigatório
        self::TAG_LINK_ACESSO => '#' . self::TAG_LINK_ACESSO,
        self::TAG_LOGO_EMPRESA => '#' . self::TAG_LOGO_EMPRESA,
        self::TAG_NOME_EMPRESA => '#' . self::TAG_NOME_EMPRESA,
        self::TAG_NOME_OBJETO => '#' . self::TAG_NOME_OBJETO,
//        self::TAG_NOME_USUARIO => '#' . self::TAG_NOME_USUARIO, 
        self::TAG_QRCODE => '#' . self::TAG_QRCODE,
//        self::TAG_PERFIL_USUARIO => '#' . self::TAG_PERFIL_USUARIO, 
        self::TAG_PERIODO_DE_VALIDADE => '#' . self::TAG_PERIODO_DE_VALIDADE,
//        self::TAG_CARGO_USUARIO => '#' . self::TAG_CARGO_USUARIO,
        self::TAG_DATA_HOJE => '#' . self::TAG_DATA_HOJE,
//        self::TAG_DEPARTAMENTO_USUARIO => '#' . self::TAG_DEPARTAMENTO_USUARIO, 
//        self::TAG_EMAIL_USUARIO => '#' . self::TAG_EMAIL_USUARIO, 
    ];

    public static function getDataTipos() {
        return [
            self::TIPO_SENHA => Yii::t('app', 'geral.senha'),
            self::TIPO_CONSULTA_MANUAL => Yii::t('app', 'geral.consulta_manual'),
            self::TIPO_PAINEL_MANUAL => Yii::t('app', 'geral.painel_manual'),
            self::TIPO_CONSULTA_AUTOMATICA => Yii::t('app', 'geral.consulta_automatica'),
            self::TIPO_PAINEL_AUTOMATICO => Yii::t('app', 'geral.painel_automatico')
        ];
    }

    public static function getDataTiposEnabled() {
        return [
            self::TIPO_CONSULTA_AUTOMATICA => Yii::t('app', 'geral.consulta_automatica'),
            self::TIPO_PAINEL_AUTOMATICO => Yii::t('app', 'geral.painel_automatico')
        ];
    }

    public static function getDataTiposDisabled() {
        return [
            self::TIPO_SENHA => Yii::t('app', 'geral.senha'),
            self::TIPO_CONSULTA_MANUAL => Yii::t('app', 'geral.consulta_manual'),
            self::TIPO_PAINEL_MANUAL => Yii::t('app', 'geral.painel_manual')
        ];
    }

    public static function getTipos($cod) {
        $tipos = self::getDataTipos();
        return (isset($tipos[$cod])) ? $tipos[$cod] : $cod;
    }

    public static function getTags($cod) {
        return (isset(self::$tags[$cod])) ? self::$tags[$cod] : $cod;
    }

}
