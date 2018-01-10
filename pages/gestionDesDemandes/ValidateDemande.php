<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 30/10/2016
 * Time: 23:24
 */
//echo '<pre>';print_r($_POST);die;
$id=filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect('Liste');
}

$Demande=get('*','grm_demande_cadeaux',array('id='=>$id));
$DemandeDet=$Demande['reponse'][0];
if($DemandeDet['famille']==4 && $DemandeDet['etat']>=4)
    redirect('listeDemandeOrdonnancier');
if($DemandeDet['famille']==2 && $DemandeDet['etat']>=4)
    redirect('listeDemandeVitrine');

$Gifts=get("*",'grm_cadeaux_demander',array('id_demande='=>$id));
//print_r($Gifts);
if(filter_input(0,'Add',257)):
// 1er test si ensemble de ponts des produits <= total point
    $Products=filter_input(INPUT_POST,'prodValue',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    //$TotalClientsPoints=filter_input(0,'totalPoint',FILTER_DEFAULT);
    $TotPoints=0;
    if(count($Products)>0){
        foreach ($Products as $key => $value):
//key == id
            $point= getinfo($key,'grm_gift' ,'point_bonus') * $value;
            $TotPoints+=$point;
        endforeach;
    }
        $dataDemande= array(
            'id_remise'=>filter_input(0,'id_remise',FILTER_DEFAULT),
            'oberservation_admin'=>filter_input(0,'observation_admin',FILTER_SANITIZE_STRING),
            'date_validation'=>date("Y-m-d"),
            'etat'=>4,
            'modifier_par'=>$_SESSION['user']['id']
        );
        $IdDEmande=update($id,$dataDemande,'grm_demande_cadeaux');
        if($IdDEmande ){
            //echo '<pre>';print_r($Products);die;
            foreach ($Gifts['reponse'] as $keyG):
                delete($keyG['id'],'grm_cadeaux_demander');
            endforeach;
            if(count($Products)>0){
                foreach ($Products as $key => $value):
                    add(array(
                        'id_demande'=>$id,
                        'id_cadeaux'=>$key,
                        'qte'=>$value,
                        'type_cdx'=>0,
                    ), 'grm_cadeaux_demander');
                    $Gcc->DimStock($key,$value);
                endforeach;
            }
        }
        $_SESSION['msg'] = "Votre demande est sauvegarder";
        $_SESSION['type'] = "alert-success";
        if($DemandeDet['famille']==4)
            redirect('listeDemandeOrdonnancier');
        else
            redirect('listeDemandeVitrine');
endif;



?>
<section class="content-header">
    <h1> validation demande de cadeaux :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <form class="" method="post">


        <div class="col-md-4">
            <div class="box box-success box-body">
                <h4>Information demande</h4>
                <div class="form-group">
                    <label>Ref. : <?=$DemandeDet['id'].'/'.date("Y",strtotime($DemandeDet['system_date']))?></label>

                </div>
                <div class="form-group">
                    <label>Observation prospect : </label>
                    <p><?=$DemandeDet['observation_client']?> </p>
                </div>
                <div class="form-group">
                    <label>Demander par : </label>
                    <?= getinfo($DemandeDet['id_demandeur'],'users' ,'Nom').' '.getinfo($DemandeDet['id_demandeur'],'users' ,'Prenom') ?>
                </div>
            </div>
                </div>
        <div class="col-md-4">
           <!-- <img src="<?=WEBRoot?>/ajax/qr.php?id=<?=$id?>" class="img-responsive center-block">-->

            <div class="box box-comment box-body">
                <h4>Produits demander pour : </h4>
                <ul>
                    <li><b>Prospect : </b> <?= getinfo($DemandeDet['id_pros'],'prospect' ,'Nom').' '.getinfo($DemandeDet['id_pros'],'prospect' ,'Prenom') ?></li>
                    <li><b>Secteur IMS : </b><?=getinfo( getinfo($DemandeDet['id_pros'],'prospect' ,'gouvernorat'),'gouvernerat' ,'nom' ) ?></li>

                </ul>
                <input type="hidden" name="totalPoint" value="0" id="TotPoint" >
                <div class="form-group">
                    <label>Les produits disponible : </label>
                    <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRod(1)">
                        <option value="">Choix</option>
                        <?
                        $ListeGift = get('*', 'grm_gift',array(
                            'dispo =' => 1,
                            'qte >=' => 1,
                            'famille = '=>$DemandeDet['famille']
                        ));
                        foreach ($ListeGift['reponse'] as $Gift):
                            ?>
                  <option value="<?= $Gift['id'] ?>" rel="<?= $Gift['serialisable'] ?>" >
                      <?= $Gift['titre'];?>
                  </option>
                        <? endforeach; ?>

                    </select>

                </div>
                <div id="ProdListeINp">
                <? foreach($Gifts['reponse'] as $Prod): ?>
                    <div class="form-group" id="<?=$Prod['id_cadeaux']?>">
                   <label>
                        <a href="javascript:void(0)" onclick="RemouveDiv('<?=$Prod['id_cadeaux']?>')" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                       <?=getinfo($Prod['id_cadeaux'],'grm_gift' ,'titre')?><br/> Quantité :

                    </label>
                    <input type="number" name="prodValue[<?=$Prod['id_cadeaux']?>]" value="<?=$Prod['qte']?>"  min="1" class="form-control QteProd" onchange="VerifyPoints()">
                  <?  if(getinfo($Prod['id_cadeaux'],'grm_gift' ,'serialisable')==1 ){ ?>
                    <div id="prodSerie'+prod+'">
                        <label>Numero de Série</label>
                        <textarea name="Series" class="form-control"></textarea>
                        </div>
                   <? } ?>
                    </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-danger box-body">
                <h4>Information Livraison</h4>
                <div class="form-group">
                    <label>Livrer par : </label>
                    <select class="form-control" name="id_remise" required >
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
                        <label>Observation administration : </label>
                        <textarea class="form-control" name="observation_admin" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-info box-footer">

                    <button type="submit" name="Add" value="1" class="btn btn-primary pull-right">Valider la  demande</button>

                </div>
            </div>
        </form>
    </div>
    </section>
