<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Sistema;
use yii\helpers\Console;
use app\lists\VersionList;

class VersaoController extends Controller {

    public function actionAtualizar() {
        $versao_atual = VersionList::$data[0]['version'];

        Sistema::updateAll(['valor' => $versao_atual], ['campo' => 'version']);

        parent::stdout("Versão atual: {$versao_atual}\n\n", Console::BG_BLUE);
    }

}
