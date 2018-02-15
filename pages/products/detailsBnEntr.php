<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 12/02/18
 * Time: 17:37
 */
$idBn= filter_input(INPUT_GET,'idBn',FILTER_VALIDATE_INT);
$bnsEntr=get('*','prod_ref_stock',array('id='=>$idBn));
$bn=$bnsEntr['reponse'][0];
$products=get('*','prod_stock',array('	idBonEnt='=>$idBn));
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
                        <small>N° <?='BN'.$bn['id'].'/'.date('Y',strtotime($bn['created_at']));?></small>
                    </h1>
                </td>
            </tr>
        </table>
        <h2 style="text-align: center">Liste des produits</h2>
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
            <?php foreach ($products['reponse'] as $product):?>
                <tr>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($product['prod'],'products','code_article');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($product['prod'],'products','name');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($product['prod'],'products','description');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=$product['qte'];?></td>
                </tr>
            <?endforeach;?>
            </tbody>
        </table>

    </div>
</section>
