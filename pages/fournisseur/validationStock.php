<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 10/01/2017
 * Time: 11:21
 */
if($idStock){
    //update grm gift :
    update($id,array(
        'stock_id'=>$idStock,
        'qte'=>$CadeauxDetails['qte']+filter_input(0,'Newqte',257)
    ),'grm_gift');
    $_SESSION['msg'] = "Stock et prix ajouter";
    $_SESSION['type'] = "alert-success";
    redirect('../gift/ListeCadeaux');
}else{
    $_SESSION['msg'] = "problème erreur mochkla wa 7lili";
    $_SESSION['type'] = "alert-danger";
}