<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 17:04
 */
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$ProdSeaC=filter_input(INPUT_POST,'ProdSeaC',FILTER_VALIDATE_INT);
$version=filter_input(INPUT_POST,'version',FILTER_VALIDATE_INT);
$qte=filter_input(INPUT_POST,'qte',FILTER_VALIDATE_INT);
$type=filter_input(INPUT_POST,'type',FILTER_VALIDATE_INT);
$delegue=filter_input(INPUT_POST,'delegue',FILTER_VALIDATE_INT);
if(!$type) {
    $uType =$_SESSION['user']['type'];
    $url="materielPromotionnel&IdSup=";
}else{
    $uType=$type;
    $url="PromoDetails&IdSup=";
}
$_SESSION['delegue']=$delegue;
$version_nom = getinfoByIdv3('version', 'grm_art_version', ' id=' . $version);
$_SESSION['version_nom']=$version_nom;
$_SESSION['version_id']=$version;


if($ProdSeaC && $qte):
    $_SESSION['PromoCmd'][$ProdSeaC]=$qte;
    $_SESSION['PromoCmdVers'][$ProdSeaC]['version']=$version_nom;
    $_SESSION['PromoCmdVers'][$ProdSeaC]['version_id']=$version;
    if(isset($_SESSION['PromoCmd']) && count($_SESSION['PromoCmd'])>0):

        ?>
        <table class="table">
            <thead>
            <tr>
                <th>Nom du produits</th>
                <th>version</th>
                <th>Qte</th>
                <th></th>
            </tr>
            </thead>

            <?
            foreach ($_SESSION['PromoCmd'] as $key=>$value):?>
                <tr>
                    <td><?echo getinfo($key,'grm_gift','titre')?></td>
                    <td><? echo $_SESSION['PromoCmdVers'][$key]['version']?></td>
                    <td><? echo $value?></td>
                    <td>
                        <a href="<?=$url.$key?>" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?  endforeach;?>
        </table>
        <br/>
        <?php
        if(!$type) {?>
            <a href="materielPromotionnel&annuler=1" class="btn btn-danger pull-left">
                Annuler
            </a>
            <a href="javascript:void(0)" class="btn btn-primary pull-right" id="BtnValiderPromo">
                Valider ma liste
            </a>
            <?
        }else{
            ?>
            <a href="javascript:void(0)" class="btn btn-primary pull-right" id="BtnFinaPromo">
                Finaliser
            </a>
            <?
        }
    endif;
    ?>

<?
else:
//echo 'un'
endif;