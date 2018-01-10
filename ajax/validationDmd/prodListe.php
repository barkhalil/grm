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
    $ListeProd=get("*",'products',array('gamme_id='=>$gamme));
    ?>
    <div class="form-group">
        <label for="ProdSeaC">Liste des produits</label>
        <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRod(1)">
            <option value="">Choix du produits</option>
            <?foreach ($ListeProd['reponse'] as $prod):?>
                <option value="<?=$prod['id']?>"><?=$prod['title']?></option>
            <?php endforeach;?>
        </select>
    </div>
<?
else:
    echo false;
endif;