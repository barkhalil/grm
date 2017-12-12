<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 25/10/2016
 * Time: 16:23
 */
require '../Connextion.php';
require '../librairie/loadall.php';
$SectFilter=filter_input(INPUT_POST,'secteur',FILTER_DEFAULT); // etablissement
$listeEtab=get("*",'etablissement',array('del_id ='=>$SectFilter),"AND",array('nom' => 'ASC'));
?>
<label for="EtabSelc">Etablissement</label>
<select class="form-control" name="Etab" id="EtabSelc">
    <option value="">----</option>
    <?
    foreach ($listeEtab['reponse'] as $Eta):
        ?>
        <option value="<?= $Eta['id'] ?>" rel="<?if($Eta['del_id']) echo getGenInfo($Eta['del_id'],'postal_code',"id_del" ,'nom')?>"><?= $Eta['nom'] ?></option>
    <? endforeach; ?>
</select>