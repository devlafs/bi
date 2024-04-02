<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\magic\EmailMagic;

class EmailController extends Controller {

    public function actionEnviar() {
        $start_date = date('Y-m-d H:i:s');
        parent::stdout("{$start_date} - Iniciando rotina de email\n\n", Console::BG_BLUE);

        EmailMagic::enviar();

        $end_date = date('Y-m-d H:i:s');
        parent::stdout("{$end_date} - Finalizando rotina de email\n\n", Console::BG_BLUE);
    }

}
