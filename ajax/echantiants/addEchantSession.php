<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 17:47
 */
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$ProdSeaC=filter_input(INPUT_POST,'ProdSeaC',FILTER_VALIDATE_INT);
$qte=filter_input(INPUT_POST,'qte',FILTER_VALIDATE_INT);
$type=filter_input(INPUT_POST,'type',FILTER_VALIDATE_INT);
$delegue=filter_input(INPUT_POST,'delegue',FILTER_VALIDATE_INT);
$_SESSION['delegue']=$delegue;
if(!$type) {
    $uType=getinfo($_SESSION['delegue'],'users','type');
    $url="echantiants&IdSup=";
}else{
    $uType=$type;
    $url="EchantDetails&IdSup=";
}
$Quota=getinfo($uType,'user_type','quota'); // général :
if($ProdSeaC && $qte):
    $TotEch=$_SESSION['TotalEchant'] ? $_SESSION['TotalEchant'] : 0;
        //test si le m$eme prod ou non :
        if(isset($_SESSION['EchantCmd'][$ProdSeaC]) && $_SESSION['EchantCmd'][$ProdSeaC]>0){
            $_SESSION['TotalEchant']= $_SESSION['TotalEchant']-$_SESSION['EchantCmd'][$ProdSeaC];
            $_SESSION['EchantCmd'][$ProdSeaC]=$qte;
            $_SESSION['TotalEchant']+=$qte;
            $TotEch=$_SESSION['TotalEchant'];
        }else{
            $_SESSION['EchantCmd'][$ProdSeaC]=$qte;
            $_SESSION['TotalEchant']+=$qte;
            $TotEch=$_SESSION['TotalEchant'];
        }



    echo "<h3>Total Qte : ".$TotEch." / $Quota </h3>";
    if(isset($_SESSION['EchantCmd']) && count($_SESSION['EchantCmd'])>0):

        ?>
        <table class="table">
            <thead>
            <tr>
                <th>Nom du produits</th>
                <th>Qte</th>
                <th></th>
            </tr>
            </thead>

            <?
            foreach ($_SESSION['EchantCmd'] as $key=>$value):?>
                <tr>
                    <td><?echo getinfo($key,'products','name')?></td>
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
            <a href="echantiants&annuler=1" class="btn btn-danger pull-left">
                Annuler
            </a>
            <a href="javascript:void(0)" class="btn btn-primary pull-right" id="BtnValiderEchant">
                Valider ma liste
            </a>
            <?
        }else{
            ?>
            <a href="javascript:void(0)" class="btn btn-primary pull-right" id="BtnFinaEchant">
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