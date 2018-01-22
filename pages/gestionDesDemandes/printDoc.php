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
$prospect= get('*','prospect',array('id='=>$dmd['reponse'][0]['id_pros']));
$prospect= $prospect['reponse'][0];
?>
<section class="content-header" style="background: #fff;">
    <h1 class="pull-left"> Cadeaux demandés : </h1>
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

                </td>
                <td style="vertical-align: top;">
                    <p style="color: #582900;float: right; text-align: center">
                        <b>Vital</b><br/>
                        <b>Service commercial</b><br/>
                        Tél: 71 386 016 - 71 385 339<br/>
                        Fax: 79396 081<br/>
                        MF : 748728 N / A / M / 000
                    </p>

                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <span style="color: #582900;">Date de validation: <small style="color: #000;"><?= $dmd['reponse'][0]['date_validation']?></small> </span><br/><br/>
                </td>
                <td style="vertical-align: top;">
                    <span style="color: #582900;float: right;">Date de demande:  <small style="color: #000;"><?= $dmd['reponse'][0]['system_date']?></small></span><br/><br/>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <label style="color: #582900;">Le destinataire: </label><br/> <span><?= $prospect['nom'];?> <?= $prospect['prenom'];?></span><br/>
                    <label style="color: #582900;">Specialité: </label> <span><?= getinfo($prospect['spec'],'specialite','nom');?></span><br/>
                    <label style="color: #582900;">Délégation: </label> <span><?= getinfo($prospect['delegation'],'delegation','nom');?></span><br/>
                    <?php if($prospect['secteur_ims']): ?>
                    <label style="color: #582900;">Sécteur: </label> <span><?= $prospect['secteur_ims'];?></span><br/>
                    <?endif;?>
                    <?php if($prospect['tel']): ?>
                        <label style="color: #582900;">Téléphone: </label> <span><?= $prospect['tel'];?></span><br/>
                    <?endif;?>
                    <?php if($prospect['tel_2']): ?>
                        <label style="color: #582900;">Téléphone: </label> <span><?= $prospect['tel_2'];?></span><br/>
                    <?endif;?>
                    <?php if($prospect['email']): ?>
                        <label style="color: #582900;">E-MAIL: </label> <span><?= $prospect['email'];?></span><br/>
                    <?endif;?>
                </td>
                <td style="vertical-align: top;float: right;">
                    <label style="color: #582900;">Demander par: </label> <span><?= getinfo($dmd['reponse'][0]['id_demandeur'],'users','Nom');?><?= getinfo($dmd['reponse'][0]['id_demandeur'],'users','Prenom');?></span><br/>
                    <label style="color: #582900;">Livrer par: </label> <span><?= getinfo($dmd['reponse'][0]['id_remise'],'users','Nom');?><?= getinfo($dmd['reponse'][0]['id_remise'],'users','Prenom');?></span><br/>
                    <label style="color: #582900;">Date de livraison: </label> <span><?= $dmd['reponse'][0]['date_livraison']?></span><br/>
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
                        <?= $prod['qte']?> pour
                        <? if($prod['type_cdx']==1){
                            echo   getinfo($prod['id_cadeaux'],'products' ,'name');
                        }else{
                            echo  getinfo($prod['id_cadeaux'],'grm_gift' ,'titre') ;
                        }?>
                        <td style="border: 1px solid #999;padding: 10px;">
                            <? if($cd['type_cdx']==1){
                                echo   getinfo($cd['id_cadeaux'],'products' ,'name');
                            }else{
                                echo  getinfo($cd['id_cadeaux'],'grm_gift' ,'titre') ;
                            }?>
                        </td>
                        <td style="border: 1px solid #999;padding: 10px;">
                            <? if($cd['type_cdx']==1){
                                echo   getinfo($cd['id_cadeaux'],'products' ,'description');
                            }else{
                                echo  getinfo($cd['id_cadeaux'],'grm_gift' ,'description') ;
                            }?>
                        </td>
                        <td style="border: 1px solid #999;padding: 10px;"><?=$cd['qte'];?></td>
                    </tr>
                <?endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">
                    <div style="margin-top: 15px; width: 100%;vertical-align: top;">
                        <div style="width: 49%;display: inline-block;vertical-align: top;">
                            <h4 style="color: #582900;">Observation administrateur: </h4>
                            <span ><?= $dmd['reponse'][0]['oberservation_admin']?></span>
                        </div>
                        <div style="width: 49%;display: inline-block;">
                            <h4 style="color: #582900;">Observation client: </h4>
                            <span ><?= $dmd['reponse'][0]['observation_client']?></span>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="color: #582900;">Date de validation: <small style="color: #000;"><?= $dmd['reponse'][0]['date_validation']?></small> </span><br/><br/>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>
</section>