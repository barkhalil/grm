<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 11:40
 */
$id=filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect('listeDmdMaterelDeleg');
}

$Demande=get('*','materiel_deleg',array('id='=>$id));
$DemandeDet=$Demande['reponse'][0];
if($DemandeDet['etat']>=1)
    redirect('listeDmdMaterelDeleg');
$Gifts=get("*",'materiel_deleg_details',array('id_dmd='=>$id));
//print_r($Gifts);
if(filter_input(0,'Add',257)):
// 1er test si ensemble de ponts des produits <= total point
    $Products=filter_input(INPUT_POST,'prodValue',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    $dataDemande= array(
        'observation_admin'=>filter_input(0,'observation_admin',FILTER_SANITIZE_STRING),
        'date_validation'=>date("Y-m-d"),
        'etat'=>1,
        'id_validateur'=>$_SESSION['user']['id']
    );
    $IdDEmande=update($id,$dataDemande,'materiel_deleg');
    if($IdDEmande ){
        //echo '<pre>';print_r($Products);die;
        foreach ($Gifts['reponse'] as $keyG):
            delete($keyG['id'],'materiel_deleg_details');
        endforeach;
        if(count($Products)>0){
            foreach ($Products as $key => $value):
                add(array(
                    'id_dmd'=>$id,
                    'id_prod'=>$key,
                    'qte'=>$value,
                ), 'materiel_deleg_details');
                $Gcc->DimStock($key,$value);
            endforeach;
        }
    }
    $_SESSION['msg'] = "Votre demande est sauvegarder";
    $_SESSION['type'] = "alert-success";
    redirect('listeDmdMaterelDeleg');
endif;
?>
<section class="content-header">
    <h1> validation demande de produits promotionnel :  </h1>
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
                        <label>Demander par : </label>
                        <?= getinfo($DemandeDet['id_deleg'],'users' ,'Nom').' '.getinfo($DemandeDet['id_deleg'],'users' ,'Prenom') ?>
                    </div>
                    <div class="form-group">
                        <label>Observation : </label>
                        <?= $DemandeDet['observation']; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- <img src="<?=WEBRoot?>/ajax/qr.php?id=<?=$id?>" class="img-responsive center-block">-->

                <div class="box box-comment box-body">
                    <div class="form-group">
                        <label>Les produits disponible : </label>
                        <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRod(1)">
                            <option value="">Choix</option>
                            <?
                            $ListeGift = get('*', 'grm_gift',array(
                                'dispo =' => 1,
                                'qte >=' => 1,
                                'famille = '=>6
                            ));
                            foreach ($ListeGift['reponse'] as $Gift):
                                ?>
                                <option value="<?= $Gift['id'] ?>" rel="<?= $Gift['serialisable'] ?>" bonus="<?=$Gift['point_bonus']?>" >
                                    <?= $Gift['titre'] . ' Points : ' . $Gift['point_bonus'] ?>
                                </option>
                            <? endforeach; ?>

                        </select>

                    </div>
                    <div id="ProdListeINp">
                        <?
                        foreach($Gifts['reponse'] as $Prod):
                            ?>
                            <div class="form-group" id="<?=$Prod['id_prod']?>">
                                <label>
                                    <a href="javascript:void(0)" onclick="RemouveDiv('<?=$Prod['id_prod']?>')" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <?=getinfo($Prod['id_prod'],'grm_gift' ,'titre')?><br/> Quantité :

                                </label>
                                <input type="number" name="prodValue[<?=$Prod['id_prod']?>]" value="<?=$Prod['qte']?>"  min="1" class="form-control QteProd" onchange="VerifyPoints()">
                                <?  if(getinfo($Prod['id_prod'],'grm_gift' ,'serialisable')==1 ){ ?>
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