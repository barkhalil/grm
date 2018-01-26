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
    $Cadeaux=get("*",'grm_demande_cadeaux',array('id_demandeur='=>$idDemandeau,'famille='=>4),'AND',array('id'=>'DESC'),array($Limite,30));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('famille='=>4),'AND',array('id'=>'DESC'),array($Limite,30));
}
//echo '<pre>';print_r($Cadeaux);die;
$_SESSION['Point']=0;
$_SESSION['TotPoint']=0;
$_SESSION['TotalCdx']=0;
unset($_SESSION['TotalCdx']);
unset($_SESSION['ProdPbCmd']);
unset($_SESSION['CdxCmd']);

?>
<section class="content-header">
    <h1>Liste demandes ordonnancier</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="form-group">
                    <label>Demander par : </label>
                    <select class="form-control" name="id_demandeur" onchange="GetPage('listeDemandeOrdonnancier')" id="TypeClient" >
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
                            <td><?=
                                getinfo($cdt['id_demandeur'],'users' ,'Nom').' '.getinfo($cdt['id_demandeur'],'users' ,'prenom')
                                ?></td>
                            <td><?=getinfo($cdt['id_pros'],'prospect' ,'Nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom')?></td>
                            <td><?
                                if($cdt['etat']==0){
                                    echo "En cours de traitement";
                                }elseif($cdt['etat']==-1){
                                    echo "Refusée";
                                }elseif($cdt['etat']==1){
                                    echo "Pointer";
                                }elseif($cdt['etat']==2){
                                    echo "Points insufissant, avec reste =  ".$cdt['rest_point'];
                                }else{
                                    echo "Valider";
                                }
                                ?></td>
                            <td>
                                <ul>


                                    <?
                                    $ListeCadeaux=get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
                                    for($i=0;$i<3;$i++):
                                        if($ListeCadeaux['total']<=$i) break;
                                        ?>
                                        <li>
                                            <?= $ListeCadeaux['reponse'][$i]['qte']?> pour <?= getinfo($ListeCadeaux['reponse'][$i]['id_cadeaux'],'grm_gift' ,'titre') ?>
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
                                <? if($cdt['etat']>=0): ?>
                                    <?if($cdt['etat']<4):?>
                                        <a href="listeDemandeVitrine<?=$link?>&annuler=<?=$cdt['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Annuler">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    <?endif;?>
                                    <? if(!$cdt['pointage']): ?>
                                        <a href="listeDemandeOrdonnancier<?=$link?>&pointage=<?=$cdt['id']?>" class="btn btn-google" data-toggle="tooltip" title="Pointer">
                                            <i class="fa fa-calculator"></i>
                                        </a>
                                    <?else: if($cdt['etat']<2) :?>
                                        <a href="ValidateDemande&idDemande=<?=$cdt['id']?>&edit=1" class="btn btn-success" data-toggle="tooltip" title="Valider">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    <?else:?>
                                        <?if($cdt['date_livraison']==""):?>
                                            <a href="listeDemandeOrdonnancier<?=$link?>&idDemLivraison=<?=$cdt['id']?>" class="btn btn-instagram" data-toggle="tooltip" title="Livraison">
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
</section>
