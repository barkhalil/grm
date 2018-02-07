<?php

/**
 * Created by PhpStorm.
 * User: Nagui
 * Date: 12/06/2017
 * Time: 00:02
 */
class Products{

public function getPrix($prodId,$Type=1){
    global $PDO;
    $Sql="SELECT * FROM products_prix WHERE products_prix.id_prod  = $prodId and type = $Type";
    $stmt=$PDO->prepare($Sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);

}
public function getAll(){
    global $PDO;
    $Sql="SELECT * from products INNER JOIN products_prix ON products_prix.id_prod=products.id";
    $stmt=$PDO->prepare($Sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}


}
$ProdClass=new Products();