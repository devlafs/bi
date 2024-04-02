<?php 

use app\magic\DataProviderMagic;
use app\magic\ResultMagic;

if($data) : 
    
    $colspan = 2;

?>

    <h4><?= Yii::t('app', 'view.relatorio.preview_dados') ?></h4>

    <div class="d-flex justify-content-center align-item-center w-100 preview__data--content">

        <table id="datagrid" class="table table-hover table-bordered" cellspacing="0" width="100%">

            <thead class="thead-default">

                <tr>

                    <?php foreach ($data['campos']['x'] as $campo) : ?>

                        <td><?= $campo['nome'] ?></td>

                    <?php endforeach; ?>

                    <td><?= $data['campos']['y']['nome'] ?></td>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($data['dataProvider']->models as $valor) : ?>

                    <tr>

                        <?php foreach ($data['campos']['x'] as $campo) : ?>

                            <td><?= $valor[$campo['campo']] ?></td>

                        <?php endforeach; ?>

                        <td><?= $valor[$data['campos']['y']['campo']] ?></td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php endif; ?>