<?php
/**
 * Created by PhpStorm.
 * User: Nagui
 * Date: 19/06/2017
 * Time: 00:07
 */
$idDmd= filter_input(INPUT_GET,'idDemande',FILTER_VALIDATE_INT);
$dmd=get('*','grm_demande_cadeaux',array('id='=>$idDmd));
//echo '<pre>';print_r($dmd);die;
$cdx=get('*','grm_cadeaux_demander',array('id_demande='=>$idDmd));

?>
<section class="content-header" style="background: #fff;">
    <h1 class="pull-left"> Cadeaux demandés : </h1>
    <button type="button" id="BtnToPrint" value="1" onclick="PrintDiv()" class="btn btn-facebook pull-right">Imprimer <i class="fa fa-print"></i></button>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content" id="DivToPrint" style="background: #fff;">
    <div class="box-body">
        <table style="width:  100%;">
            <tr>
                <td>
                    <label style="color: #582900;">Date de validation: </label> <span><?= $dmd['reponse'][0]['date_validation']?></span><br/>
                    <label style="color: #582900;">Date de remise points: </label> <span><?= $dmd['reponse'][0]['date_remise_point']?></span><br/>
                    <label style="color: #582900;">Date de pointage: </label> <span><?= $dmd['reponse'][0]['date_pointage']?></span><br/>
                    <label style="color: #582900;">Date de livraison: </label> <span><?= $dmd['reponse'][0]['date_livraison']?></span>
                </td>
                <td>
                    <label style="color: #582900;">Date de demande: </label> <span><?= $dmd['reponse'][0]['system_date']?></span><br/>
                    <label style="color: #582900;">Demandeur: </label> <span><?= getinfo($dmd['reponse'][0]['id_demandeur'],'users','Nom');?><?= getinfo($dmd['reponse'][0]['id_demandeur'],'users','Prenom');?></span><br/>
                    <label style="color: #582900;">Le destinataire: </label> <span><?= getinfo($dmd['reponse'][0]['id_pros'],'prospect','nom');?><?= getinfo($dmd['reponse'][0]['id_pros'],'prospect','prenom');?></span><br/>
                </td>
            </tr>
        </table>
        <h2 style="text-align: center">Liste des cadeaux</h2>
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
                        <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($cd['id_cadeaux'],'grm_gift','titre');?></td>
                        <td style="border: 1px solid #999;padding: 10px;"><?=getinfo($cd['id_cadeaux'],'grm_gift','description');?></td>
                        <td style="border: 1px solid #999;padding: 10px;"><?=$cd['qte'];?></td>
                    </tr>
                <?endforeach;?>
            </tbody>
        </table>
        <div style="margin-top: 15px; width: 100%;vertical-align: top;">
            <div style="width: 49%;display: inline-block;">
                <h4 style="color: #582900;">Observation client: </h4>
                <span ><?= $dmd['reponse'][0]['observation_client']?></span>
            </div>
            <div style="width: 49%;display: inline-block;vertical-align: top;">
                <h4 style="color: #582900;">Observation administrateur: </h4>
                <span ><?= $dmd['reponse'][0]['oberservation_admin']?></span>
            </div>
        </div>
    </div>
</section>