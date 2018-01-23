<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 15:36
 */
require_once "../Connextion.php";
require_once "../librairie/loadall.php";
$famille=filter_input(INPUT_POST,'famille',257);
if($famille):
    ?>




    <div class="form-group">
        <label>Les articles disponible : </label>
        <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRodDemande()">
            <option value="">Choix</option>
            <?
            // ajouter filter par famille
            if($famille==1){
                /*  $ListeGift = get('*', 'grm_gift',array(
                      //  'dispo =' => 1,
                      'qte >=' => 1,
                      'famille IN( ) '=>'1,3'
                  ));*/
                $sql=" Select * from grm_gift WHERE qte >=1 and famille IN(1,3)";
                $stmt=$PDO->prepare($sql);
                $stmt->execute();
                $ListeGift['reponse']=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $ListeGift = get('*', 'grm_gift',array(
                    //  'dispo =' => 1,
                    'qte >=' => 1,
                    'famille = '=>$famille
                ));
            }

            foreach ($ListeGift['reponse'] as $Gift):
                ?>
                <option value="<?= $Gift['id'] ?>" rel="<?= $Gift['serialisable'] ?>" bonus="<?=$Gift['point_bonus']?>" ><?= $Gift['titre'] . ' Points : ' . $Gift['point_bonus'] ?></option>
            <? endforeach; ?>

        </select>

    </div>
<?
else:
    echo "Merci de choisir une famille";
endif;
?>