<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 15:59
 */
if(filter_input(INPUT_GET,'annuler',FILTER_VALIDATE_INT)){
    $_SESSION['Point']=0;
    $_SESSION['TotPoint']=0;
    $_SESSION['TotalCdx']=0;
    $_SESSION['Obs']='';
    $_SESSION['ProdPbCmd']=null;
    $_SESSION['CdxCmd']=null;
    unset($_SESSION['TotalCdx']);
    unset($_SESSION['ProdPbCmd']);
    unset($_SESSION['CdxCmd']);
    if($_SESSION['user']['type']<=2 || $_SESSION['user']['type']>5){
        redirect(WEBRoot."/prospects/listeAdmin");
    }else {
        redirect(WEBRoot . "/prospects/listeD");
    }
}
$Pbs=get('*','grm_pb_type',array('etat='=>1));
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if($_SESSION['PbClient']) {
    if($_SESSION['PbClient']!=$id) {
        $pointsBonus->viderSession();
    }
}
if(!$id){
    $_SESSION['msg'] = "Missing Id !!";
    $_SESSION['type'] = "alert-danger";
    redirect(WEBRoot.'/prospects/liste');
}
$pb=$Pbs['reponse'][0];

$IdSup=filter_input(INPUT_GET,'IdSup',FILTER_VALIDATE_INT);
if($IdSup){
    $ProdSup=filter_input(INPUT_GET,'prod',FILTER_VALIDATE_INT);
    //echo 'ok';die;
    if($ProdSup){
        $pBCadeau=getinfo($IdSup,'grm_gift','point_bonus');
        //echo '<pre>';print_r($pBCadeau);die;
        $TotSup=$_SESSION['ProdPbCmd'][$IdSup];
        //echo $TotSup;die;
        unset($_SESSION['ProdPbCmd'][$IdSup]);
        $_SESSION['TotalCdx']=$_SESSION['TotalCdx']-($TotSup*$pBCadeau);
        redirect('DmdPb&id='.$id);
    } else {
        $TotSup=$_SESSION['CdxCmd'][$IdSup];
        //echo $TotSup;die;
        unset($_SESSION['CdxCmd'][$IdSup]);
        $pbV=getinfo($IdSup,'grm_gift','point_bonus');
        $_SESSION['TotalCdx']=$_SESSION['TotalCdx']-($TotSup*10);
        redirect('DmdPb&id='.$id);
    }


}
?>
<section class="content-header">
    <h1>Demande de spécifique pour <?= getinfo($id,'prospect' ,'Nom').' '.getinfo($id,'prospect' ,'Prenom') ?> :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-body box-danger">
                <form>
                    <ul class="list-inline">
                        <li><b>Ancien Point : </b> <t id="PointValIni"><?= $StdFunctions->getAllPoint($id); ?></t></li>
                        <li><b>Nouveau : </b> <t id="PointVal"><?=$_SESSION['newPb']?></t></li>
                        <li><b>Total : </b> <t id="PointValTot"><?=$_SESSION['TotPoint']?></t></li>
                    </ul>
                    <div class="form-group">
                        <label>Observation prospect : </label>
                        <textarea class="form-control" name="observation_client" id="Obs" rows="4"><?=$_SESSION['Obs'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="totalPoint" value="<?=$_SESSION['TotPoint'] ?>" id="TotPoint" >
                        <input type="hidden" name="client" value="<?=$id?>" id="client" >

                        <label>Nombre de points bonus : </label>
                        <div id="pBonus">
                            <?php

                            $Pbs = get("*",'grm_pb_type',array('etat='=>1));
                            foreach ($Pbs['reponse'] as $pbx): ?>
                                <br>
                                <label>Point bonus de valeur : <?=$pbx['value']?></label>
                                <input type="number" value="<?=$_SESSION['Point'.$pbx['id']] ?>" name="point_bonus<?=$pbx['id']?>" min="1" style="1" id="PintC<?=$pbx['id']?>" class="form-control pb_calcule" required onchange="AddPoint('PintC<?=$pbx['id']?>')" placeholder="Points cadeaux" pbVal="<?=$pbx['value']?>" rel="<?=$pbx['id']?>"   />
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
                            <label for="gamme">Gamme :</label>
                            <select id="gamme" name="gamme" class="form-control" onchange="GetProdListe()">
                                <option value="">Choix</option>
                                <?php $Gammes=get("*",'prod_categorie');
                                foreach ($Gammes['reponse'] as $gamme): ?>
                                    <option value="<?=$gamme['id']?>"><?=$gamme['nom']?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                        <div id="ProdACmd"></div>
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
                    <button type="button" value="1" name="Add" class="btn btn-block btn-primary" onclick="PbAdd()">Ajouter</button>
                </form>
            </div>

        </div>

        <div class="col-md-8">
            <div class="box box-body box-danger" id="ListeProdSessions">
                <?php echo "<h3>Total point bonus : ".$_SESSION['TotalCdx']." / <span class='totPB'>". $_SESSION['TotPoint'] ."</span></h3>"; ?>
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
                                    <a href="DmdPb&id=<?=$id?>&IdSup=<?=$key?>&prod=1" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?  endforeach; endif;?>
                    <?if(isset($_SESSION['CdxCmd']) && count($_SESSION['CdxCmd'])>0):
                        foreach ($_SESSION['CdxCmd'] as $key=>$value):?>
                            <tr>
                                <td><?echo getinfo($key,'grm_gift','titre')?></td>
                                <td><? echo $value?></td>
                                <td>
                                    <a href="DmdPb&id=<?=$id?>&IdSup=<?=$key?>&cdx=1" class="btn btn-danger">
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
                <a href="javascript:void(0)" class="btn btn-success pull-right" onclick="FinalisationPb()">Finaliser</a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</section>