<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 15:30
 */
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect(WEBRoot.'/prospects/liste');
}
if(filter_input(0,'Add',257)):
// 1er test si ensemble de ponts des produits <= total point
    $Products=filter_input(INPUT_POST,'prodValue',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    $TotalClientsPoints=filter_input(0,'totalPoint',FILTER_DEFAULT);
    $TotPoints=0;
    if(count($Products)>0){
        foreach ($Products as $key => $value)://key == id
            $point= getinfo($key,'grm_gift' ,'point_bonus') * $value;
            $TotPoints+=$point;
        endforeach;
    }

    if($TotalClientsPoints!=0 && $TotalClientsPoints!="NaN" && $TotalClientsPoints<$TotPoints){
        // !! erreur quantité suppèrieur aux points de le clents :
        $_SESSION['msg'] = "Quantité*points Bonus est suppèrieur aux points bonus du clents!!";
        $_SESSION['type'] = "alert-warning";
    }else{
        if(count($Products)>0):
            $restPoints=$TotalClientsPoints-$TotPoints;
            // 2 étape (ajouter la demande et ajouter les produists :
            $famille=filter_input(0,'famille',FILTER_VALIDATE_INT);
            $delegue=filter_input(0,'delegue',FILTER_VALIDATE_INT);
            $dataDemande=array(
                'id_pros'=>$id,
                'id_demandeur'=>$delegue,
                'point_bonus'=>filter_input(0,'point_bonus',FILTER_DEFAULT),
                'rest_point'=>$restPoints > 0 ? $restPoints : 0,
                'observation_client'=>filter_input(0,'observation_client',FILTER_SANITIZE_STRING),
                'famille'=>$famille,
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
                        'qte'=>$value,
                        'type_cdx'=>0
                    ), 'grm_cadeaux_demander');
                endforeach;
                $_SESSION['msg'] = "Votre demande est sauvegarder";
                $_SESSION['type'] = "alert-success";
                if ($famille==2) {
                    redirect(WEBRoot.'/gestionDesDemandes/listeDemandeVitrine');
                } else {
                    redirect(WEBRoot.'/gestionDesDemandes/listeDemandeOrdonnancier');
                }

            }else{
                $_SESSION['msg'] = "Votre demande n'est pas sauvegarder";
                $_SESSION['type'] = "alert-danger";
            }
        else:
            $_SESSION['msg'] = "Merci de choisir au moins 1 articles!!";
            $_SESSION['type'] = "alert-warning";
        endif;

    }


endif;
?>
<section class="content-header">
    <h1>Demande de spécifique pour prospect :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <form class="" method="post">
            <div class="col-md-4">
                <div class="box box-danger box-body">
                    <h3>Demande pour : </h3>
                    <ul>
                        <li><b>Prospect : </b> <?= getinfo($id,'prospect' ,'Nom').' '.getinfo($id,'prospect' ,'Prenom') ?></li>
                        <li><b>Secteur IMS : </b><?=getinfo( getinfo($id,'prospect' ,'gouvernorat'),'gouvernerat' ,'nom' ) ?></li>
                        <li><b>Ancien Point : </b> <t id="PointValIni"><?= $StdFunctions->getAllPoint($id); ?></t></li>
                        <li><b>Nouveau Point : </b> <t id="PointVal"></t></li>
                        <li><b>Total Point : </b> <t id="PointValTot"></t></li>

                    </ul>
                    <!--<label>
                        <input type="radio" value="0" name="SansPB" checked> Sans Point bonus
                    </label>
                    <label>
                        <input type="radio" value="0" name="SansPB" onchange="RediPage('<?php //echo $id;?>')"> Avec Point bonus
                    </label>!-->
                    <br/>



                </div>
            </div>
            <div class="col-md-4">

                <div class="box box-comment box-body">
                    <div class="form-group">
                        <label>Pour</label>
                        <select name="delegue" class="form-control"  required  >
                            <option value=""></option>
                            <?
                            $users= get('*','users',array('active>='=>1),'AND');
                            foreach ($users['reponse'] as $user):?>
                                <option value="<?=$user['id']?>"> <?=$user['Nom'].' '.$user['Prenom'];?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Famille</label>
                        <select name="famille" class="form-control" id="FamillesProd" required onchange="SelectProd()" >
                            <option value=""></option>
                            <?
                            if($id) $Familes=get("*",'grm_gift_family',array('id<'=>5,'id>'=>1));
                            else $Familes=get("*",'grm_gift_family',array('id='=>5));
                            foreach ($Familes['reponse'] as $fami):
                                if(($_SESSION['user']['type']>2 && $fami['id']<5) || $_SESSION['user']['type']<=2 ):
                                    ?>
                                    <option value="<?=$fami['id']?>"> <?=$fami['nom']?></option>
                                <?endif;endforeach;?>
                        </select>
                    </div>
                    <div id="ProdInfos"></div>
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