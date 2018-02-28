<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 02/01/18
 * Time: 12:42
 */
$cancel=filter_input(1,'cancel',257);
if($cancel) {
    $dmd=$cancelDmds->cancelVitrOrdnDmd($cancel);
    if($dmd) {
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['msg']='Une erreur s\'est produite';
        $_SESSION['type']="alert-warning";
    }

}
$iddeleg=filter_input(INPUT_POST,'delegue',FILTER_VALIDATE_INT);
if($iddeleg) {
    $idDmd=filter_input(INPUT_POST,'idDmd',FILTER_VALIDATE_INT);
    $demande =array();
    $demande=$demande['reponse'][0];
    $demande['etat']=0;
    $demande['par']=$iddeleg;
    $demande['created_by']=1001;
    $newDmd=add($demande,'promo_demander');
    $cdx=get(array('id_prod','qte'),'promo_prod',array('id_promo='=>$idDmd));
    $cdx=$cdx['reponse'];
    foreach ($cdx as $cd) {
        $cd['id_promo']=$newDmd;
        add($cd,'promo_prod');
    }
    redirect('dmdPromotionnel');
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
        'valider_par'=>$_SESSION['user']['id'],
        'date_validation'=>date("Y-m-d"),
    ) ,'promo_demander');
}
$idDemLivraison=filter_input(INPUT_GET,'idDemLivraison',257);
$annuler=filter_input(INPUT_GET,'annuler',FILTER_VALIDATE_INT);
if($idDemLivraison){
    update($idDemLivraison,array(
        'date_livraison'=>date("Y-m-d"),
        'etat'=>2
    ) ,'promo_demander');
}
if($annuler){
    update($annuler,array(
        'date_validation'=>date("Y-m-d"),
        'valider_par'=>$_SESSION['user']['id'],
        'etat'=>-1,
    ) ,'promo_demander');
}
if($idDemandeau) {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'promo_demander',array('par='=>$idDemandeau),'AND',array('id'=>'DESC'),array($Limite,30));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'promo_demander',NULL,'AND',array('id'=>'DESC'),array($Limite,30));
}
//echo '<pre>';print_r($Cadeaux);die;
?>
<section class="content-header">
    <h1 class="pull-left">Liste demandes produits promotionnel</h1>
    <?if($_SESSION['user']['type']<=102):?>
        <a href="<?=WEBRoot?>/demande/materielPromotionnel" class="btn btn-primary pull-right">
            Ajouter
        </a>
    <?endif;?>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="form-group">
                    <label>Demande pour : </label>
                    <select class="form-control" name="id_demandeur" onchange="GetPage('dmdPromotionnel')" id="TypeClient" >
                        <option value=""> Pour utilisateur</option>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date de demande</th>
                            <th>Pour</th>
                            <th>Par</th>
                            <th>Etat demande</th>
                            <th>Cadeaux demander</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <? foreach ($Cadeaux['reponse'] as $cdt): ?>
                        <tr>
                            <td><?=$cdt['id']. '/' . date("Y", strtotime($cdt['sysDate']))?></td>
                            <td><?=$cdt['sysDate'];?></td>
                            <td>
                                <?php
                                echo getinfo($cdt['par'],'users' ,'Nom').' '.getinfo($cdt['par'],'users' ,'prenom');
                                ?>
                            </td>
                            <td>
                                <?php if($cdt['par']!=$cdt['created_by']):
                                    echo getinfo($cdt['created_by'],'grm_users' ,'Nom').' '.getinfo($cdt['created_by'],'grm_users' ,'prenom').' (compte GRM)';
                                else:
                                    echo getinfo($cdt['created_by'],'users' ,'Nom').' '.getinfo($cdt['created_by'],'users' ,'prenom');
                                endif;
                                ?>
                            </td>
                            <td><?
                                if($cdt['etat']==0){
                                    echo "En cours de traitement";
                                }elseif($cdt['etat']==1){
                                    echo "Valider";
                                }elseif($cdt['etat']==-2){
                                    echo "Annulée aprés validation";
                                }elseif($cdt['etat']==-1){
                                    echo "Annuler";
                                }else{
                                    echo "Livrer le " .$cdt['date_livraison'];
                                }
                                ?></td>
                            <td>
                                <ul>
                                    <? $ListeCadeaux=get("*",'promo_prod',array('	id_promo='=>$cdt['id']));
                                    for($i=0;$i<3;$i++):
                                        if($ListeCadeaux['total']<=$i) break;
                                        ?>
                                        <li>
                                            <?= $ListeCadeaux['reponse'][$i]['qte']?> <?= getinfo($ListeCadeaux['reponse'][$i]['id_prod'],'grm_gift' ,'titre') ?>
                                        </li>
                                    <?endfor;?>
                                    <?if($ListeCadeaux['total']>3):?>
                                        <li>
                                            ...
                                        </li>
                                    <?endif;?>
                                </ul>
                            </td>
                            <td>
                                <? if(($cdt['etat']!=0) && ($_SESSION['user']['type']<=102)):?>
                                <form method="post" id="dupliquerDmd" action="#" style="display: inline-block;">
                                    <select name="delegue" class="form-control full-height" required style="display: inline-block" >
                                        <option value=""></option>
                                        <?
                                        $users= get('*','users',array('active>='=>1),'AND');
                                        foreach ($users['reponse'] as $user):?>
                                            <option value="<?=$user['id']?>" <?= ($_SESSION['delegue']==$user['id'])? 'selected':''; ?>><?=$user['Nom'].' '.$user['Prenom'];?></option>
                                        <?endforeach;?>
                                    </select>
                                    <input type="hidden" name="idDmd" value="<?=$cdt['id'];?>">
                                    <button type="submit" class="btn btn-info" data-toggle="tooltip" title="Dupliquer" id="dupliquer">
                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <?endif;?>
                                <? if($cdt['etat']==0 && $_SESSION['user']['type']<=102) :?>
                                    <a href="validationDmdPromt&idDemande=<?=$cdt['id']?>&edit=1" class="btn btn-success" data-toggle="tooltip" title="Valider">
                                        <i class="fa fa-check"></i>
                                    </a>
                                    <a href="dmdPromotionnel<?=$link?>&annuler=<?=$cdt['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Annuler">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                <?elseif($cdt['etat']>=1):?>
                                    <?if($cdt['date_livraison']==NULL):?>
                                        <a href="dmdPromotionnel<?=$link?>&idDemLivraison=<?=$cdt['id']?>" class="btn btn-instagram" data-toggle="tooltip" title="Livraison">
                                            <i class="fa fa-train"></i>
                                        </a>
                                    <?endif;?>
                                    <a href="printDocPromo&idDemande=<?=$cdt['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Imprimer">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <?if($_SESSION['user']['type']<=102):?>
                                        <a href="dmdPromotionnel<?=$link?>&cancel=<?=$cdt['id']?>" class="btn btn-warning cancelDmd" data-toggle="tooltip" title="Annuler" data-confirm="Attention vous ne pouvez pas valider la demande aprés l'annulation. Etes-vous sûr de vouloir annulé cette demande?">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    <?endif;?>
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
                <? pagination($Cadeaux['total'], 30, WEBRoot . "/gestionDesDemandes/dmdPromotionnel".$link."&d=", ""); ?>
            </div>
        </div>

    </div>
</section>