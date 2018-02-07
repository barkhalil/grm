<?php

/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 16/11/2016
 * Time: 13:33
 */
// class for all Budget and control products ...
class GrmController {
    function getBudget($type, $year){
        global $PDO;
        /** @var  $Sql */
        $Sql=" Select sold From grm_budget_annuel WHERE type = $type and years = $year";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        $res=$stmt->fetch(PDO::FETCH_OBJ)->sold;
        return $res;
    }
    function getBudgetZone($type, $year,$zone){
        global $PDO;
        /** @var  $Sql */
        $Sql=" Select sold From grm_budget_annuel_zone WHERE type = $type and years = $year and zone = $zone";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        $res=$stmt->fetch(PDO::FETCH_OBJ)->sold;
        return $res;
    }
    function DimStock($id,$qte){
        global $PDO;
        $Sql="UPDATE grm_gift SET grm_gift.qte=qte-$qte , grm_gift.qte_utiliser = qte_utiliser+$qte WHERE id = $id";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
    }
    function DimStockProduits($idPord,$qte)
    {
        global $PDO;
        $Sql = "UPDATE products_prix SET products_prix.qte=qte-$qte WHERE id_prod = $idPord";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
    }
    function DimStockProd($id,$qte){
        $this->DimStockProduits($id,$qte);
    }
    function edit_stock_gifts($id,$qte){
        global $PDO;
        $Sql="UPDATE grm_gift SET grm_gift.qte=qte+$qte , grm_gift.qte_utiliser = qte_utiliser-$qte WHERE id = $id";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
    }
    function edit_stock_prods($id,$qte){
        global $PDO;
        $Sql = "UPDATE products_prix SET products_prix.qte=qte+$qte WHERE id_prod = $id";
        //echo $Sql;die;
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
    }
}
$Gcc=new GrmController();