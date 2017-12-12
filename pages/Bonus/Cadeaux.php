<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 10/11/2016
 * Time: 14:17
 */
// récupération des cadeaux demander :
$Cadeaux=get("*",'grm_demande_cadeaux',array('id_demandeur='=>$_SESSION['user']['id']),'AND',array('id'=>'DESC'));


$_SESSION['Point']=0;
$_SESSION['TotPoint']=0;
$_SESSION['TotalCdx']=0;
unset($_SESSION['TotalCdx']);
unset($_SESSION['ProdPbCmd']);
unset($_SESSION['CdxCmd']);

?>
<section class="content-header">
    <h1> Liste demande cadeaux pour les prospects</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
<div class="col-md-12">
    <div class="box box-success box-body table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>date demande</th>
                <th>Par: </th>
                <th>Pour : </th>
                <th>Etat demande</th>
                <th>Cadeaux / articles demander</th>
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
                    <td><?=
                        getinfo($cdt['id_pros'],'prospect' ,'Nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom')

                        ?></td>
                    <td><?
                        if($cdt['etat']==0){
                            echo "En cours de traitement";
                        }elseif($cdt['etat']==-1){
                            echo "Réfuser";
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
                    foreach ($ListeCadeaux['reponse'] as $prod):
                    ?>
                        <li>
                            <?= $prod['qte']?> pour <?= getinfo($prod['id_cadeaux'],'grm_gift' ,'titre') ?>
                        </li>

                        <?endforeach;?>
                        </ul>
                    </td>
                </tr>
            <?endforeach;?>
            </tbody>
        </table>
    </div>
</div>
        </div>
</section>
