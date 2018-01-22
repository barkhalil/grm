<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 10:09
 */
$idDmd= filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
$dmd=get('*','promo_demander',array('id='=>$idDmd));
//echo '<pre>';print_r($dmd);die;
$cdx=get('*','promo_prod',array('id_promo='=>$idDmd));
$user= get('*','users',array('id='=>$dmd['reponse'][0]['par']));
$user= $user['reponse'][0];
?>
<section class="content-header" style="background: #fff;">
    <h1 class="pull-left"> Matériel promotionnel : </h1>
    <button type="button" id="BtnToPrint" value="1" onclick="PrintDiv()" class="btn btn-facebook pull-right">Imprimer <i class="fa fa-print"></i></button>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content" id="DivToPrint" style="background: #fff;">
    <div class="box-body" style="margin-top: 100px;">
        <table style="width:  100%;">
            <tr>
                <td colspan="2">
                    <h1 style="display: block; text-align: center">Bon de sortie
                        <small>N° <?=$idDmd?> / <?= $dmd['reponse'][0]['date_validation']?></small>
                    </h1>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <label style="color: #582900;">Demandé par: </label> <span><?= $user['Civilite'].' '.$user['Nom'];?> <?= $user['Prenom'];?></span><br/>
                    <label style="color: #582900;">Téléphone: </label> <span><?= $user['Tel'];?></span><br/>
                    <label style="color: #582900;">E-MAIL: </label> <span><?= $user['Email'];?></span><br/>
                    <label >Visiteur médical</label> <br/>
                </td>
                <td style="vertical-align: top;color: #582900;float: right; text-align: center">
                    <b>Vital</b><br/>
                    <b>Service commercial</b><br/>
                    Tél: 71 386 016 - 71 385 339<br/>
                    Fax: 79396 081<br/>
                    MF : 748728 N / A / M / 000<br/>
                    Adresse: Boumhal - Tunisie<br/>
                    <span style="color: #582900;float: right;">Date de bon de commande:  <small style="color: #000;"><?= $dmd['reponse'][0]['sysDate']?></small></span>
                </td>
            </tr>
        </table>
        <h2 style="text-align: center">Matériel promotionnel</h2>
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
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($cd['id_prod'],'grm_gift','titre');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($cd['id_prod'],'grm_gift','description');?></td>
                    <td style="border: 1px solid #999;padding: 10px;"><?=$cd['qte'];?></td>
                </tr>
            <?endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <div style="margin-top: 15px; width: 100%;vertical-align: top;">
                        <div style="width: 49%;display: inline-block;vertical-align: top;">
                            <h4 style="color: #582900;">Observation administrateur: </h4>
                            <span ><?= $dmd['reponse'][0]['observation_admin']?></span>
                        </div>
                    </div>
                </td>
                <td></td>
                <td>
                    <span style="color: #582900;">Date de validation: <small style="color: #000;"><?= $dmd['reponse'][0]['date_validation']?></small> </span><br/><br/>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>
</section>