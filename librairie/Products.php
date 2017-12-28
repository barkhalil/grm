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


}
$ProdClass=new Products();