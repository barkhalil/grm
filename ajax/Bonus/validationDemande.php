<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 05/06/2017
 * Time: 12:35
 */
//just call this page for geting session and add to data base :p
session_start();
require '../../Connextion.php';
include '../../librairie/loadall.php';
$algo = "PASSWORD_BCRYPT";
$options = [
    'cost' => 12,
];
$pp=filter_input(INPUT_POST,'pp',FILTER_VALIDATE_INT);
$etat=filter_input(INPUT_POST,'etat',FILTER_VALIDATE_INT);
$_SESSION['cdxSansPB']=$pp;
$idDemande=filter_input(INPUT_POST,'idDemande',FILTER_VALIDATE_INT);
$idRemise=filter_input(INPUT_POST,'idRemise',FILTER_VALIDATE_INT);
$ObsAdm=filter_input(INPUT_POST,'ObsAdm',FILTER_DEFAULT);
$Pbs=get('*','grm_pb_type',array('etat='=>1));
$points=array();

if(!$idDemande) {
    foreach ($Pbs['reponse'] as $pb) {
        if ($_SESSION['Point' . $pb['id']]) {
            $points[] = $_SESSION['Point' . $pb['id']];
        }
    }
    $pointByType = implode($points, '@_@');
    if ($_SESSION['TotalCdx'] > 0) {
        // ajouter la demande :
        $data = array(
            'id_pros' => $_SESSION['PbClient'],
            'id_demandeur' => $_SESSION['delegue'],
            'pointage' => 1,
            'date_pointage'=>date('Y-m-d'),
            'point_bonus' => $_SESSION['TotPoint'],//rest !!!
            'rest_point' => $_SESSION['TotPoint'] - $_SESSION['TotalCdx'],
            'isCart' => $_SESSION['cdxSansPB'],
            'observation_client' => $_SESSION['Obs'],
            'date_remise_point' => date('Y-m-d'),
            'cree_par' => $_SESSION['user']['id'],
            'ponitsByType' => $pointByType,
            'famille' => 10

        );
        $idDemande = add($data, 'grm_demande_cadeaux');
        $chaine=$_SESSION['PbClient'].' '.$_SESSION['delegue'].' 1 '.date('Y-m-d').' '.$_SESSION['TotPoint'].' '
            .$_SESSION['TotPoint'].' '.$_SESSION['TotalCdx'].' '.$_SESSION['cdxSansPB'].' '.$_SESSION['Obs'].' '
            .date('Y-m-d').' '.$_SESSION['user']['id'].' '.$pointByType.' 10';
        $hash=password_hash(strtolower("hhh"), PASSWORD_BCRYPT, $options);
        $data22 = array(
            'id_demande' =>$idDemande,
            'hash' => $hash,


        );
        $idhash= add($data22, 'bs');


        foreach ($_SESSION['ProdPbCmd'] as $key => $value):
            $dataProd = array(
                'id_demande' => $idDemande,
                'id_cadeaux' => $key,
                'qte' => $value,
                'type_cdx' => 1,
            );
            add($dataProd, 'grm_cadeaux_demander');
        endforeach;
        foreach ($_SESSION['CdxCmd'] as $key => $value):
            $dataProd = array(
                'id_demande' => $idDemande,
                'id_cadeaux' => $key,
                'qte' => $value,
                'type_cdx' => 2,
            );
            add($dataProd, 'grm_cadeaux_demander');
        endforeach;
        $idRemise = $_SESSION['user']['id'];
    }
}
if($_SESSION['TotalCdx']>0){
    $cadeauxDmd=get('*','grm_cadeaux_demander',array('id_demande='=>$idDemande));
   /* foreach ($Pbs['reponse'] as $pb) {
        if($_SESSION['Point'.$pb['id']]) {
            $points[]=$_SESSION['Point'.$pb['id']];
        }
    }
    if(count($points)>0){
        $pointByType=implode($points,'@_@');
    } else {
        $pointByType=getinfo($idDemande,'grm_demande_cadeaux','ponitsByType');
    }*/
    $data=array(
        'point_bonus_reel'=>$_SESSION['TotPoint'],
        'rest_point'=>$_SESSION['TotPoint']-$_SESSION['TotalCdx'],
        'id_remise'=>$idRemise,
        'oberservation_admin'=>$ObsAdm,
        'date_validation'=>date('Y-m-d'),
        'modifier_par'=>$_SESSION['user']['id'],
        'pointsRealByType'=>$pointByType,
        'famille'=>10
    );
if(!$etat){
    $etat=0;
}
    $data['etat']=$etat;

    update($idDemande,$data,'grm_demande_cadeaux');
    echo '<pre>';print_r($_SESSION['CdxCmd']);exit;
    foreach ($cadeauxDmd['reponse'] as $cdx) {
        delete($cdx['id'],'grm_cadeaux_demander');
    }
    //echo 'ok22';exit;
    foreach ($_SESSION['ProdPbCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idDemande,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>1,
        );
        add($dataProd,'grm_cadeaux_demander');
        //demiussion produits  :
        if($etat==4){
            $Gcc->DimStockProduits($key,$value);
        }
    endforeach;
    foreach($_SESSION['CdxCmd'] as $key=>$value):
        $dataProd=array(
            'id_demande'=>$idDemande,
            'id_cadeaux'=>$key,
            'qte'=>$value,
            'type_cdx'=>2,
        );
        add($dataProd,'grm_cadeaux_demander');
        //deminisussion cadeaux git
        if($etat==4){
            //$Gcc->DimStock($key,$value);

            if($key==63 || $key==54||  $key==53 ||  $key==52 || $key==1054) {
                $sect=getinfo($_SESSION['PbClient'],'prospect','gouvernorat');
                $idbon = getinfoByIdv3('id', 'stockbon', ' idbn=' . $key . ' and idsect=' .$sect);

                $qtebn = getinfoByIdv3('qte', 'stockbon', ' idbn=' . $key . ' and idsect=' .$sect);

                //$qtebn=getinfo($id,'stockbon','qte');
                $new = filter_input(0, 'Newqte', 257);
                $aj = $qtebn-$value;
                update(
                    $idbon, array('qte' => $aj), 'stockbon'
                );

            }
        }
    endforeach;
    //echo 'ok22';exit;
   // $pointsBonus->viderSession();
    //echo $idDemande;
   // echo $_SESSION['lastP'];
   // redirect('../'.$_SESSION['lastP']);
   // echo "../gestionDesDemandes/Liste&idDel=&d=30";
}

