<?php if($condicao) : ?>

    <?php foreach($condicao as $indexAnd => $grupo) : ?>

        <?php if($indexAnd > 1): ?>

            <div class="and-text-separator and-text-separator-<?= $indexAnd ?>"><?= Yii::t('app', 'view.geral.e'); ?></div>
            
        <?php endif; ?>
            
        <ul class="list-group filter-list__atribute" id="filter-list__atribute_<?= $indexAnd ?>" data-index="<?= $indexAnd ?>">

            <?php foreach($grupo as $indexOr => $data) : ?>
            
                <?= $this->render('_or', ['model' => $model, 'indexAnd' => $indexAnd, 'indexOr' => $indexOr, 'data' => $data, 'isLast' => sizeof($grupo) == $indexOr]) ?>

            <?php endforeach; ?>
            
        </ul>

    <?php endforeach; ?>

<?php else: ?>

    <?php if($index > 1): ?>

        <div class="and-text-separator and-text-separator-<?= $index ?>"><?= Yii::t('app', 'view.geral.e'); ?></div>

    <?php endif; ?>
            
    <ul class="list-group filter-list__atribute" id="filter-list__atribute_<?= $index ?>" data-index="<?= $index ?>" <?= ($index > 1) ? 'style="display:none;"' : '' ?>>

        <?= $this->render('_or', ['model' => $model, 'indexAnd' => $index, 'indexOr' => 1, 'data' => null, 'isLast' => true]) ?>

    </ul>

<?php endif; ?>
