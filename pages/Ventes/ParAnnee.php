<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 07/02/2017
 * Time: 12:15
 */
?>
<?
$Years=2016;

/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 30/10/2016
 * Time: 21:48
 */
$where=array('code_article!='=>'');
$Bread = "ParAnnee";
$NonDispo=filter_input(INPUT_GET,'NonDispo',FILTER_VALIDATE_INT);
if($NonDispo){
    update($NonDispo,array('dispo'=>0) ,'grm_gift');
    redirect('ListeCadeaux');
}
$Dispo=filter_input(INPUT_GET,'Dispo',FILTER_VALIDATE_INT);
if($Dispo){
    update($Dispo,array('dispo'=>1) ,'grm_gift');
    redirect('ListeCadeaux');
}
$familleId=filter_input(INPUT_GET,'famille',FILTER_VALIDATE_INT);
if($familleId){
    $where['famille = ']= $familleId;
    $link.="&famille=$familleId";
}
$Limite=filter_input(1,'d',257);
if(!$Limite) $Limite=0;
$ListeCadeaux=get('*','grm_gift',$where,"AND",array('id'=>'DESC'), array($Limite, 30));
//print_r($ListeCadeaux);
?>
<section class="content-header">
    <h1> Liste des produits vital Vente / annees (2016 comme test) </h1>
</section><!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-12 table-responsive">
            <div class="box box-success box-body">


                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Quantité</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($ListeCadeaux['reponse'] as $cade):
                        /* update($cade['id'],array(
                                 'paht'=>str_replace(',','.',$cade['paht']), //paht
                                 'pvht'=>str_replace(',','.',$cade['pvht']), //paht
                                 'pvttc'=>str_replace(',','.',$cade['pvttc']), //paht
                             )
                             ,'grm_gift')*/
                        ?>
                        <tr>
                            <td><?=$cade['id']?></td>
                            <td><?=$cade['titre']?></td>
                            <td><?=$cade['code_article'] ? $VentesC->getVentesByYears($Years,$cade['code_article']) :"Pas de code" ?></td>
                                                    </tr>
                    <?endforeach;?>
                    </tbody>
                </table>
            </div>   </div>

    </div>
    <div class="row">

        <div class="col-md-5">

            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                Affichage de <?=($Limite>1) ? $Limite : 1?> à <?=($Limite+30<$ListeCadeaux['total']) ? $Limite+30 : $ListeCadeaux['total']?> de <?=$ListeCadeaux['total']?> cadeaux

            </div>

        </div>

        <div class="col-md-7">

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                <?pagination($ListeCadeaux['total'],30,WEBRoot."/Ventes/ParAnnee".$link."&d=",""); ?>

            </div>

        </div>

    </div>
</section>

