<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 25/04/2017
 * Time: 11:32
 */
session_start();
require_once '../../Connextion.php';
include '../../librairie/loadall.php';
$gamme=filter_input(INPUT_POST,'Gammeid',FILTER_VALIDATE_INT);
if($gamme):
    //affecher les produits de la gamme choisit avec qte >0
    $request="select products.*,products_prix.qte from products INNER JOIN products_prix ON products.id=products_prix.id_prod WHERE products.gamme_id=$gamme";
    $sql = $PDO->prepare($request);
    $sql->execute();
    $ListeProd = $sql->fetchAll(PDO::FETCH_ASSOC);
    //$ListeProd=get("*",'products',array('gamme_id='=>$gamme));
    ?>
    <div class="form-group">
        <label for="ProdSeaC">Liste des produits</label>
        <select class="form-control select2" name="prodListe" id="ProdSeaC">
            <option value="">Choix du produits</option>
            <?foreach ($ListeProd as $prod):?>
            <option value="<?=$prod['id']?>"><?=$prod['title']?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="qte">Qte</label>
        <input type="number" id="qte" name="qte" value="1" step="1" min="1" class="form-control"> 
        <label class="hidden" id="errorMsgQte"></label>
    </div>
<?
else:
echo false;
endif;