<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 10/11/2016
 * Time: 14:17
 */
$idPros=filter_input(INPUT_POST,'idPros',FILTER_VALIDATE_INT);
if($idPros) {
    $idDmd=filter_input(INPUT_POST,'idDmd',FILTER_VALIDATE_INT);
    $prospect=get('*','prospect',array('id='=>$idPros));
    //echo '<pre>';print_r(get('*','prospect',array('id='=>$idPros)));die;
    if($prospect['total']>0) {
        $cpDmd=array();
        //echo 'ok';die;
        $dmd=get('*','grm_demande_cadeaux',array('id='=>$idDmd));
        $cpDmd['famille']=$dmd['reponse'][0]['famille'];
        $cpDmd['date_remise_point']=date('Y-m-d');
        $cpDmd['etat']=0;
        $cpDmd['id_pros']=$idPros;
        $cpDmd['pointage']=0;
        $cpDmd['id_demandeur']=$_SESSION['user']['id'];
        $cpDmd['cree_par']=$_SESSION['user']['id'];
        $cpDmd['point_bonus']=$dmd['reponse'][0]['point_bonus'];
        $cpDmd['ponitsByType']=$dmd['reponse'][0]['ponitsByType'];
        $cpDmd['rest_point']=$dmd['reponse'][0]['rest_point'];
        $cpDmd['point_bonus']=$dmd['reponse'][0]['point_bonus'];
        /*//echo '<pre>';print_r($dmd);die;
        $demande=$dmd['reponse'][0];
        $demande['etat']=0;
        $demande['pointage']=0;
        unset($demande['id']);
        $demande['id_pros']=$idPros;*/
        $idNewDmd=add($cpDmd,'grm_demande_cadeaux');
        $listeCdx=get("*",'grm_cadeaux_demander',array('id_demande='=>$idDmd));
        foreach ($listeCdx['reponse'] as $cdx) {
            $cdx['id_demande']=$idNewDmd;
            unset($cdx['id']);
            add($cdx, 'grm_cadeaux_demander');
        }
    } else {
        $_SESSION['msg']='Prospect inexistant';
        $_SESSION['type']="alert-warning";
    }
    redirect('Liste');
}
$idDemandeau=filter_input(INPUT_GET,'idDel',257);
$link="&idDel=$idDemandeau";
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$pointage=filter_input(1,'pointage',257);
$annuler=filter_input(1,'annuler',257);
if($pointage){
    update($pointage,array(
        'pointage'=>1,
        'date_pointage'=>date("Y-m-d"),
        'etat'=>1,
        'modifier_par'=>$_SESSION['user']['id']
    ) ,'grm_demande_cadeaux');
}
if($annuler){
    update($annuler,array(
        'etat'=>-1,
        'modifier_par'=>$_SESSION['user']['id'],
        'date_validation'=>date("Y-m-d"),
    ) ,'grm_demande_cadeaux');
}
$idDemLivraison=filter_input(INPUT_GET,'idDemLivraison',257);
if($idDemLivraison){
    update($idDemLivraison,array(
        'date_livraison'=>date("Y-m-d"),
        'etat'=>5,
        'modifier_par'=>$_SESSION['user']['id']
    ) ,'grm_demande_cadeaux');
}
if($idDemandeau) {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('id_demandeur='=>$idDemandeau,'point_bonus>'=>0),'AND',array('id'=>'DESC'),array($Limite,30));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('famille='=>10),'AND',array('id'=>'DESC'),array($Limite,30));
}
//echo '<pre>';print_r($Cadeaux);die;
?>
<section class="content-header">
    <h1> Liste demande cadeaux</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="form-group">
                    <label>Demander par : </label>
                    <select class="form-control" name="id_demandeur" onchange="GetPage('Liste')" id="TypeClient" >
                        <option value=""> Par utilisateur</option>
                        <?
                        $ListeUser = get('*', 'users',array('active>'=>0));
                        foreach ($ListeUser['reponse'] as $user):
                            ?>
                            <option value="<?= $user['id'] ?>" <? if ($user['id'] == $idDemandeau) {
                                echo "selected=selected";
                            } ?>><?= $user['Nom'] . ' ' . $user['Prenom'] ?></option>
                        <? endforeach; ?>

                    </select>
                </div>
                <table class="table table-bordered sameline-btns">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>date remise</th>
                        <th>Point remis</th>
                        <th>Demandeur</th>
                        <th>Pour : </th>
                        <th>Etat demande</th>
                        <th>Cadeaux demander</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($Cadeaux['reponse'] as $cdt): ?>
                        <tr>
                            <td><?=$cdt['id']. '/' . date("Y", strtotime($cdt['system_date']))?></td>
                            <td><?=$cdt['date_remise_point']?></td>
                            <td><?=$cdt['point_bonus']?></td>
                            <td><?php if($cdt['id_demandeur']==2):
                                    echo getinfo(63,'users' ,'Nom').' '.getinfo(63,'users' ,'prenom');
                                else:
                                    echo getinfo($cdt['id_demandeur'],'users' ,'Nom').' '.getinfo($cdt['id_demandeur'],'users' ,'prenom');
                                endif;
                                ?></td>
                            <td><?=getinfo($cdt['id_pros'],'prospect' ,'Nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom')?></td>
                            <td><?
                                if($cdt['etat']==0){
                                    echo "En cours de traitement";
                                }elseif($cdt['etat']==1){
                                    echo "Pointer";
                                }elseif($cdt['etat']==-1){
                                    echo "Refusée";
                                }elseif($cdt['etat']==2){
                                    echo "Points insufissant, avec reste =  ".$cdt['rest_point'];
                                }elseif($cdt['etat']==4){
                                    echo "Valider avec reste = ".$cdt['rest_point'];
                                }else{
                                    echo "Livrer le " .$cdt['date_livraison'];
                                }
                                ?></td>
                            <td>
                                <ul class="small-padding">
                                    <? $ListeCadeaux=get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
                                    for($i=0;$i<3;$i++):
                                        if($ListeCadeaux['total']<=$i) break;?>
                                        <li>
                                            <?= $ListeCadeaux['reponse'][$i]['qte']?> pour
                                            <? if($ListeCadeaux['reponse'][$i]['type_cdx']==1){
                                             echo   getinfo($ListeCadeaux['reponse'][$i]['id_cadeaux'],'products' ,'name');
                                            }else{
                                              echo  getinfo($ListeCadeaux['reponse'][$i]['id_cadeaux'],'grm_gift' ,'titre') ;
                                            }?>
                                        </li>
                                    <?endfor;?>
                                    <?if($ListeCadeaux['total']>3):?>
                                        <br/>...
                                    <?endif;?>
                                </ul>
                            </td>
                            <td>
                                <? if($cdt['etat']>0 || $cdt['etat']==-1):?>
                                    <form method="post" id="dupliquerDmd" action="#" style="display: inline-block;">
                                        <input onkeyup="this.value = this.value.replace(/\D/g,'')" type="text" name="idPros" placeholder="ID PROS" class="full-height">
                                        <input type="hidden" name="idDmd" value="<?=$cdt['id'];?>">
                                        <button type="submit" class="btn btn-info" data-toggle="tooltip" title="Dupliquer" id="dupliquer">
                                            <i class="fa fa-files-o" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                <?endif;?>
                                <? if($cdt['etat']>=0): ?>
                                    <?if($cdt['etat']<4):?>
                                        <a href="Liste<?=$link?>&annuler=<?=$cdt['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Annuler">
                                             <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    <?endif;?>
                                    <? if(!$cdt['pointage']): ?>
                                    <a href="Liste<?=$link?>&pointage=<?=$cdt['id']?>" class="btn btn-google" data-toggle="tooltip" title="Pointer">
                                        <i class="fa fa-calculator"></i>
                                    </a>
                                    <?else: if($cdt['etat']<2) :?>
                                        <a href="DmdPb&idDemande=<?=$cdt['id']?>&edit=1" class="btn btn-success" data-toggle="tooltip" title="Valider">
                                            <i class="fa fa-check"></i>
                                        </a>
                                        <?else:?>
                                        <?if($cdt['date_livraison']==""):?>
                                        <a href="Liste<?=$link?>&idDemLivraison=<?=$cdt['id']?>" class="btn btn-instagram" data-toggle="tooltip" title="Livraison">
                                            <i class="fa fa-train"></i>
                                        </a>
                                        <?endif;?>
                                        <a href="printDoc&idDemande=<?=$cdt['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Imprimer">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    <?endif;endif?>
                                <?endif;?>
                            </td>
                        </tr>
                    <?endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-5">

            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                Affichage de <?= ($Limite > 1) ? $Limite : 1 ?>
                à <?= ($Limite + 30 < $Cadeaux['total']) ? $Limite + 30 : $Cadeaux['total'] ?>
                de <?= $Cadeaux['total'] ?> Demande cadeaux

            </div>

        </div>

        <div class="col-md-7">

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                <? pagination($Cadeaux['total'], 30, WEBRoot . "/gift/Liste".$link."&d=", ""); ?>

            </div>

        </div>

    </div>
</section>
