<?php
/**
 * Created by PhpStorm.
 * User: Nagui
 * Date: 18/06/2017
 * Time: 00:54
 */
if(filter_input(INPUT_GET,'annuler',FILTER_VALIDATE_INT)){
    $_SESSION['Point']=0;
    $_SESSION['TotPoint']=0;
    $_SESSION['TotalCdx']=0;
    $_SESSION['Obs']='';
    $_SESSION['ProdPbCmd']=null;
    $_SESSION['CdxCmd']=null;
    unset($_SESSION['TotalCdx']);
    unset($_SESSION['firsttime']);
    unset($_SESSION['ProdPbCmd']);
    unset($_SESSION['CdxCmd']);
    redirect("Liste");
}


$id=filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
//echo $id;die;
/*if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect("Liste");
}*/

$Pbs=get('*','grm_pb_type',array('etat='=>1));
$pb=$Pbs['reponse'][0];
$edit=filter_input(INPUT_GET,'edit',FILTER_VALIDATE_INT);
//echo '<pre>';print_r($_SESSION);die;
$DemandeInfo=get('*','grm_demande_cadeaux',array('id='=>$id));
$Dmd=$DemandeInfo['reponse'][0];
$pointsBs=explode('@_@',$Dmd['ponitsByType']);

if(!isset($_SESSION['firsttime'])) {
    $_SESSION['idDmd']=$id;
    for($i=0;$i<count($pointsBs);$i++) {
        //echo $pointsBs[$i];die;
        if($_SESSION['bonusPoints'][$i]['valeur']!=$pointsBs[$i] && $_SESSION['Point'.$pointsBs[$i]]==0 || $_SESSION['PbClient']!=$id){
            $_SESSION['firsttime']='yes';
        } else {
            $_SESSION['firsttime']='no';
        }
    }
}

if($_SESSION['firsttime']=='yes'){
    if($edit):
        $_SESSION['Point']=0;
        $_SESSION['TotPoint']=0;
        $_SESSION['TotalCdx']=0;
        $_SESSION['Obs']='';
        $_SESSION['ProdPbCmd']=null;
        $_SESSION['CdxCmd']=null;
        unset($_SESSION['TotalCdx']);
        unset($_SESSION['ProdPbCmd']);
        unset($_SESSION['CdxCmd']);
        // récupération de la demande et ces détails :
        $DemandeInfo=get('*','grm_demande_cadeaux',array('id='=>$id));
        $Dmd=$DemandeInfo['reponse'][0];
        $_SESSION['TotPoint']=$Dmd['point_bonus'];
        $_SESSION['Obs']=$Dmd['observation_client'];
        $_SESSION['ObsAdmin']='';
        $_SESSION['PbClient']=$Dmd['id_pros'];
        $pointsBs=explode('@_@',$Dmd['ponitsByType']);
        unset($_SESSION['bonusPoints']);
        for($i=0;$i<count($pointsBs);$i++) {
            //echo $pointsBs[$i];die;
            $_SESSION['bonusPoints'][$i]['valeur']=$pointsBs[$i];
            $_SESSION['bonusPoints'][$i]['pb']=$Pbs['reponse'][$i]['value'];
        }

        //echo'<pre>';print_r($_SESSION['bonusPoints']);die;
        $_SESSION['PbUser']=$Dmd['id_demandeur'];
        $_SESSION['ProdPbCmd']=null;
        //$_SESSION['CdxCmd']=null;
        $DmdDetailsProd=get('*','grm_cadeaux_demander',array('id_demande='=>$id,'type_cdx='=>1));
        $DmdDetailsCdx=get('*','grm_cadeaux_demander',array('id_demande='=>$id,'type_cdx='=>2));
        $QteTot=0;
        //echo'<pre>';print_r($DmdDetailsCdx['reponse']);die;
        foreach($DmdDetailsProd['reponse'] as $ProdPb) {
            $ProdSeaC=$ProdPb['id_cadeaux'];
            $qte=$ProdPb['qte'];
            $_SESSION['ProdPbCmd'][$ProdSeaC]=$qte;
            $QteTot+=$qte*10;
        }
        //echo $QteTot;die;
        foreach($DmdDetailsCdx['reponse'] as $ProdPb) {
            $ProdSeaC=$ProdPb['id_cadeaux'];
            $qte=$ProdPb['qte'];
            $_SESSION['CdxCmd'][$ProdSeaC]=$qte;
            $pbV=getinfo($ProdSeaC,'grm_gift','point_bonus');
            $QteTot+=$qte*$pbV;

        }

        $_SESSION['TotalCdx']=$QteTot;
        //echo $_SESSION['TotalCdx'];die;

    endif;
    $_SESSION['firsttime']='no';
}

