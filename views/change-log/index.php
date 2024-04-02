<?php

use Yii;

$this->title = \Yii::t('app', 'view.log_alteracoes');

?>

<div>
    
    <?php foreach($list as $index => $data) : ?>

        <div class="col-lg-12">
                
            <div data-mh="painel-group-<?= $index ?>" class="card mb-3">

                <div class="card-header d-flex align-item-center justify-content-end">

                    <div class="col-lg-6 text-left">
                        v<?= $data['version'] . '.' . $data['detail'] ?>
                    </div>
                    
                    <div class="col-lg-6 text-right">
                        <?= $data['date'] ?>
                    </div>

                </div>

                <div class="card-block ">
                    
                    <?php

                        $changes = [];

                        if($data['changes']) :

                        for($i = 1; $i <= $data['changes']; $i++) :

                            $changes[] = Yii::t('app', "{$data['version']}.{$data['detail']}.$i");

                        endfor;

                    ?>
                    
                        <?php sort($changes); ?>
                    
                        <ul style="list-style-type: square">
                    
                            <?php foreach($changes as $change) : ?>

                                <li><?= $change ?></li>

                            <?php endforeach; ?>
                    
                        </ul>
                        
                    <?php endif; ?>

                </div>

            </div>

        </div>
    
    <?php endforeach; ?>

</div>