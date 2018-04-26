<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 19/03/2018
 * Time: 17:29
 */
require '../../Connextion.php';
require '../../librairie/loadall.php';
$delegids=array();
$delegs=array('list'=>'','etabs'=>'','result'=>'');
if(isset($_POST['secteur']) && $_POST['secteur']) {
    $secteurs=implode(',',$_POST['secteur']);
    $request = "SELECT * FROM delegation WHERE gouv_id IN ($secteurs) ORDER BY nom";
    $request = $PDO->prepare($request);
    $request->execute();
    $delegations= $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    $delegations=get('*','delegation');
    $delegations=$delegations['reponse'];
}
if($delegations) {
    foreach ($delegations as $deleg) {
        $delegs['list'].='<option value="'.$deleg['id'].'">'.$deleg['nom'].'</option>';
        $delegids[]=$deleg['id'];
    }
    $delegs['result']='success';
    echo json_encode($delegs);exit;
}
$delegs['result']='error';
$delegs['list']=NULL;
echo json_encode($delegs);exit;