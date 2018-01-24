<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 24/01/18
 * Time: 14:45
 */
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$product=filter_input(INPUT_POST,'product',FILTER_VALIDATE_INT);
$qte=filter_input(INPUT_POST,'qte',FILTER_VALIDATE_INT);
//echo $product;exit;
if($product) {
    $qteStock=get('*','products_prix',array('id_prod='=>$product));
    //print_r($qteStock);exit;
    if($qteStock['reponse'][0]['qte']<$qte) {
        $retour=array('message'=>'Quantité du produits en stock est '.$qteStock['reponse'][0]['qte'],'status'=>'error');
    } else {
        $retour=array('status'=>'success');
    }
    echo json_encode($retour);
    exit;
}
