<?php
/**
 * Created by PhpStorm.
 * User: NAGUI
 * Date: 17/11/2015
 * Time: 22:34
 */
session_start();
require '../Connextion.php';
require '../librairie/loadall.php';
// selon le secteur choisie tous autres filtre seras executer :
$filter=filter_input(INPUT_POST,'secteur',FILTER_VALIDATE_INT);
$ListeDel=$StdFunctions->getDetailFiltre('delegation',$filter);
$ListeSpecG=$StdFunctions->getDetailFiltre('specialite',$filter,false);
// ajouter des filtre selon liste_detail ou ALL :
?>
<div class="clearfix"></div>
<div class="col-md-12">
    <div class="form-group">
        <label>Choix de délégation</label>
        <select id="delegationListe" name="delegation[]" class="form-control select2" multiple>
            <?
            foreach ($ListeDel as $liste):
                foreach ($liste['reponse'] as $peos): ?>

                    <option value="<?=$peos['id']?>"><?=$peos['nom']?></option>
                    <?
                endforeach;?>
            <?  endforeach;?>
        </select>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label>Choix des Spécialités</label>
        <select id="Spec" name="Spec[]" class="form-control select2" multiple="multiple">
            <?php
            foreach($ListeSpecG as $ListeSpec):
                foreach($ListeSpec['reponse'] as $spec):?>
                    <option value="<?=$spec['id']?>" ><?=$spec['nom']?></option>
                <?endforeach;endforeach?>
        </select>
    </div>

</div>



