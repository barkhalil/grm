<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 05/05/2017
 * Time: 16:12
 */
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$ProdSeaC=filter_input(INPUT_POST,'ProdSeaC',FILTER_VALIDATE_INT);
$qte=filter_input(INPUT_POST,'qte',FILTER_VALIDATE_INT);
$type=filter_input(INPUT_POST,'type',FILTER_VALIDATE_INT);
$newPb=filter_input(INPUT_POST,'newPb',FILTER_VALIDATE_INT);
$TotPoint=filter_input(INPUT_POST,'TotPoint',FILTER_DEFAULT);
$Point=filter_input(INPUT_POST,'Point',FILTER_VALIDATE_INT);
$prodbonus=filter_input(INPUT_POST,'prodbonus',FILTER_VALIDATE_INT);
$client=filter_input(INPUT_POST,'client',FILTER_VALIDATE_INT);
$idDemande=filter_input(INPUT_POST,'idDemande',FILTER_VALIDATE_INT);
$Obs=filter_input(INPUT_POST,'Obs',FILTER_SANITIZE_STRING);
$Pbs=get('*','grm_pb_type',array('etat='=>1));
$pb=$Pbs['reponse'][0];// pour avoir le nombre de point bonus par produits ==> exemple 10 point bonus par produits : qte*10
$Quota=$TotPoint; // général :
foreach ($_POST['ponits'] as $key=>$valeurPb) {
    $_SESSION['Point'.$key]=$valeurPb;
}
$_SESSION['newPb']=$newPb;
$_SESSION['PbClient']=$client;
$_SESSION['Point']=$Point;
$_SESSION['TotPoint']=$TotPoint;
$_SESSION['ObsAdmin']=$Obs;
$Value =$prodbonus;
if($ProdSeaC && $qte):
    $TotEch=isset($_SESSION['TotalCdx']) ? $_SESSION['TotalCdx'] : 0;
    if($TotEch<=$Quota && $Quota >0 && ($TotEch+$qte*$Value)<=$Quota){
        // deux session en // => Cadeaux et produits :
        if($type==1): // article
            //test si le m$eme prod ou non : // possible bug même prod !!!!
            if(isset($_SESSION['CdxCmd'][$ProdSeaC]) && $_SESSION['CdxCmd'][$ProdSeaC]>0){
                $_SESSION['TotalCdx']= $_SESSION['TotalCdx']-($_SESSION['CdxCmd'][$ProdSeaC]*$prodbonus);
                $_SESSION['CdxCmd'][$ProdSeaC]=$qte;
                $_SESSION['TotalCdx']+=$qte*$prodbonus;
                $TotEch=$_SESSION['TotalCdx'];
            }else{
                $_SESSION['CdxCmd'][$ProdSeaC]=$qte;
                $_SESSION['TotalCdx']+=$qte*$prodbonus;
                $TotEch=$_SESSION['TotalCdx'];
            }
        else:
            //test si le m$eme prod ou non :
            if(isset($_SESSION['ProdPbCmd'][$ProdSeaC]) && $_SESSION['ProdPbCmd'][$ProdSeaC]>0){
                $_SESSION['TotalCdx']= $_SESSION['TotalCdx']-($_SESSION['ProdPbCmd'][$ProdSeaC]*$pb['value']);
                $_SESSION['ProdPbCmd'][$ProdSeaC]=$qte;
                $_SESSION['TotalCdx']+=$qte*$prodbonus;
                $TotEch=$_SESSION['TotalCdx'];
            }else{
                $_SESSION['ProdPbCmd'][$ProdSeaC]=$qte;
                $_SESSION['TotalCdx']+=$qte*$prodbonus;
                $TotEch=$_SESSION['TotalCdx'];
            }

        endif;



    }else{
        echo "<h2 class='bg-danger'>Max Pb est attent</h2>";
    }

    echo "<h3>Total point bonus : ".$TotEch." / <span class='totPB'>$Quota</span> </h3>";


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
        if((isset($_SESSION['ProdPbCmd']) && count($_SESSION['ProdPbCmd'])>0)):
            foreach ($_SESSION['ProdPbCmd'] as $key=>$value):?>
                <tr>
                    <td><?echo getinfo($key,'products','name')?></td>
                    <td><? echo $value?></td>
                    <td>
                        <a href="DmdPb&idDemande=<?=$idDemande?>&IdSup=<?=$key?>&prod=1" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?  endforeach; endif;?>
        <? if(isset($_SESSION['CdxCmd']) && count($_SESSION['CdxCmd'])>0):
            foreach ($_SESSION['CdxCmd'] as $key=>$value):?>
                <tr>
                    <td><?echo getinfo($key,'grm_gift','titre')?></td>
                    <td><? echo $value?></td>
                    <td>
                        <a href="DmdPb&idDemande=<?=$idDemande?>&IdSup=<?=$key?>" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?  endforeach; endif;?>
    </table>
    <br/>
    <a href="DmdPb&annuler=1" class="btn btn-danger pull-left">
        Annuler
    </a>
    <a href="javascript:void(0)" class="btn btn-success pull-right" onclick="FinalisationPb()">Finaliser</a>
    <div class="clearfix"></div>

<?
else:
//print_r($_POST);
endif;