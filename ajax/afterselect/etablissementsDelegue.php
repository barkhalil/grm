<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 21/03/2018
 * Time: 16:36
 */
require '../../Connextion.php';
require '../../librairie/loadall.php';
$etabs=array('list'=>'','result'=>'');
$delId = filter_input(INPUT_POST, 'delId', FILTER_VALIDATE_INT);
if($delId=='') $delId=$_SESSION['user']['id'];
//print_r($_POST);exit;
if(isset($_POST['delegations']) && $_POST['delegations']) {
    $delegations=implode(',',$_POST['delegations']);
    $request = "SELECT DISTINCT etablissement.* FROM prospect  LEFT JOIN affectation on affectation.id_prospect=prospect.id LEFT JOIN etablissement on etablissement.id = prospect.etablissement 
             WHERE affectation.id_deleg = $delId AND prospect.etablissement !='' AND prospect.public > 0 AND del_id IN ($delegations) ORDER BY etablissement.nom";
    //$request = "SELECT * FROM etablissement WHERE del_id IN ($delegations)";
    $request = $PDO->prepare($request);
    $request->execute();
    $etablissements= $request->fetchAll(PDO::FETCH_ASSOC);
} else if(isset($_POST['secteur']) && $_POST['secteur']) {
    $secteurs=implode(',',$_POST['secteur']);
    $request = "SELECT DISTINCT etablissement.* FROM prospect  LEFT JOIN affectation on affectation.id_prospect=prospect.id LEFT JOIN etablissement on etablissement.id = prospect.etablissement 
             WHERE affectation.id_deleg = $delId AND prospect.etablissement !='' AND prospect.public > 0 AND gouv_id IN ($secteurs) ORDER BY etablissement.nom";
    //$request = "SELECT * FROM etablissement WHERE gouv_id IN ($secteurs)";
    $request = $PDO->prepare($request);
    $request->execute();
    $etablissements= $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    $etablissements=$Pro->GetEtab($delId);
}
if($etablissements) {
    foreach ($etablissements as $etab) {
        $etabs['list'].='<option value="'.$etab['id'].'">'.$etab['nom'].'</option>';
    }
    $etabs['result']='success';
    echo json_encode($etabs);exit;
}else {
    $etabs['list']='<option value="" disabled>Aucun établissement à afficher</option>';
    $etabs['result']='success';
    echo json_encode($etabs);exit;
}
$etabs['result']='error';
$etabs['list']=NULL;
echo json_encode($etabs);exit;