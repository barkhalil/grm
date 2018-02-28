<?php

/**
 * Created by PhpStorm.
 * User: Nagui
 * Date: 12/06/2017
 * Time: 00:02
 */
class Products{
    public function getProdAlert(){
        global $PDO;
        $Sql="SELECT * FROM products JOIN products_prix ON products.id=products_prix.id_prod WHERE products_prix.qte<=0";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPrix($prodId,$Type=1){
        global $PDO;
        $Sql="SELECT * FROM products_prix WHERE products_prix.id_prod  = $prodId and type = $Type";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function getAll(){
        global $PDO;
        $Sql="SELECT products.id,products.code_article,products.name,products.description,products_prix.qte from products INNER JOIN products_prix ON products_prix.id_prod=products.id ORDER BY products.name";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function increaseQte($id,$qte) {
        global $PDO;
        $Sql = "UPDATE products_prix SET products_prix.qte=qte+$qte WHERE id_prod = $id";
        //echo $Sql.' <br/> ';
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
    }
}
$ProdClass=new Products();