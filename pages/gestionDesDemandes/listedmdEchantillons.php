<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 02/01/18
 * Time: 15:50
 */
$iddeleg=filter_input(INPUT_POST,'delegue',FILTER_VALIDATE_INT);
if($iddeleg) {
    $idDmd=filter_input(INPUT_POST,'idDmd',FILTER_VALIDATE_INT);
    $demande =array();
    $demande=$demande['reponse'][0];
    $demande['etat']=0;
    $demande['par']=$iddeleg;
    $demande['created_by']=1001;
    $demande['pour']=date('Y-m-d');
    $newDmd=add($demande,'echant_demander');
    $cdx=get(array('id_prod','qte'),'echant_prod',array('id_echant='=>$idDmd));
    $cdx=$cdx['reponse'];
    foreach ($cdx as $cd) {
        $cd['id_echant']=$newDmd;
        add($cd,'echant_prod');
    }
    redirect('listedmdEchantillons');
}
$idDemandeau=filter_input(INPUT_GET,'idDel',257);
$link="&idDel=$idDemandeau";
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$idDemLivraison=filter_input(INPUT_GET,'idDemLivraison',257);
$annuler=filter_input(INPUT_GET,'annuler',257);
if($idDemLivraison){
    update($idDemLivraison,array(
        'date_livraison'=>date("Y-m-d"),
        'etat'=>2
    ) ,'echant_demander');
}
if($annuler){
    update($annuler,array(
        'valider_par'=>$_SESSION['user']['id'],
        'date_validation'=>date("Y-m-d"),
        'etat'=>-1,
    ) ,'echant_demander');
}
if($idDemandeau) {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'echant_demander',array('par='=>$idDemandeau),'AND',array('id'=>'DESC'),array($Limite,30));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'echant_demander',NULL,'AND',array('id'=>'DESC'),array($Limite,30));
}
//echo '<pre>';print_r($Cadeaux);die;
unset($_SESSION['EchantCmd']);
unset($_SESSION['TotalEchant']);$_SESSION['TotalEchant']=0;
?>
<section class="content-header">
    <h1 class="pull-left">Demandes d'échantillons</h1>
    <a href="<?=WEBRoot?>/demande/echantiants" class="btn btn-primary pull-right">
        Ajouter
    </a>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="form-group">
                    <label>Demander par : </label>
                    <select class="form-control" name="id_demandeur" onchange="GetPage('listedmdEchantillons')" id="TypeClient" >
                        <option value="">Par utilisateur</option>
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
                        <th>Par</th>
                        <th>Etat demande</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($Cadeaux['reponse'] as $cdt): ?>
                        <tr>
                            <td><?=$cdt['id']. '/' . date("Y", strtotime($cdt['sysDate']))?></td>
                            <td><?=$cdt['sysDate'];?></td>
                            <td>
                                <?php if($cdt['par']==2):
                                    echo getinfo(63,'users' ,'Nom').' '.getinfo(63,'users' ,'prenom');
                                else:
                                    echo getinfo($cdt['par'],'users' ,'Nom').' '.getinfo($cdt['par'],'users' ,'prenom');
                                endif;
                                ?></td>
                            <td><?
                                if($cdt['etat']==0){
                                    echo "En cours de traitement";
                                }elseif($cdt['etat']==1){
                                    echo "Valider";
                                }elseif($cdt['etat']==-1){
                                    echo "Annuler";
                                }else{
                                    echo "Livrer le " .$cdt['date_livraison'];
                                }
                                ?></td>
                            <td>
                                <ul>
                                    <? $ListeCadeaux=get("*",'echant_prod',array('	id_echant='=>$cdt['id']));
                                    for($i=0;$i<3;$i++):
                                        if($ListeCadeaux['total']<=$i) break;
                                        ?>
                                        <li>
                                            <?= $ListeCadeaux['reponse'][$i]['qte']?> <?=getinfo($ListeCadeaux['reponse'][$i]['id_prod'],'products' ,'name') ?>
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
                                <? if($cdt['etat']==0) :?>
                                    <a href="validationDmdEchant&idDemande=<?=$cdt['id']?>&edit=1" class="btn btn-success" data-toggle="tooltip" title="Valider">
                                        <i class="fa fa-check"></i>
                                    </a>
                                    <a href="listedmdEchantillons<?=$link?>&annuler=<?=$cdt['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Annuler">
                                        <i class="fa fa-times"></i>
                                    </a>
                                <?elseif($cdt['etat']==-1):?>
                                <?else:?>
                                    <?if($cdt['date_livraison']==NULL):?>
                                        <a href="listedmdEchantillons<?=$link?>&idDemLivraison=<?=$cdt['id']?>" class="btn btn-instagram" data-toggle="tooltip" title="Livraison">
                                            <i class="fa fa-train"></i>
                                        </a>
                                    <?endif;?>
                                    <a href="printDocEchant&idDemande=<?=$cdt['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Imprimer">
                                        <i class="fa fa-print"></i>
                                    </a>
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

                <? pagination($Cadeaux['total'], 30, WEBRoot . "/gestionDesDemandes/listedmdEchantillons".$link."&d=", ""); ?>

            </div>

        </div>

    </div>
</section>