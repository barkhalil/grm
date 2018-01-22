<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 10:23
 */
$idDmd= filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
$dmd=get('*','echant_demander',array('id='=>$idDmd));
//echo '<pre>';print_r($dmd);die;
$cdx=get('*','echant_prod',array('id_echant='=>$idDmd));
$user= get('*','users',array('id='=>$dmd['reponse'][0]['par']));
$user= $user['reponse'][0];
?>
<section class="content-header" style="background: #fff;">
    <h1 class="pull-left"> Echantillons demandés : </h1>
    <button type="button" id="BtnToPrint" value="1" onclick="PrintDiv()" class="btn btn-facebook pull-right">Imprimer <i class="fa fa-print"></i></button>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content" id="DivToPrint" style="background: #fff;">
    <div class="box-body" style="margin-top: 100px;">
        <table style="width:  100%;">
            <tr>
                <td style="vertical-align: top;">
                    <span style="color: #582900;">Date de validation: <small style="color: #000;"><?= $dmd['reponse'][0]['date_validation']?></small> </span><br/><br/>
                </td>
                <td style="vertical-align: top;">
                    <span style="color: #582900;float: right;">Date de demande:  <small style="color: #000;"><?= $dmd['reponse'][0]['sysDate']?></small></span><br/><br/>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <label style="color: #582900;">Demander par: </label> <span><?= $user['Nom'];?> <?= $user['Prenom'];?></span><br/>
                    <label style="color: #582900;">Téléphone: </label> <span><?= $user['Tel'];?></span><br/>
                    <label style="color: #582900;">E-MAIL: </label> <span><?= $user['Email'];?></span><br/>
                    <label style="color: #582900;">Zone: </label> <span><?= getinfo($user['Email'],'zone','nom');?></span><br/>
                    <label style="color: #582900;">Département: </label> <span><?= getinfo($user['departement'],'departement','nom');?></span><br/>
                    <label style="color: #582900;">Civilité: </label> <span><?= $user['Civilite'];?></span><br/>
                    <label ><?= getinfo($user['type'],'user_type','name')?></label> <br/>
                </td>
                <td style="vertical-align: top;float: right;">
                    <label style="color: #582900;">Date de livraison: </label> <span><?= $dmd['reponse'][0]['date_livraison']?></span><br/>
                </td>
            </tr>
        </table>
        <div style="margin-top: 15px; width: 100%;vertical-align: top;">
            <div style="width: 49%;display: inline-block;vertical-align: top;">
                <h4 style="color: #582900;">Observation administrateur: </h4>
                <span ><?= $dmd['reponse'][0]['observation_admin']?></span>
            </div>
        </div>
        <h2 style="text-align: center">Liste des échantillons</h2>
        <table style="border: 1px solid #999; width: 100%;">
            <thead>
                <tr>
                    <th style="border: 1px solid #999;padding: 10px">#</th>
                    <th style="border: 1px solid #999;padding: 10px">Cadeaux</th>
                    <th style="border: 1px solid #999;padding: 10px">Déscription</th>
                    <th style="border: 1px solid #999;padding: 10px">Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cdx['reponse'] as $cd):?>
                    <tr>
                        <td style="border: 1px solid #999;padding: 10px;"><?=$cd['id'];?></td>
                        <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($cd['id_prod'],'products','name');?></td>
                        <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($cd['id_prod'],'products','description');?></td>
                        <td style="border: 1px solid #999;padding: 10px;"><?=$cd['qte'];?></td>
                    </tr>
                <?endforeach;?>
            </tbody>
        </table>
    </div>
</section>