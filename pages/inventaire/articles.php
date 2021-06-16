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
    <th>ID CRM</th>
    <th>Code article</th>
    <th>Désignation</th>
    <th>Déscription</th>
    <th>Famille</th>
    <th>Quantité en stock</th>
    <th>Quantité utiliser</th>
    <th>Prix  achat</th>
    <th>Prix vente hors taxe</th>
    <th>Prix vente TTC</th>
</tr>";
$request="SELECT *,grm_gift.id as idcrm,grm_gift_family.nom as family FROM grm_gift LEFT JOIN grm_gift_family ON grm_gift.famille=grm_gift_family.id where grm_gift.etat=1 ORDER BY grm_gift.famille";
$stmt=$PDO->prepare($request);
$stmt->execute();
$gifts= $stmt->fetchAll(PDO::FETCH_ASSOC);
//$products=get('*','products',NULL,'AND',array('name'=>'ASC'));
//echo '<pre>';print_r($gifts);die;
foreach ($gifts as $gift):
    $qte=getStockProd($gift['idcrm'])->qte;
if(!$qte){
    $qte=0;
}
    $excel.="
<tr>
    <td>".$gift['idcrm']."</td> 
    <td>".$gift['code_article']."</td> 
    <td>".$gift['titre']."</td> 
    <td>".$gift['description']."</td> 
    <td>".$gift['family']."</td> 
    <td>".$qte."</td> 
    <td></td> 
    <td>".$gift['paht']."</td> 
    <td>".$gift['pvht']."</td> 
    <td>".$gift['pvttc']."</td> 
</tr>";
endforeach;
$excel.="</table>";
//echo $excel;
header( 'content-type: text/html; charset=utf-8' );
header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-disposition: attachment; filename=inventaireArtc-".$now.".xls");

print $excel;

exit;