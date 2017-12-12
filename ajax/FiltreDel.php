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
$ListeEtab=$StdFunctions->getDetailFiltre('etablissement',$filter);
$ListeDel=$StdFunctions->getDetailFiltre('delegation',$filter);
$ListeSpecG=$StdFunctions->getDetailFiltre('specialite',$filter,false);
$ListeActivite=$StdFunctions->getDetailFiltre('activite',$filter,false);
$ListePotentiel=$StdFunctions->getDetailFiltre('potentiel',$filter,false);
    // ajouter des filtre selon liste_detail ou ALL :
?>
<div class="clearfix"></div>
 <div class="col-md-4">
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
<div class="col-md-4">
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

    <div class="col-md-4">
    <div class="form-group">
<label>Etablissement</label>
<select id="EtablissementListe" name="Etab[]" class="form-control select2" multiple>
<? 
foreach ($ListeEtab as $listes):
    foreach ($listes['reponse'] as $ett): ?>
    <option value="<?=$ett['id']?>"><?=$ett['nom']?></option>

    <?       endforeach;
                              endforeach;?>
                          </select>
</div>
</div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Choix d'activités</label>
            <select class="form-control select2" name="ActiviteFilter[]" multiple>
                <?php
                foreach($ListeActivite as $ListeA):
                foreach($ListeA['reponse'] as $act):         ?>
                    <option value="<?=$act['id']?>" ><?=$act['nom']?></option>
                <?endforeach?>
                <?endforeach?>

            </select>

        </div>
    </div>
<div class="col-md-4">
    <label>Potentiel</label>
    <select class="form-control select2" name="Potentiel[]" multiple>
        <?php
        foreach($ListePotentiel as $ListeP):
        foreach($ListeP['reponse'] as $potentiel):         ?>
            <option value="<?=$potentiel['id']?>" ><?=$potentiel['valeur']?></option>
        <?endforeach?>
        <?endforeach?>

    </select>
</div>