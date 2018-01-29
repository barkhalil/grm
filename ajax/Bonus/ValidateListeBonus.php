<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 05/06/2017
 * Time: 12:35
 */
//just call this page for geting session and add to data base :p
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$Pbs=get('*','grm_pb_type',array('etat='=>1));
$observation=filter_input(INPUT_POST,'observation',FILTER_DEFAULT);
$points=array();
foreach ($Pbs['reponse'] as $pb) {
    if($_SESSION['Point'.$pb['id']]) {
        $points[]=$_SESSION['Point'.$pb['id']];
    }
}
$pointByType=implode($points,'@_@');
if($_SESSION['TotalCdx']>0) {
    // ajouter la demande :
    $data=array(
        'id_pros'=>$_SESSION['PbClient'],
        'id_demandeur'=>$_SESSION['user']['id'],
        'point_bonus'=>$_SESSION['TotPoint'],//rest !!!
        'rest_point'=>$_SESSION['TotPoint']-$_SESSION['TotalCdx'],
        'isCart'=>$_SESSION['cdxSansPB'],
        'observation_client'=>$_SESSION['Obs'],
        'date_remise_point'=>date('Y-m-d'),
        'cree_par'=>$_SESSION['user']['id'],
        'ponitsByType'=>$pointByType,
        'etat'=>0,
        'famille'=>10

    );
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
    foreach($_SESSION['CdxCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idD,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>2,
        );
        add($dataProd,'grm_cadeaux_demander');
    endforeach;
    $pointsBonus->viderSession($_SESSION['PbClient']);
}else{
    echo false;
}