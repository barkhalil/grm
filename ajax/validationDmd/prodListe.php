<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 12:32
 */
session_start();
$gamme=filter_input(INPUT_POST,'Gammeid',FILTER_VALIDATE_INT);
if($gamme):
    require_once '../../Connextion.php';
    include '../../librairie/loadall.php';
    $request="select products.*,products_prix.qte from products INNER JOIN products_prix ON products.id=products_prix.id_prod WHERE products.gamme_id=$gamme AND products_prix.qte>0";
    $sql = $PDO->prepare($request);
    $sql->execute();
    $ListeProd = $sql->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="form-group">
        <label for="ProdSeaC">Liste des produits</label>
        <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRod(1)">
            <option value="">Choix du produits</option>
            <?foreach ($ListeProd as $prod):?>
                <option value="<?=$prod['id']?>"><?=$prod['code_article'].' '.$prod['title']?></option>
            <?php endforeach;?>
        </select>
    </div>
<?
else:
    echo false;
endif;