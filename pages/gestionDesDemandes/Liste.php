<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 10/11/2016
 * Time: 14:17
 */
$idDemandeau=filter_input(INPUT_GET,'idDel',257);
$link="&idDel=$idDemandeau";
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$pointage=filter_input(1,'pointage',257);
if($pointage){
    update($pointage,array(
        'pointage'=>1,
        'date_pointage'=>date("Y-m-d"),
        'etat'=>1,
        'modifier_par'=>$_SESSION['user']['id']
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
    $Cadeaux=get("*",'grm_demande_cadeaux',array('point_bonus>'=>0),'AND',array('id'=>'DESC'),array($Limite,30));
}

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
                <table class="table table-bordered">
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
                            <td><?=
                                getinfo($cdt['id_demandeur'],'users' ,'Nom').' '.getinfo($cdt['id_demandeur'],'users' ,'prenom')
                            ?></td>
                            <td><?=getinfo($cdt['id_pros'],'prospect' ,'Nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom')?></td>
                            <td><?
                                if($cdt['etat']==0){
                                    echo "En cours de traitement";
                                }elseif($cdt['etat']==1){
                                    echo "Pointer";
                                }elseif($cdt['etat']==2){
                                    echo "Points insufissant, avec reste =  ".$cdt['rest_point'];
                                }elseif($cdt['etat']==3){
                                    echo "Valider avec reste = ".$cdt['rest_point'];
                                }else{
                                    echo "Livrer le " .$cdt['date_livraison'];
                                }
                                ?></td>
                            <td>
                                <ul>
                                    <? $ListeCadeaux=get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
                                    foreach ($ListeCadeaux['reponse'] as $prod):
                                        ?>
                                        <li>
                                            <?= $prod['qte']?> pour <?= getinfo($prod['id_cadeaux'],'grm_gift' ,'titre') ?>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            </td>
                            <td>
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
