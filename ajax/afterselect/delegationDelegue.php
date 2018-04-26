<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 21/03/2018
 * Time: 11:24
 */
require '../../Connextion.php';
require '../../librairie/loadall.php';
$delegids=array();
$delegs=array('list'=>'','etabs'=>'','result'=>'');
$delId = filter_input(INPUT_POST, 'delId', FILTER_VALIDATE_INT);
if($delId=='') $delId=$_SESSION['user']['id'];
if(isset($_POST['secteur']) && $_POST['secteur']) {
    $secteurs=implode(',',$_POST['secteur']);
    $request = "SELECT DISTINCT delegation.* FROM prospect 
              LEFT JOIN affectation on affectation.id_prospect=prospect.id 
              LEFT JOIN delegation on delegation.id = prospect.delegation 
              WHERE affectation.id_deleg = $delId AND prospect.delegation != '' AND prospect.public > 0 AND delegation.gouv_id IN ($secteurs) ORDER BY delegation.nom ASC";
    $request = $PDO->prepare($request);
    $request->execute();
    $delegations= $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    $delegations=$Pro->GetDel($delId);
}
if($delegations) {
    foreach ($delegations as $deleg) {
        $delegs['list'].='<option value="'.$deleg['id'].'">'.$deleg['nom'].'</option>';
        $delegids[]=$deleg['id'];
    }
    $deleglist=implode(',',$delegids);
    $request = "SELECT DISTINCT etablissement.* FROM prospect  LEFT JOIN affectation on affectation.id_prospect=prospect.id 
                LEFT JOIN etablissement on etablissement.id = prospect.etablissement 
                WHERE affectation.id_deleg = $delId AND prospect.etablissement !='' AND prospect.public > 0 AND etablissement.del_id IN ($deleglist) ORDER BY etablissement.nom ";
    $request = $PDO->prepare($request);
    $request->execute();
    $etablissements= $request->fetchAll(PDO::FETCH_ASSOC);
    foreach ($etablissements as $etab) {
        $delegs['etabs'].='<option value="'.$etab['id'].'">'.$etab['nom'].'</option>';
    }
    $delegs['result']='success';
    echo json_encode($delegs);exit;
}
$delegs['result']='error';
$delegs['list']=NULL;
echo json_encode($delegs);exit;