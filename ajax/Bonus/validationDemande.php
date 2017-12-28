<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 05/06/2017
 * Time: 12:35
 */
//just call this page for geting session and add to data base :p
//ALTER TABLE `grm_demande_cadeaux` ADD `pointsRealByType` VARCHAR(255) NULL DEFAULT NULL AFTER `ponitsByType`;
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
if($_SESSION['TotalCdx']>0){
    // ajouter la demande :
    $data=array(
        'point_bonus_reel'=>$_SESSION['TotPoint'],//rest !!!
        'observation_admin'=>$_SESSION['ObsAdmin'],
        'etat'=>4
    );

    foreach ($EchantListe['reponse'] as $item):
        delete($item['id'],'promo_prod');
    endforeach;
    $idD=add($data,'grm_demande_cadeaux');
    foreach ($_SESSION['ProdPbCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idD,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>1,
        );
        add($dataProd,'grm_cadeaux_demander');
    endforeach;
    foreach ($_SESSION['CdxCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idD,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>2,
        )   ;
        add($dataProd,'grm_cadeaux_demander');
    endforeach;


}else{
    echo false;
}