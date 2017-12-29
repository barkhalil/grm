<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 30/10/2016
 * Time: 23:24
 */
$id=filter_input(INPUT_GET,'idPros',FILTER_VALIDATE_INT);
if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect(WEBRoot.'/prospects/listeAdmin');
}
if(filter_input(0,'Add',257)):
// 1er test si ensemble de ponts des produits <= total point
    $Products=filter_input(INPUT_POST,'prodValue',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    $TotalClientsPoints=filter_input(0,'totalPoint',FILTER_DEFAULT);
    $TotPoints=0;
    foreach ($Products as $key => $value):
//key == id
        $point= getinfo($key,'grm_gift' ,'point_bonus') * $value;
        $TotPoints+=$point;
    endforeach;
    if($TotalClientsPoints<$TotPoints){
        // !! erreur quantité suppèrieur aux points de le clents :
        $_SESSION['msg'] = "Quantité*points Bonus est suppèrieur aux points bonus du clents!!";
        $_SESSION['type'] = "alert-warning";
    }else{
        $restPoints=$TotalClientsPoints-$TotPoints;
        // 2 étape (ajouter la demande et ajouter les produists :
        $dataDemande=array(
            'id_pros'=>$id,
            'id_demandeur'=>filter_input(0,'id_demandeur',FILTER_DEFAULT),
            'point_bonus'=>filter_input(0,'point_bonus',FILTER_DEFAULT),
            'rest_point'=>$restPoints,
            'observation_client'=>filter_input(0,'observation_client',FILTER_SANITIZE_STRING),
            'date_remise_point'=>date("Y-m-d"),
            'etat'=>0,
            'cree_par'=>$_SESSION['user']['id']
        );
        $IdDEmande=add($dataDemande,'grm_demande_cadeaux');
        if($IdDEmande){
            foreach ($Products as $key => $value):
                add(array(
                    'id_demande'=>$IdDEmande,
                    'id_cadeaux'=>$key,
                    'qte'=>$value
                ), 'grm_cadeaux_demander');
            endforeach;
        }
        $_SESSION['msg'] = "Votre demande est sauvegarder";
        $_SESSION['type'] = "alert-success";
        redirect('Liste&idDel='.filter_input(0,'id_demandeur',FILTER_DEFAULT));
    }


endif;
?>
<section class="content-header">
    <h1> Demande de cadeaux :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <form class="" method="post">



        <div class="col-md-4">
            <div class="box box-danger box-body">
            <div class="form-group">
                <label>Demander par : </label>
                <select class="form-control" name="id_demandeur" required>
                    <option value=""> Par utilisateur</option>
                    <?
                    $ListeUser = get('*', 'users');
                    foreach ($ListeUser['reponse'] as $user):
                        ?>
                        <option value="<?= $user['id'] ?>" <? if ($user['id'] == $userID) {
                            echo "selected=selected";
                        } ?>><?= $user['Nom'] . ' ' . $user['Prenom'] ?></option>
                    <? endforeach; ?>

                </select>
            </div>
            <div class="form-group">
                <label>Nombre de points bonus : </label>
                <input type="number" value="" name="point_bonus" id="PintC" class="form-control" required onchange="AddPoint('PintC')">
            </div>
        </div>
        </div>
        <div class="col-md-4">
           <!-- <img src="<?=WEBRoot?>/ajax/qr.php?id=<?=$id?>" class="img-responsive center-block">-->

            <div class="box box-comment box-body">
                <h3>Cadeaux demander pour : </h3>
                <ul>
                    <li><b>Prospect : </b> <?= getinfo($id,'prospect' ,'Nom').' '.getinfo($id,'prospect' ,'Prenom') ?></li>
                    <li><b>Secteur IMS : </b><?=getinfo( getinfo($id,'prospect' ,'gouvernorat'),'gouvernerat' ,'nom' ) ?></li>
                    <li><b>Ancien Point : </b> <t id="PointValIni"><?= $StdFunctions->getAllPoint($id); ?></t></li>
                    <li><b>Nouveau Point : </b> <t id="PointVal"></t></li>
                    <li><b>Total Point : </b> <t id="PointValTot"></t></li>

                </ul>
                <input type="hidden" name="totalPoint" value="0" id="TotPoint" >
                <div class="form-group">
                    <label>Les cadeaux disponible : </label>
                    <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRod()">
                        <option value="">Choix</option>
                        <?
                        $ListeGift = get('*', 'grm_gift',array(
                            'dispo =' => 1,
                            'qte >=' => 1,
                        ));
                        foreach ($ListeGift['reponse'] as $Gift):
                            ?>
                  <option value="<?= $Gift['id'] ?>" rel="<?= $Gift['serialisable'] ?>" bonus="<?=$Gift['point_bonus']?>" ><?= $Gift['titre'] . ' Points : ' . $Gift['point_bonus'] ?></option>
                        <? endforeach; ?>

                    </select>

                </div>
                <div id="ProdListeINp"></div>
            </div>
        </div>
            <div class="col-md-4">
                <div class="box box-success box-body">

                    <div class="form-group">
                        <label>Observation prospect : </label>
                        <textarea class="form-control" name="observation_client" rows="8"></textarea>
                    </div>

                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-info box-footer">

                    <button type="submit" name="Add" value="1" class="btn btn-primary pull-right">Ajouter votre demande</button>

                </div>
            </div>
        </form>
    </div>
    </section>
