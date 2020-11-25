<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 10/11/2016
 * Time: 11:35
 */
$id=filter_input(1,'id',257);
if(!$id){
    $_SESSION['msg'] = "Une Erreur c'est produite";
    $_SESSION['type'] = "alert-danger";
    redirect('ListeCadeaux');
}
$Cadeaux=get("*",'grm_gift',array('id='=>$id));
$CadeauxDetails=$Cadeaux['reponse'][0]; 
?>
<section class="content-header">
    <a href="#" class="btn bg-maroon pull-right" onclick="history.go(-1);"> <i class="glyphicon glyphicon-backward"></i> Retour </a>
    <h1> Produit :  <?=$CadeauxDetails['titre']?></h1>

</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <div class="box box-success box-body">
            <div class="col-md-6">
                <h3> Produit :  <?=$CadeauxDetails['titre']?></h3>
                <p> Description : <br/>
                    <?=$CadeauxDetails['description']?>
                </p>
                <ul>
                    <li>Point Bonus :  <?=$CadeauxDetails['point_bonus']?></li>
                    <li>Disponible :  <?=$CadeauxDetails['dispo']==1 ? "Oui" : "Non"?></li>
                    <li>Quantité :  <?=$CadeauxDetails['qte']?></li>
                    <li>Quantité utiliser :  <?=$CadeauxDetails['qte_utiliser']?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3> Information</h3>
                <ul>
                    <li>Code  : <?=$CadeauxDetails['bare_code']?></li>

                    <li>Stock Alert : <?=$CadeauxDetails['stoc_alert']?></li>
                </ul>
            </div>
                <div class="col-md-6">
                    <h3> Versions : <span><a href="version?prod=<?=$id?>"  class="btn btn-success" data-toggle="tooltip" title="Modifier"><i class="fa fa-plus-circle"></i></a></span>
                    </h3>
                    <table  class="table table-bordered">
                        <tr>
                            <td>nom</td>
                            <td>date creation</td>
                            <td>dernière mise à jour  du stock</td>
                            <td>Qte entrée</td>
                            <td>Qte restante</td>
                            <td>Qte utilisé</td>

                        </tr>
                        <?
                        $ListeGift = get('*', 'grm_art_version',array(
                            //  'dispo =' => 1,

                            'id_art = '=>$id
                        ));

                        foreach ($ListeGift['reponse'] as $Gift):
                            ?>

                            <tr>


                                <td><?=$Gift['version']?></td>
                                <td><?

                                   echo getinfoByIdv3('created_at','grm_art_version',' id_art='.$id.' and version="'.$Gift['version'].'"');
                                    ?></td><td><?

                                   echo getinfoByIdv4('MIN(system_date)','nb','grm_stock',' prod='.$id.' and version="'.$Gift['id'].'"');
                                    ?></td>
                                <td>
                                    <?
                                   $qteent=SUM('grm_stock','qte',' version='.$Gift['id'].' and prod='.$id);
                                   if($qteent){
                                       echo $qteent;
                                   }else{
                                       $qteent=0;
                                       echo $qteent;
                                   }

                                    ?>

                                </td>
                                <td><?=$Gift['qte']?></td>
                                <td><?=$qteent-$Gift['qte']?></td>

                            </tr>



                        <? endforeach; ?>


                    </table>





                </div>

            </div>
            </div>
        </div>
    </section>
