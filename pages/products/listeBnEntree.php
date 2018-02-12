<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 12/02/18
 * Time: 15:56
 */
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$bnsEntr=get('*','prod_ref_stock',NULL,'AND',array('created_at'=>'DESC'),array($Limite,30));
?>
<section class="content-header">
    <h1 class="pull-left">Bons d'entrée</h1>
    <a href="<?=WEBRoot?>/products/bonEntree" class="btn btn-primary pull-right">
        Nouveau
    </a>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="form-group">
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Réference</th>
                            <th>Date</th>
                            <th>Date de saisie</th>
                            <th>Par</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bnsEntr['reponse'] as $bn):?>
                        <tr>
                            <td><?='BN'.$bn['id'].'/'.date('Y',strtotime($bn['created_at']));?></td>
                            <td><?=$bn['reference'];?></td>
                            <td><?=$bn['date_bn_entr'];?></td>
                            <td><?=$bn['created_at'];?></td>
                            <td><?=getinfo($bn['created_by'],'users','Nom').' '.getinfo($bn['created_by'],'users','Prenom');?></td>
                            <td>
                                <a href="deytailBnEntr&idBn=<?=$bn['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Visualiser">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-5">

            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                Affichage de <?= ($Limite > 1) ? $Limite : 1 ?>
                à <?= ($Limite + 30 < $bnsEntr['total']) ? $Limite + 30 : $bnsEntr['total'] ?>
                de <?= $bnsEntr['total'] ?> Bons d'entrée

            </div>

        </div>

        <div class="col-md-7">

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                <? pagination($bnsEntr['total'], 30, WEBRoot . "/products/listeBnEntree".$link."&d=", ""); ?>

            </div>

        </div>

    </div>
</section>