$IdSup=filter_input(INPUT_GET,'IdSup',FILTER_VALIDATE_INT);
if($IdSup){
    $id= filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
    $ProdSup=filter_input(INPUT_GET,'prod',FILTER_VALIDATE_INT);
    if($ProdSup) {
        $TotSup=$_SESSION['ProdPbCmd'][$IdSup];
        unset($_SESSION['ProdPbCmd'][$IdSup]);
        $_SESSION['TotalCdx']=$_SESSION['TotalCdx']-($TotSup*10);
        redirect('DmdPb&idDemande='.$id.'&edit=1');
    } else {
        $TotSup=$_SESSION['CdxCmd'][$IdSup];
        unset($_SESSION['CdxCmd'][$IdSup]);
        $pbV=getinfo($IdSup,'grm_gift','point_bonus');
        $_SESSION['TotalCdx']=$_SESSION['TotalCdx']-($TotSup*$pbV);
        redirect('DmdPb&idDemande='.$id.'&edit=1');
    }
}
//echo $_SESSION['TotPoint'];die;
?>
<section class="content-header">
    <h4>Demande de spécifique pour <?= getinfo($_SESSION['PbClient'],'prospect' ,'Nom').' '.getinfo($_SESSION['PbClient'],'prospect' ,'Prenom') ?> Par  : <?= getinfo($_SESSION['PbUser'],'users' ,'Nom').' '.getinfo($_SESSION['PbUser'],'users' ,'Prenom') ?> </h4>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
        <div class="box box-body box-danger">
            <form>
                <ul class="list-inline">
                    <li><b>Ancien Point : </b> <t id="PointValIni"><?= $StdFunctions->getAllPoint($_SESSION['PbClient']); ?></t></li>
                    <li><b>Nouveau : </b> <t id="PointVal"><?=$_SESSION['Point']?></t></li>
                    <li><b>Total : </b> <t id="PointValTot"><?=$_SESSION['TotPoint']?></t></li>
                </ul>
                <label>Observation Client :</label>
                <small><?=$Dmd['observation_client'];?></small>
                <div class="form-group">
                    <label>Observation Administrateur : </label>
                    <textarea class="form-control" name="observation_client" id="ObsAdm" rows="2"><?=$_SESSION['ObsAdmin'] ?></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="totalPoint" value="<?=$_SESSION['TotPoint'] ?>" id="TotPoint" >
                    <input type="hidden" name="client" value="<?=$_SESSION['PbClient'];?>" id="client" >
                    <input type="hidden" name="user" value="<?=$_SESSION['PbUser']?>" id="user" >
                    <input type="hidden" name="idDemande" value="<?=$id?>" id="idDemande" >
                    <div class="form-group">
                        <label>Remise par : </label>
                        <select class="form-control" name="id_remise" id="id_remise"  >
                            <option value=""> Par utilisateur</option>
                            <?
                            $ListeUser = get('*', 'users',array('active>'=>0));
                            foreach ($ListeUser['reponse'] as $user):
                                ?>
                                <option value="<?= $user['id'] ?>" <? if ($user['id'] == $_SESSION['PbUser']) {
                                    echo "selected=selected";
                                } ?>><?= $user['Nom'] . ' ' . $user['Prenom'] ?></option>
                            <? endforeach; ?>

                        </select>
                        <label>
                            <input type="checkbox" name="cdxSansPB" id="cdxSansPB"  hidden>
                        </label><br/>
                    </div>
                    <label>Nombre de points bonus : </label>
                    <div id="pbByDeleg">
                        <?php

                        $Pbs = get("*",'grm_pb_type',array('etat='=>1));
                        foreach ($_SESSION['bonusPoints'] as $pbx): ?>
                            <br>
                            <label>Point bonus de valeur : <?=$pbx['pb']?></label>
                            <input type="number" value="<?=$pbx['valeur'] ?>"  class="form-control pb_calcule"   placeholder="Points cadeaux" pbVal="<?=$pbx['pb']?>" readonly  />
                        <?
                        endforeach;
                        ?>
                    </div>
                    <label>Nombre de points bonus réel : </label>
                    <div id="pBonus">
                        <?php

                        $Pbs = get("*",'grm_pb_type',array('etat='=>1));
                        foreach ($Pbs['reponse'] as $pbx): ?>
                            <br>
                            <label>Point bonus de valeur : <?=$pbx['value']?></label>
                            <input type="number" value="<?=$_SESSION['Point'.$pbx['id']] ?>" name="point_bonus<?=$pbx['id']?>" min="1" style="1" id="PintC<?=$pbx['id']?>" class="form-control pb_calcule" onchange="AddPoint('PintC<?=$pbx['id']?>')" placeholder="Points cadeaux réel" pbVal="<?=$pbx['value']?>" rel="<?=$pbx['id']?>" value="<?=$_SESSION['Point'.$pbx['id']] ?>"  />
                        <?
                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="form-groupe">
                    <label>
                        <input type="radio" name="TypeCadeaux" id="TypeProd" value="1" checked onchange="ShowDiv('prodL')"> Produit
                    </label>
                    <label>
                        <input type="radio" name="TypeCadeaux" id="TypeCadeaux" value="2" onchange="ShowDiv('cadx')"> Cadeaux
                    </label>
                </div>
                <div id="prodL">
                    <div class="form-group">
                        <label for="ProdSeaC">Liste des produits</label>
                        <select class="form-control select2" name="prodListe" id="ProdSeaC">
                            <option value="">Choix du produits</option>
                            <? $request="select products.*,products_prix.qte from products INNER JOIN products_prix ON products.id=products_prix.id_prod WHERE  products_prix.qte>0";
                            $sql = $PDO->prepare($request);
                            $sql->execute();
                            $ListeProd = $sql->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($ListeProd as $prod):?>
                                <option value="<?=$prod['id']?>"><?=$prod['title']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qte">Qte</label>
                        <input type="number" id="qte" name="qte" value="1" step="1" min="1" class="form-control" onkeyup="getMAxQte()" onmouseup="getMAxQte()">
                        <label class="hidden" id="errorMsgQte"></label>
                    </div>
                </div>
                <div id="cadx">
                    <div class="form-group">
                        <label>Les articles disponible : </label>
                        <select class="form-control select2" name="cadeaux" id="CdxSelect" >
                            <option value="">Choix</option>
                            <?
                            // ajouter filter par famille

                                $sql=" Select * from grm_gift WHERE qte >=1 and famille IN(1,3)";
                                $stmt=$PDO->prepare($sql);
                                $stmt->execute();
                                $ListeGift['reponse']=$stmt->fetchAll(PDO::FETCH_ASSOC);


                            foreach ($ListeGift['reponse'] as $Gift):
                                ?>
                                <option value="<?= $Gift['id'] ?>" rel="<?= $Gift['serialisable'] ?>" bonus="<?=$Gift['point_bonus']?>" ><?= $Gift['titre'] . ' Points : ' . $Gift['point_bonus'] ?></option>
                            <? endforeach; ?>

                        </select>

                    </div>
                    <div class="form-group">
                        <label for="qte">Qte</label>
                        <input type="number" id="qteC" name="qteC" value="1" step="1" min="1" class="form-control">
                    </div>
                </div>
            <button type="button" value="1" name="Add" class="btn btn-block btn-primary" onclick="PbAdd('addArticles.php')">Ajouter</button>
            </form>
                </div>

        </div>

        <div class="col-md-8">
            <div class="box box-body box-danger" id="ListeProdSessions">
                <?php echo "<h3>Total point bonus : ".$_SESSION['TotalCdx']." / <span class='totPB'>". $_SESSION['TotPoint'] ." </span></h3>"; ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom du produits</th>
                        <th>Qte</th>
                        <th></th>
                    </tr>
                    </thead>

                    <?
                    if(isset($_SESSION['ProdPbCmd']) && count($_SESSION['ProdPbCmd'])>0):
                    foreach ($_SESSION['ProdPbCmd'] as $key=>$value):?>
                        <tr>
                            <td><?echo getinfo($key,'products','name')?></td>
                            <td><? echo $value?></td>
                            <td>
                                <a href="DmdPb&idDemande=<?=$id?>&IdSup=<?=$key?>&prod=1" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?  endforeach; endif;?>
                    <?if(isset($_SESSION['CdxCmd']) && count($_SESSION['CdxCmd'])>0):
                    foreach ($_SESSION['CdxCmd'] as $key=>$value):?>
                        <tr>
                            <td><?echo getinfo($key,'grm_gift','titre')?></td>
                            <td><? echo $value?></td>
                            <td>
                                <a href="DmdPb&idDemande=<?=$id?>&IdSup=<?=$key?>&cdx=1" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?  endforeach; endif;?>
                </table>
                <br/>
                <a href="DmdPb&annuler=1" class="btn btn-danger pull-left">
                    Annuler
                </a>
                <button href="javascript:void(0)" class="btn btn-success pull-right" onclick="FinalisationPb(4)">Finaliser</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</section>

