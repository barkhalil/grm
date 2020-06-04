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
                        <th>date remise</th>
                        <th>Point remis</th>
                        <th>Délégué</th>
                        <th>Pour : </th>
                        <th>Etat demande</th>
                        <th>Cadeaux demander</th>
                        <th>Type </th>
                        <th>Suivi</th>

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
                                ?>
                            </td>
                            <td><?=getinfo($cdt['id_pros'],'prospect' ,'Nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom')?></td>
                            <td><?
                                if($cdt['etat']==0){
                                    echo "En cours de traitement";
                                }elseif($cdt['etat']==1){
                                    echo "Pointer";
                                }elseif($cdt['etat']==-1){
                                    echo "Refusée";
                                }elseif($cdt['etat']==-2){
                                    echo "Annulée aprés validation";
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
                            <td><?
                                if($cdt['isCart']==0){
                                echo "BA";
                                }else{
                                echo "Carte";
                                }
                                ?>
                            </td>
                            <td>
                                <div name="div_<?=$cdt['id']?>" id="div_<?=$cdt['id']?>" >
                                    <?php
                                    if($cdt['suivi']==0){
                                        if($_SESSION['user']['id']==9) {
                                            ?>

                                            <button type="button" class="btn btn-info"
                                                    onclick="SuiviCadeau(<?= $cdt['id'] ?>)" data-toggle="tooltip"
                                                    title="valider" id="">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </button>

                                            <?php
                                        }
                                    }
                                    else {
                                        echo "valider";
                                    }


                                    ?>
                                </div>
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
