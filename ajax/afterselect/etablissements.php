<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 21/03/2018
 * Time: 10:09
 */
require '../../Connextion.php';
require '../../librairie/loadall.php';
$etabs=array('list'=>'','result'=>'');
//print_r($_POST);exit;
if(isset($_POST['delegations']) && $_POST['delegations']) {
    $delegations=implode(',',$_POST['delegations']);
    $request = "SELECT * FROM etablissement WHERE del_id IN ($delegations)";
    $request = $PDO->prepare($request);
    $request->execute();
    $etablissements= $request->fetchAll(PDO::FETCH_ASSOC);
} else if(isset($_POST['secteur']) && $_POST['secteur']) {
    $secteurs=implode(',',$_POST['secteur']);
    $request = "SELECT * FROM etablissement WHERE gouv_id IN ($secteurs)";
    $request = $PDO->prepare($request);
    $request->execute();
    $etablissements= $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    $etablissements=get('*','etablissement');
    $etablissements=$etablissements['reponse'];
}
if($etablissements) {
    foreach ($etablissements as $etab) {
        $etabs['list'].='<option value="'.$etab['id'].'">'.$etab['nom'].'</option>';
    }
    $etabs['result']='success';
    echo json_encode($etabs);exit;
} else {
    $etabs['list']='<option value="" disabled>Aucun établissement à afficher</option>';
    $etabs['result']='success';
    echo json_encode($etabs);exit;
}
$etabs['result']='error';
$etabs['list']=NULL;
echo json_encode($etabs);exit;