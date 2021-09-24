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
$user= get('*','users',array('id='=>$dmd['reponse'][0]['id_demandeur']));
$user= $user['reponse'][0];
 $vv=getinfoByIdv3('hash','bs',' id_demande='.$idDmd);
if($vv) {
    $path = '' . WEBRoot . '/ajax/qr2.php?id=' . $idDmd;
}else{
    $path='15963';
}
?>
<style type="text/css" media="print">
    @page
    {
        size:  auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    html
    {
        background-color: #FFFFFF;
        margin: 0px;  /* this affects the margin on the html before sending to printer */
    }

   
    </style>
<section class="content-header" style="background: #fff;">
    <input id="txtpath" name="txtpath" type="text" hidden value="<?=$path?>">
    <h1 class="pull-left"> Cadeaux demandés : </h1>
    <button type="button" id="BtnToPrint" value="1" onclick="PrintDiv2()" class="btn btn-facebook pull-right">Imprimer <i class="fa fa-print"></i></button>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content" id="DivToPrint" style="background: #fff;">
    <div class="box-body" style="">
        <table style="width:  100%;">
            <tr>
                <td></td>
                <td >
                    <h1 style="display: block; text-align: center">Bon de sortie
                        <small>N° <?=$idDmd?> / <?= date('d-m-Y',strtotime($dmd['reponse'][0]['date_validation']))?></small>
                    </h1>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <label style="color: #582900;">Le destinataire: </label> <span><?= $prospect['nom'];?> <?= $prospect['prenom'];?></span><br/>
                    <label style="color: #582900;">Délégation: </label> <span><?= getinfo($prospect['delegation'],'delegation','nom');?></span><br/>
                    <label style="color: #582900;">Demander par: </label> <span><?= $user['Civilite'].' '.$user['Nom'];?> <?= $user['Prenom'];?></span><br/>
                    <label >Visiteur médical</label> <br/>
                    <label style="color: #582900;">Matricule : </label> <span>  <?=$user['matricule']?>  </span><br/>

                </td>
                <td>
                    <?
                    if($vv){
                        $path=''.WEBRoot.'/ajax/qr2.php?id='.$idDmd
                    ?>
                   <div id="qrcode"> <img src="<?=$path?>" class="img-responsive center-block">
                   </div>
                       <?}?>

                </td>
                <td style="vertical-align: top;color: #582900;float: right; text-align: center">
                    <b>Vital</b><br/>
                    <b>Service commercial</b><br/>
                    Tél: 71 386 016 - 71 385 339<br/>
                    Fax: 79396 081<br/>
                    MF : 748728 N / A / M / 000<br/>
                    Adresse: Boumhal - Tunisie<br/>
                    <span style="color: #582900;float: right;">Date de bon de commande:  <small style="color: #000;"><?= $dmd['reponse'][0]['system_date']?></small></span>
                </td>
            </tr>


        </table>

        <h2 style="text-align: center">
            <?if($dmd['reponse'][0]['famille']==4):
                echo 'Demande ordonnancier';
            elseif ($dmd['reponse'][0]['famille']==2):
                echo 'Demande vitrine';
            else:
                echo 'Liste des cadeaux / articles';
            endif;
            ?>

        </h2>
        <table style="border: 1px solid #999; width: 100%;">
            <thead>
                <tr>
                    <th style="border: 1px solid #999;padding: 10px">Code</th>
                    <th style="border: 1px solid #999;padding: 10px">Cadeaux</th>
                    <th style="border: 1px solid #999;padding: 10px">Déscription</th>
                    <th style="border: 1px solid #999;padding: 10px">Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cdx['reponse'] as $cd):?>
                    <tr>
                        <td style="border: 1px solid #999;padding: 10px;">
                            <? if($cd['type_cdx']==1){
                                echo   getinfo($cd['id_cadeaux'],'products' ,'code_article');
                            }else{
                                echo  getinfo($cd['id_cadeaux'],'grm_gift' ,'code_article') ;
                            }?>
                         </td>

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
            </tbody>
        </table>

    </div>
</section>