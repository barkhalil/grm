<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 09/02/18
 * Time: 11:10
 */
session_start();
require_once '../Connextion.php';
include '../librairie/loadall.php';
/*
 * <input type="hidden" value="10" name="29"> 51
 * */
//echo 'ok';exit;
//$products=filter_input(INPUT_POST,'products',FILTER_DEFAULT);
$products=$_POST['products'];
$msg=filter_input(INPUT_GET,'msg',FILTER_VALIDATE_INT);
$fournisseur=filter_input(INPUT_POST,'fournisseur',FILTER_DEFAULT);
$ref=filter_input(INPUT_POST,'ref',FILTER_DEFAULT);
$dateSelect=filter_input(INPUT_POST,'dateSelect',FILTER_DEFAULT);
//print_r($products);exit;
$dateSelect = str_replace('/', '-', $dateSelect);
$dateSelect= date('Y-m-d', strtotime($dateSelect));
if($msg) {
    $_SESSION['msg'] = "Bon d'entrée enregistré";
    $_SESSION['type'] ="alert-success";
    redirect('../products/bonEntree');
}
$bonDEntr=array(
    'fournisseur'=>$fournisseur,
    'reference'=>$ref,
    'date_bn_entr'=>$dateSelect,
    'created_by'=>$_SESSION['user']['id']
);
if($idBn=add($bonDEntr,'prod_ref_stock')) {
    foreach ($products as $product) {
        //echo $product['id'].' '.$product['qte'].' <br/> ';
        $prodStock=array(
            'idBonEnt'=>$idBn,
            'fournisseur'=>$fournisseur,
            'prod'=>$product['id'],
            'qte'=>$product['qte']
        );
        //echo $product['qte'].' id: '.$product['id'].' <br/> ';
        //$ProdClass->increaseQte($product['id'],$product['qte']);
        //add($prodStock,'prod_stock');exit;
        if(add($prodStock,'prod_stock')) {
            $ProdClass->increaseQte($product['id'],$product['qte']);
        } else {
            echo 'error';exit;
        }
    }
}else {
    echo 'error1';exit;
}
echo 'success';exit;