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

?>
<label for="delegationListe">Choix de délégation</label>
<select id="delegationListe" name="delegation" class="form-control" required >
    <option value="">----</option>
<? 

    foreach ($liste['reponse'] as $peos): ?>
    <option value="<?=$peos['id']?>"><?=$peos['nom']?></option>
        <?  endforeach;?>
                           
                          </select>
<br/>