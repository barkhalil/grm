<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 30/10/2016
 * Time: 21:48
 */
$where=array('etat='=>1);
$Bread = "ListeCadeaux";
$NonDispo=filter_input(INPUT_GET,'NonDispo',FILTER_VALIDATE_INT);
if($NonDispo){
    update($NonDispo,array('dispo'=>0) ,'grm_gift');
}
$Dispo=filter_input(INPUT_GET,'Dispo',FILTER_VALIDATE_INT);
if($Dispo){
    update($Dispo,array('dispo'=>1) ,'grm_gift');
}
$familleId=filter_input(INPUT_GET,'famille',FILTER_VALIDATE_INT);
if($familleId){
    $where['famille = ']= $familleId;
    $link.="&famille=$familleId";
}
$idToSup=filter_input(INPUT_GET,'idToSup',FILTER_VALIDATE_INT);
if($idToSup){
    update($idToSup,array(
            'etat'=>-1
    ),'grm_gift');
}
$Limite=filter_input(1,'d',257);
if(!$Limite) $Limite=0;
$ListeCadeaux=get('*','grm_gift',$where,"AND",array('id'=>'ASC'), array($Limite, 30));
//print_r($ListeCadeaux);
?>
<section class="content-header">
    <h1> Liste des cadeaux </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-danger box-body">
                <form id="SearchForm">
                    <div class="checkbox">
                        <label>
                            <input type="radio" name="type" value="1" checked>Stock
                        </label>
                        <label>
                            <input type="radio" name="type" value="2" >Détail
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Recherche par code à bare</label>
                        <input type="text" placeholder="Recherche par Code à bare" name="codeB" class="form-control" autofocus autocomplete="off" id="SearchBC">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-danger box-body">
                <form>
                    <div class="form-group">
                        <label>Filtre par famille</label>
                        <select name="famille" class="form-control">
                            <option>Tous</option>
                            <? $listeFamille=get("*",'grm_gift_family');
                            foreach($listeFamille['reponse'] as $famille):
                            ?>
                                <option value="<?=$famille['id']?>" <? if($familleId==$famille['id']) echo "selected='selected'"?>><?=$famille['nom']?></option>
                            <?endforeach?>
                        </select>
                    </div>
                    <button type="submit" value="1" name="Filter" class="btn btn-block btn-primary">Filtrer</button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
<div class="col-md-12 table-responsive">
    <div class="box box-success box-body">


    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Famille</th>
            <th>Point Bonus</th>
            <th>Quantité</th>
            <th>Action</th>
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
                <td><?=getinfo($cade['famille'],'grm_gift_family','nom') ?></td>
                <td><?=$cade['point_bonus']?></td>
                <td><?=$cade['qte']-$cade['qte_utiliser']?></td>

                <td>
                    <? if($cade['dispo']): ?>
                    <a href="ListeCadeaux&famille=<?=$familleId?>&d=<?=$Limite?>&NonDispo=<?=$cade['id']?>" class="btn btn-danger" data-toggle="tooltip" title="Non disponible">
                        <i class="fa fa-ban"></i>
                    </a>
                <?else:?>
                    <a href="ListeCadeaux&famille=<?=$familleId?>&d=<?=$Limite?>&Dispo=<?=$cade['id']?>" class="btn btn-success" data-toggle="tooltip" title="Disponible">
                        <i class="fa fa-cart-arrow-down"></i>
                    </a>
                <?endif;?>
                    <a href="Details&id=<?=$cade['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Détails">
                        <i class="fa fa-paperclip"></i>
                    </a>
                    <a href="EdtitCadeau&id=<?=$cade['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Modifier">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>
                 <a href="../fournisseur/addStocks&id=<?=$cade['id']?>" class="btn btn-flat">
                     <i class="fa fa-truck" aria-hidden="true"></i>
                 </a>
                    <a href="ListeCadeaux&idToSup=<?=$cade['id']?>" class="btn btn-danger">
                    <i class="fa fa-trash"></i>
                    </a>
                </td>
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

                <?pagination($ListeCadeaux['total'],30,WEBRoot."/gift/ListeCadeaux".$link."&d=",""); ?>

            </div>

        </div>

    </div>
    </section>
