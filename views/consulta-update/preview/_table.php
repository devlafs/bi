<?php 

use app\magic\DataProviderMagic;
use app\magic\ResultMagic;

if($data) : 
    
    $colspan = (isset($data['nomes']['w'])) ? sizeof($data['nomes']['w']) + 2 : 2;

    $dataProvider = DataProviderMagic::getData($data['dataProvider'], 'table', $data['series']);
        
?>

    <h4><?= Yii::t('app', 'view.consulta.preview_dados') ?></h4>

    <div class="d-flex justify-content-center align-item-center w-100 preview__data--content">

        <table id="datagrid<?= $index; ?>" class="table table-hover table-bordered table__preview" cellspacing="0" width="100%">

        <thead class="thead-default">
            
            <tr>
                
                <th><?= $data['nomes']['x']; ?></th>
                
                <?php if(isset($data['nomes']['w'])) :
                    
                        foreach($data['nomes']['w'] as $nome_w) :  ?>
                
                            <th><?= $nome_w ?></th>

                    <?php endforeach;
                
                endif; ?>

                <th class="text-right"><?= $data['nomes']['y']; ?></th>

            </tr>
            
        </thead>
        
        <tbody>

            <?php foreach($dataProvider['data'] as $nome_serie => $valores) : ?> 
                       
                <?php if($data['series']) : ?>
                        
                    <tr class="title-serie">
            
                        <td class="text-center text-bold text-uppercase" colspan="<?= $colspan ?>"><?= ($nome_serie) ? mb_strtoupper(ResultMagic::format($nome_serie, $data['campos']['z'], 1, TRUE)) : 'null' ?></td>
                    
                    </tr>
                        
                <?php endif; ?>
            
                <?php foreach($valores['data'] as $valor) :?>

                    <tr>
                        
                        <td data-order="<?= (isset($valor['x'])) ? $valor['x'] : '' ?>">
                        
                            <?php 
                        
                            if(!$data['elementoAtual'])
                            {
                                echo Yii::t('app', 'view.geral.total');
                            }
                            elseif(isset($valor['x']))
                            {
                                echo ResultMagic::format($valor['x'], $data['campos']['x'], 1, TRUE);
                            }
                            else
                            {
                                echo 'null';
                            }
                            
                            ?>
                        
                        </td>
                                                
                        <?php if(isset($data['nomes']['w'])) :

                                foreach($data['nomes']['w'] as $index_w => $nome_w) : ?>

                                    <td data-order="<?= (isset($valor['w' . $index_w])) ? $valor['w' . $index_w] : '' ?>"><?= (isset($valor['w' . $index_w])) ? ResultMagic::format($valor['w' . $index_w], $data['campos']['w' . $index_w], 1, TRUE) : '-' ?></td>

                                <?php endforeach;

                        endif; ?>

                        <td data-order="<?= $valor['y'] ?>"  class="text-right"><?= ResultMagic::format($valor['y'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE); ?></td>

                    </tr>
                    
                <?php endforeach; ?>
            
            <?php endforeach; ?>
                    
        </tbody>

    </table>

    </div>

<?php else : ?>
    
    <?= $this->render("/consulta-update/preview/_empty-table"); ?>
    
<?php endif; ?>