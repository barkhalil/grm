<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 14/02/18
 * Time: 11:08
 */
$refBn= filter_input(INPUT_GET,'refBn',FILTER_DEFAULT);
//echo '<pre>';print_r($_GET);die;
$where=array('ref= "'=>$refBn.'"');
$bnsEntr=$giftStock->getBnEntr($refBn);
//echo '<pre>';print_r($bnsEntr);die;
?>
<section class="content-header" style="background: #fff;">
    <h1 class="pull-left"> Bons d'entrée: </h1>
    <button type="button" id="BtnToPrint" value="1" onclick="PrintDiv()" class="btn btn-facebook pull-right">Imprimer <i class="fa fa-print"></i></button>
    <a href="#" class="btn bg-maroon pull-right" style="margin-right: 15px" onclick="history.go(-1);"> <i class="glyphicon glyphicon-backward"></i> Retour </a>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content" id="DivToPrint" style="background: #fff;">
    <div class="box-body" style="margin-top: 100px;">
        <table style="width:  100%;">
            <tr>
                <td colspan="2">
                    <h1 style="display: block; text-align: center">Bons d'entrée

                        <small>N° <?='BN'.$bnsEntr[0]['ref'].'/'.date('Y',strtotime($bnsEntr[0]['system_date']));?></small>
                        <br>
                        <? if ($bnsEntr[0]['idsect']!=0){ ?>
                        <small>Secteur: <?=getinfo($bnsEntr[0]['idsect'],'gouvernerat','nom')?></small>
                        <?}?>
                    </h1>
                </td>
            </tr>
        </table>
        <h2 style="text-align: center">Liste des articles</h2>
        <table style="border: 1px solid #999; width: 100%;">
            <thead>
            <tr>
                <th style="border: 1px solid #999;padding: 10px">code</th>
                <th style="border: 1px solid #999;padding: 10px">Désignation</th>
                <th style="border: 1px solid #999;padding: 10px">Déscription</th>
                <th style="border: 1px solid #999;padding: 10px">Quantité</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bnsEntr as $article):?>
                <tr>
                    <?php $bnDetails=explode('//',$article['champs']);
                     // echo '<pre>';print_r($bnDetails);die;
                    ?>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($article['prod'],'grm_gift','code_article');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($article['prod'],'grm_gift','titre');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($article['prod'],'grm_gift','description');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=$article['qte'];?></td>
                </tr>
            <?endforeach;?>
            </tbody>
        </table>

    </div>
</section>