<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 19/03/2018
 * Time: 17:29
 */
require '../../Connextion.php';
require '../../librairie/loadall.php';
$prodids=array();
$prods=array('list'=>'','name'=>'','result'=>'');
$gamme=filter_input(INPUT_POST,'gamme',FILTER_DEFAULT);
//$gamme=1111;
if($gamme) {
    // $gamme=implode(',',$_POST['gamme']);
    $request = "SELECT * FROM grm_art_version WHERE id_art in ($gamme) ORDER BY id desc";
    $request = $PDO->prepare($request);
    $request->execute();
    $prod= $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    $prod=get('*','grm_art_version');
    $prod=$prod['reponse'];
}
if($prod) {
    foreach ($prod as $deleg) {
        $prods['list'].='<option value="'.$deleg['id'].'">'.$deleg['version'].'</option>';
        $prodids[]=$deleg['id'];
    }
    $prodlist=implode(',',$prodids);

    $prods['result']='success';
    echo json_encode($prods);exit;
}else{
    $prods['result']='error';
    $prods['list']=NULL;
    echo json_encode($prods);exit;
}

