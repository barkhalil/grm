<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 02/01/18
 * Time: 13:58
 */
$id=filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect('dmdPromotionnel');
}

$Demande=get('*','promo_demander',array('id='=>$id));
$DemandeDet=$Demande['reponse'][0];
if($DemandeDet['etat']>=1)
    redirect('dmdPromotionnel');
$Gifts=get("*",'promo_prod',array('id_promo='=>$id));
//print_r($Gifts);
if(filter_input(0,'Add',257)):
// 1er test si ensemble de ponts des produits <= total point
    $Products=filter_input(INPUT_POST,'prodValue',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    $dataDemande= array(
        'observation_admin'=>filter_input(0,'observation_admin',FILTER_SANITIZE_STRING),
        'date_validation'=>date("Y-m-d"),
        'etat'=>1,
        'valider_par'=>$_SESSION['user']['id']
    );
    $IdDEmande=update($id,$dataDemande,'promo_demander');
    if($IdDEmande ){
        //echo '<pre>';print_r($Products);die;
        foreach ($Gifts['reponse'] as $keyG):
            delete($keyG['id'],'promo_prod');
        endforeach;
        if(count($Products)>0){
            foreach ($Products as $key => $value):
                add(array(
                    'id_promo'=>$id,
                    'id_prod'=>$key,
                    'qte'=>$value,
                ), 'promo_prod');
                $Gcc->DimStock($key,$value);
            endforeach;
        }
    }
    $_SESSION['msg'] = "Votre demande est sauvegarder";
    $_SESSION['type'] = "alert-success";
    redirect('dmdPromotionnel');
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
                        <?= getinfo($DemandeDet['par'],'users' ,'Nom').' '.getinfo($DemandeDet['par'],'users' ,'Prenom') ?>
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
                                'famille = '=>5
                            ));
                            foreach ($ListeGift['reponse'] as $Gift):
                                ?>
                                <option value="<?= $Gift['id'] ?>" rel="<?= $Gift['serialisable'] ?>" >
                                    <?= $Gift['titre']; ?>
                                </option>
                            <? endforeach; ?>

                        </select>

                    </div>
                    <div id="ProdListeINp">
                        <? foreach($Gifts['reponse'] as $Prod): ?>
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