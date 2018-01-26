<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 11:22
 */
$idDemandeur=filter_input(INPUT_GET,'idDel',257);
$link="&idDel=$idDemandeur";
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$idDemLivraison=filter_input(INPUT_GET,'idDemLivraison',257);
$annuler=filter_input(INPUT_GET,'annuler',257);
if($idDemLivraison){
    update($idDemLivraison,array(
        'date_livraison'=>date("Y-m-d"),
        'etat'=>2
    ) ,'materiel_deleg');
}
if($annuler){
    update($annuler,array(
        'date_validation'=>date("Y-m-d"),
        'id_validateur'=>$_SESSION['user']['id'],
        'etat'=>-1
    ) ,'materiel_deleg');
}
if($idDemandeur) {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'materiel_deleg',array('id_deleg='=>$idDemandeur),'AND',array('id'=>'DESC'),array($Limite,30));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'materiel_deleg',NULL,'AND',array('id'=>'DESC'),array($Limite,30));
}
//echo '<pre>';print_r($Cadeaux);die;
?>
<section class="content-header">
    <h1 class="pull-left">Liste demandes de matériel délégué</h1>
    <a href="<?=WEBRoot?>/demande/materielsDelegues" class="btn btn-primary pull-right">
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
                    <select class="form-control" name="id_demandeur" onchange="GetPage('listeDmdMaterelDeleg')" id="TypeClient" >
                        <option value="">Par utilisateur</option>
                        <?
                        $ListeUser = get('*', 'users',array('active>'=>0));
                        foreach ($ListeUser['reponse'] as $user):
                            ?>
                            <option value="<?= $user['id'] ?>" <? if ($user['id'] == $idDemandeur) {
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
                        <th>Cadeaux demander</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($Cadeaux['reponse'] as $cdt): ?>
                        <tr>
                            <td><?=$cdt['id']. '/' . date("Y", strtotime($cdt['date_dmd']))?></td>
                            <td><?=$cdt['sysDate'];?></td>
                            <td><?=
                                getinfo($cdt['id_deleg'],'users' ,'Nom').' '.getinfo($cdt['id_deleg'],'users' ,'prenom')
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
                                    <? $ListeCadeaux=get("*",'materiel_deleg_details',array('id_dmd='=>$cdt['id']));
                                    for($i=0;$i<3;$i++):
                                        if($ListeCadeaux['total']<=$i) break;?>
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
                                <? if($cdt['etat']==0) :?>
                                    <a href="validationDmdMatDeleg&idDemande=<?=$cdt['id']?>&edit=1" class="btn btn-success" data-toggle="tooltip" title="Valider">
                                        <i class="fa fa-check"></i>
                                    </a>
                                    <a href="listeDmdMaterelDeleg<?=$link?>&annuler=<?=$cdt['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Annuler">
                                        <i class="fa fa-times"></i>
                                    </a>
                                <?elseif($cdt['etat']==-1):?>
                                <?else:?>
                                    <?if($cdt['date_livraison']==NULL):?>
                                        <a href="listeDmdMaterelDeleg<?=$link?>&idDemLivraison=<?=$cdt['id']?>" class="btn btn-instagram" data-toggle="tooltip" title="Livraison">
                                            <i class="fa fa-train"></i>
                                        </a>
                                    <?endif;?>
                                    <a href="printDocMatDeleg&idDemande=<?=$cdt['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Imprimer">
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

                <? pagination($Cadeaux['total'], 30, WEBRoot . "/gift/Liste".$link."&d=", ""); ?>

            </div>

        </div>

    </div>
</section>