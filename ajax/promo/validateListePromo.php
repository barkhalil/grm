<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 17:15
 */
//ALTER TABLE `promo_demander` ADD `created_by` INT NULL AFTER `par`;
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$MonthValue=filter_input(INPUT_POST,'MonthValue',FILTER_DEFAULT);
if($_SESSION['PromoCmd']>0){
    // ajouter la demande :
    $data=array(
        'created_by'=>$_SESSION['user']['id'],
        'par'=>$_SESSION['delegue'],
        'etat'=>0,

    );
    $idD=add($data,'promo_demander');
    foreach ($_SESSION['PromoCmd'] as $key=>$value):
        $dataProd=array(
            'id_promo'=>$idD,
            'id_prod'=>$key,
            'qte'=>$value
        );
        add($dataProd,'promo_prod');
    endforeach;
    echo true;
    exit;
}else{
    echo false;
}