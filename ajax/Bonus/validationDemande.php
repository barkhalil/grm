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
$idDemande=filter_input(INPUT_POST,'idDemande',FILTER_VALIDATE_INT);
$idRemise=filter_input(INPUT_POST,'idRemise',FILTER_VALIDATE_INT);
$ObsAdm=filter_input(INPUT_POST,'ObsAdm',FILTER_DEFAULT);
$Pbs=get('*','grm_pb_type',array('etat='=>1));
$points=array();
if($_SESSION['TotalCdx']>0){
    $cadeauxDmd=get('*','grm_cadeaux_demander',array('id_demande='=>$idDemande));
    foreach ($Pbs['reponse'] as $pb) {
        if($_SESSION['Point'.$pb['id']]) {
            $points[]=$_SESSION['Point'.$pb['id']];
        }
    }
    if(count($points)>0){
        $pointByType=implode($points,'@_@');
    } else {
        $pointByType=getinfo($idDemande,'grm_demande_cadeaux','ponitsByType');
    }
    $data=array(
        'point_bonus_reel'=>$_SESSION['TotPoint'],
        'rest_point'=>$_SESSION['TotPoint']-$_SESSION['TotalCdx'],
        'id_remise'=>$idRemise,
        'oberservation_admin'=>$ObsAdm,
        'date_validation'=>date('Y-m-d'),
        'modifier_par'=>$_SESSION['user']['id'],
        'pointsRealByType'=>$pointByType,
        'famille'=>10
    );
    $data['etat']=4;

    update($idDemande,$data,'grm_demande_cadeaux');
    //echo '<pre>';print_r($_SESSION['CdxCmd']);exit;
    foreach ($cadeauxDmd['reponse'] as $cdx) {
        delete($cdx['id'],'grm_cadeaux_demander');
    }
    //echo 'ok22';exit;
    foreach ($_SESSION['ProdPbCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idDemande,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>1,
        );
        add($dataProd,'grm_cadeaux_demander');
    endforeach;
    foreach($_SESSION['CdxCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idDemande,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>2,
        );
        add($dataProd,'grm_cadeaux_demander');
    endforeach;
    //echo 'ok22';exit;
    $pointsBonus->viderSession();exit;
}else{
    echo false;
}