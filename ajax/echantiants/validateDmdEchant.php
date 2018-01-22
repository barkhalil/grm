<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 18:07
 */
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$MonthValue=filter_input(INPUT_POST,'MonthValue',FILTER_DEFAULT);
if($MonthValue && $_SESSION['TotalEchant']>0) {
    // ajouter la demande :
    $data=array(
        'created_by'=>$_SESSION['user']['id'],
        'par'=>$_SESSION['delegue'],
        'pour'=>$MonthValue.'-01',
        'etat'=>0,

    );
    $idD=add($data,'echant_demander');
    foreach ($_SESSION['EchantCmd'] as $key=>$value):
        $dataProd=array(
            'id_echant'=>$idD,
            'id_prod'=>$key,
            'qte'=>$value
        );
        add($dataProd,'echant_prod');
    endforeach;
    unset($_SESSION['EchantCmd']);
    unset($_SESSION['delegue']);
} else {
    echo false;
}