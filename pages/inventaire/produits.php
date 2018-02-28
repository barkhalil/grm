<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 28/02/18
 * Time: 09:26
 */
$now=date('d-m-Y');
$excel ="<table border='1px'>
<tr>
    <th>Code produit</th>
    <th>Désignation</th>
    <th>Déscription</th>
    <th>Catégorie</th>
    <th>Gamme</th>
    <th>Prix public</th>
    <th>Prix pharmacien</th>
    <th>Prix grossiste</th>
    <th>Quantité en stock</th>
</tr>";
$request="SELECT *,ventes_gamme.name as gamme,products.name as produit FROM products LEFT JOIN products_prix ON products.id=products_prix.id_prod LEFT JOIN ventes_gamme ON products.gamme_id=ventes_gamme.id ORDER BY products.code_article";
$stmt=$PDO->prepare($request);
$stmt->execute();
$products= $stmt->fetchAll(PDO::FETCH_ASSOC);
//$products=get('*','products',NULL,'AND',array('name'=>'ASC'));
//echo '<pre>';print_r($products);die;
foreach ($products as $product):
    $excel.="
<tr>
    <td>".$product['code_article']."</td> 
    <td>".$product['produit']."</td> 
    <td>".$product['description']."</td> 
    <td>".$product['categorie']."</td> 
    <td>".$product['gamme']."</td> 
    <td>".$product['prix_public']."</td> 
    <td>".$product['prix_ph']."</td> 
    <td>".$product['prix_gros']."</td> 
    <td>".$product['qte']."</td> 
</tr>";
endforeach;
$excel.="</table>";
//echo $excel;
header( 'content-type: text/html; charset=utf-8' );
header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-disposition: attachment; filename=inventaireProd-".$now.".xls");

print $excel;

exit;