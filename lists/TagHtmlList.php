<?php

namespace app\lists;

use Yii;

class TagHtmlList {

    CONST TAG_LOGO_EMPRESA = 'logoempresa';

    CONST TAG_NOME_EMPRESA = 'nomeempresa';

    CONST TAG_LINK_ACESSO = 'linkacesso';

    CONST TAG_PERFIL_USUARIO = 'perfilusuario';

    CONST TAG_NOME_USUARIO = 'nomeusuario';

    CONST TAG_EMAIL_USUARIO = 'emailusuario';

    CONST TAG_DEPARTAMENTO_USUARIO = 'departamentousuario';

    CONST TAG_CARGO_USUARIO = 'cargousuario';

    CONST TAG_DATA_HOJE = 'datahoje';

    public static $tags = [
        self::TAG_LINK_ACESSO => '#' . self::TAG_LINK_ACESSO,
        self::TAG_LOGO_EMPRESA => '#' . self::TAG_LOGO_EMPRESA,
        self::TAG_NOME_EMPRESA => '#' . self::TAG_NOME_EMPRESA,
        self::TAG_NOME_USUARIO => '#' . self::TAG_NOME_USUARIO,
        self::TAG_PERFIL_USUARIO => '#' . self::TAG_PERFIL_USUARIO,
        self::TAG_CARGO_USUARIO => '#' . self::TAG_CARGO_USUARIO,
        self::TAG_DATA_HOJE => '#' . self::TAG_DATA_HOJE,
        self::TAG_DEPARTAMENTO_USUARIO => '#' . self::TAG_DEPARTAMENTO_USUARIO,
        self::TAG_EMAIL_USUARIO => '#' . self::TAG_EMAIL_USUARIO,
    ];
}
