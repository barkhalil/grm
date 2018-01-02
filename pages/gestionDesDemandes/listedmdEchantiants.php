<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 02/01/18
 * Time: 15:50
 */
//ALTER TABLE `echant_demander` ADD `date_livraison` DATE NULL DEFAULT NULL AFTER `sysDate`;
//ALTER TABLE `echant_demander` ADD `observation_admin` VARCHAR(255) NULL DEFAULT NULL AFTER `etat`;
$idDemandeau=filter_input(INPUT_GET,'idDel',257);
$link="&idDel=$idDemandeau";
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$idDemLivraison=filter_input(INPUT_GET,'idDemLivraison',257);
if($idDemLivraison){
    update($idDemLivraison,array(
        'date_livraison'=>date("Y-m-d"),
        'etat'=>2
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
?>
<section class="content-header">
    <h1>Liste demandes d'échantillant</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="form-group">
                    <label>Demander par : </label>
                    <select class="form-control" name="id_demandeur" onchange="GetPage('dmdPromotionnel')" id="TypeClient" >
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
                        <th>Cadeaux demander</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($Cadeaux['reponse'] as $cdt): ?>
                        <tr>
                            <td><?=$cdt['id']. '/' . date("Y", strtotime($cdt['sysDate']))?></td>
                            <td><?=$cdt['sysDate'];?></td>
                            <td><?=
                                getinfo($cdt['par'],'users' ,'Nom').' '.getinfo($cdt['par'],'users' ,'prenom')
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
                                    foreach ($ListeCadeaux['reponse'] as $prod):
                                        ?>
                                        <li>
                                            <?= $prod['qte']?> <?= getinfo($prod['id_echant'],'grm_gift' ,'titre') ?>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            </td>
                            <td>
                                <? if($cdt['etat']==0) :?>
                                    <a href="validationDmdEchant&idDemande=<?=$cdt['id']?>&edit=1" class="btn btn-success" data-toggle="tooltip" title="Valider">
                                        <i class="fa fa-check"></i>
                                    </a>
                                <?elseif($cdt['etat']==-1):?>
                                <?else:?>
                                    <?if($cdt['date_livraison']==NULL):?>
                                        <a href="listedmdEchantiants<?=$link?>&idDemLivraison=<?=$cdt['id']?>" class="btn btn-instagram" data-toggle="tooltip" title="Livraison">
                                            <i class="fa fa-train"></i>
                                        </a>
                                    <?endif;?>
                                    <a href="printDoc&idDemande=<?=$cdt['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Imprimer">
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