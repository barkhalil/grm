<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 10/11/2016
 * Time: 14:17
 */


$de = filter_input(INPUT_POST, 'de');
$a = filter_input(INPUT_POST, 'a');
$idDemandeau=filter_input(INPUT_POST,'id_demandeur',257);
if(!$de) $de=date('Y-m-d',strtotime('-1 month',strtotime(date('Y-m-d'))));
if(!$a) $a=date('Y-m-d');

$idPros=filter_input(INPUT_POST,'idPros',FILTER_VALIDATE_INT);



$_SESSION['debut']=$de;
$_SESSION['au']=$a;
$_SESSION['idel']=$idDemandeau;

if(filter_input(INPUT_POST, 'Search')){
if($idDemandeau) {
    //if($idDemandeau ==2 )
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('id_demandeur='=>$idDemandeau,'point_bonus>'=>0,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('famille='=>10,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
}
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
                <div class="form-inline" style="width: 100%;padding-bottom: 3%;">
                    <form class="form" method="POST" action="#">
                    <label>Demande pour : </label>
                    <select class="form-control" name="id_demandeur"  id="TypeClient" >
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

                            <label class="lblBlock" for="de"> De </label>
                            <input type="date" name="de" value="<?=$de?>" id="de" class="form-control" required/>

                            <label class="lblBlock" for="a"> A </label>
                            <input type="date" name="a" value="<?=$a?>" id="a" class="form-control" required/>

                            <button type="submit" value="1" name="Search" class="btn btn-primary" >Rechercher</button>
                    </form>
                        <br>

                </div>
               <?php if($Cadeaux){?>
                   <div style="padding-bottom: 4%;">
                   <button type="button" name="create_excel" id="create_excel" class="btn btn-success pull-right" onclick="excel()">Vers excel</button>
                   </div>
                   <table class="table table-bordered sameline-btns" id="listeCadeauTab" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date remise</th>
                        <th>Point remis</th>
                        <th>Délégué</th>
                        <th>Pour</th>
                        <th>Etat demande</th>
                        <th>Cadeaux demandés</th>
                        <th>Type</th>
                        <th>Suivi</th>
                        <th>Creer Par</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($Cadeaux['reponse'] as $cdt): ?>
                        <?php
                        // Délégué
                        if($cdt['id_demandeur']==2){
                            $delegue = getinfo(63,'users','Nom').' '.getinfo(63,'users','prenom');
                        } else {
                            $delegue = getinfo($cdt['id_demandeur'],'users','Nom').' '.getinfo($cdt['id_demandeur'],'users','prenom');
                        }

                        // Etat
                        if($cdt['etat']==0){
                            $etat = "En cours de traitement";
                        }elseif($cdt['etat']==1){
                            $etat = "Pointer";
                        }elseif($cdt['etat']==-1){
                            $etat = "Refusée";
                        }elseif($cdt['etat']==-2){
                            $etat = "Annulée après validation";
                        }elseif($cdt['etat']==2){
                            $etat = "Points insuffisant, reste = ".$cdt['rest_point'];
                        }elseif($cdt['etat']==4){
                            $etat = "Validé avec reste = ".$cdt['rest_point'];
                        }else{
                            $etat = "Livré le ".$cdt['date_livraison'];
                        }

                        // Cadeaux — tous les items en texte pur séparés par " | "
                        $ListeCadeaux = get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
                        $cadeauxItems = array();
                        foreach($ListeCadeaux['reponse'] as $item){
                            if($item['type_cdx']==1){
                                $nom = getinfo($item['id_cadeaux'],'products','name');
                            } else {
                                $nom = getinfo($item['id_cadeaux'],'grm_gift','titre');
                            }
                            $cadeauxItems[] = $item['qte'].' x '.$nom;
                        }
                        $cadeauxTexte = implode(' | ', $cadeauxItems);

                        // Suivi
                        $suiviTexte = ($cdt['suivi']==0) ? "En cours" : "Validé";
                        ?>
                        <tr>
                            <td><?=$cdt['id'].'/'.date("Y", strtotime($cdt['system_date']))?></td>
                            <td><?=$cdt['date_remise_point']?></td>
                            <td><?=$cdt['point_bonus']?></td>
                            <td><?=$delegue?></td>
                            <td><?=getinfo($cdt['id_pros'],'prospect','Nom').' '.getinfo($cdt['id_pros'],'prospect','prenom')?></td>
                            <td><?=$etat?></td>
                            <td><?=htmlspecialchars($cadeauxTexte)?></td>
                            <td><?=($cdt['isCart']==0) ? "BA" : "Carte"?></td>
                             <td><?=getinfo($cdt['cree_par'],'grm_users','Nom').' '.getinfo($cdt['cree_par'],'grm_users','prenom')?></td>
                           
                           <!-------- <td>
                                <?php if($cdt['suivi']==0 && $_SESSION['user']['id']==9): ?>
                                <button type="button" class="btn btn-info btn-action-only"
                                        onclick="SuiviCadeau(<?=$cdt['id']?>)"
                                        data-toggle="tooltip" title="Valider"
                                        data-excel="En cours">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <?php else: ?>
                                <?=$suiviTexte?>
                                <?php endif; ?>------>
                            </td>
                        </tr>
                    <?endforeach;?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>

</section>
