<?php
/**
 * Created by PhpStorm.
 * User: NAGUI
 * Date: 17/11/2015
 * Time: 22:34
 */
require '../Connextion.php';
require '../librairie/loadall.php';
$SectFilter=filter_input(INPUT_POST,'secteur',FILTER_DEFAULT);
$liste=get("*",'delegation',array('gouv_id ='=>$SectFilter),"AND",array('nom' => 'ASC'));
$listeEtab=get("*",'etablissement',array('gouv_id ='=>$SectFilter),"AND",array('nom' => 'ASC'));

?>
<label for="delegationListe">Choix de délégation</label>
<select id="delegationListe" name="delegation" class="form-control" required onchange="FindEtabSimple()">
    <option value="">----</option>
<? 

    foreach ($liste['reponse'] as $peos): ?>
    <option value="<?=$peos['id']?>" rel="<?=getGenInfo($peos['id'],'postal_code',"id_del" ,'nom') ?>"><?=$peos['nom']?></option>
                               <?  endforeach;?>
                           
                          </select>
<br/>
<div id="EtabListDiv"></div>
