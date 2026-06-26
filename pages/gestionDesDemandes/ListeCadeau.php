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
<style>
#listeCadeauTab, #listeCadeauTab th, #listeCadeauTab td {
    border: 1px solid #aaa !important;
    border-collapse: collapse;
}
#listeCadeauTab thead th {
    background-color: #f5f5f5;
    text-align: center;
    vertical-align: middle;
}
#listeCadeauTab td[rowspan] {
    vertical-align: middle;
}
</style>
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
                   <button type="button"  onclick="toexcelv2('cagro','ca par grossite')"
                        class="btn btn-primary pull-right" >Extraction</button>
                     </div>
                      <table class="table table-bordered table-condensed" name="budgq" id="budgq">      <thead>
                    <tr>
                        <th>#</th>
                        <th>Date remise</th>
                        <th>Point remis</th>
                        <th>Délégué</th>
                        <th>Pour</th>
                        <th>Etat demande</th>
                        <th>Cadeaux demandés</th>
                        <th>Type</th>
                       
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

                        // Cadeaux — liste complète avec qté et nom
                        $ListeCadeaux = get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
                        $cadeauxItems = array();
                        foreach($ListeCadeaux['reponse'] as $item){
                            $nom = ($item['type_cdx']==1)
                                ? getinfo($item['id_cadeaux'],'products','name')
                                : getinfo($item['id_cadeaux'],'grm_gift','titre');
                            $cadeauxItems[] = array('qte'=>$item['qte'], 'nom'=>$nom);
                        }
                        $nbCadeaux = max(1, count($cadeauxItems));
                        $creePar   = getinfo($cdt['cree_par'],'grm_users','Nom').' '.getinfo($cdt['cree_par'],'grm_users','prenom');
                        $pour      = getinfo($cdt['id_pros'],'prospect','Nom').' '.getinfo($cdt['id_pros'],'prospect','prenom');
                        ?>
                        <?php foreach($cadeauxItems as $idx => $cadeau): ?>
                        <tr>
                            <?php if($idx === 0): ?>
                            <td rowspan="<?=$nbCadeaux?>"><?=$cdt['id'].'/'.date("Y", strtotime($cdt['system_date']))?></td>
                            <td rowspan="<?=$nbCadeaux?>"><?=$cdt['date_remise_point']?></td>
                            <td rowspan="<?=$nbCadeaux?>"><?=$cdt['point_bonus']?></td>
                            <td rowspan="<?=$nbCadeaux?>"><?=$delegue?></td>
                            <td rowspan="<?=$nbCadeaux?>"><?=$pour?></td>
                            <td rowspan="<?=$nbCadeaux?>"><?=$etat?></td>
                            <?php endif; ?>
                            <td><?=$cadeau['qte']?> x <?=htmlspecialchars($cadeau['nom'])?></td>
                            <?php if($idx === 0): ?>
                            <td rowspan="<?=$nbCadeaux?>"><?=($cdt['isCart']==0) ? "BA" : "Carte"?></td>
                            <td rowspan="<?=$nbCadeaux?>"><?=$creePar?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($cadeauxItems)): ?>
                        <tr>
                            <td><?=$cdt['id'].'/'.date("Y", strtotime($cdt['system_date']))?></td>
                            <td><?=$cdt['date_remise_point']?></td>
                            <td><?=$cdt['point_bonus']?></td>
                            <td><?=$delegue?></td>
                            <td><?=$pour?></td>
                            <td><?=$etat?></td>
                            <td>—</td>
                            <td><?=($cdt['isCart']==0) ? "BA" : "Carte"?></td>
                            <td><?=$creePar?></td>
                        </tr>
                        <?php endif; ?>
                    <?endforeach;?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>

</section>
