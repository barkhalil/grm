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
$repmat=filter_input(INPUT_GET,'grp',FILTER_VALIDATE_INT);

if($repmat){
    $where['grp = ']= $repmat;
    $link.="&grp=$repmat";
}
$title=filter_input(INPUT_GET,'title',FILTER_SANITIZE_STRING);
if($title){
    $where['titre like']= "%$title%";
    $link.="&title=$title";
}
$bCode=filter_input(INPUT_GET,'bCode',FILTER_SANITIZE_STRING);
if($bCode){
    $where['code_article like']= "$bCode%";
    $link.="&bCode=$bCode";
}
$idToSup=filter_input(INPUT_GET,'idToSup',FILTER_VALIDATE_INT);
if($idToSup){
    update($idToSup,array(
        'etat'=>-1
    ),'grm_gift');
}
$Limite=filter_input(1,'d',257);
if(!$Limite) $Limite=0;
$ListeCadeaux=get('*','grm_gift',$where,"AND",array('id'=>'DESC'), array($Limite, 30));
//print_r($ListeCadeaux);
?>
<section class="content-header">
    <h1> Liste des cadeaux </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3">
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
                        <label>Recherche par code à  bare</label>
                        <input type="text" placeholder="Recherche par Code à  bare" name="codeB" class="form-control" autofocus autocomplete="off" id="SearchBC">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-danger box-body">
                <form class="form-inline">

                    <div class="form-group">
                        <select name="famille" class="form-control">
                            <option>Toutes les familles </option>
                            <? $listeFamille=get("*",'grm_gift_family');
                            foreach($listeFamille['reponse'] as $famille):
                                ?>
                                <option value="<?=$famille['id']?>" <? if($familleId==$famille['id']) echo "selected='selected'"?>><?=$famille['nom']?></option>
                            <?endforeach?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="grp" class="form-control">
                            <option>Groupe</option>
                            <? $listeFamille2=get("*",'rep_mat');
                            foreach($listeFamille2['reponse'] as $famille2):
                                ?>
                                <option value="<?=$famille2['id']?>" <? if($repmat==$famille2['id']) echo "selected='selected'"?>><?=$famille2['nom']?></option>
                            <?endforeach?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Recherche par titre" name="title" class="form-control" autofocus autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Recherche par code à  barre" name="bCode" class="form-control" autofocus autocomplete="off">
                    </div>
                    <button type="submit" value="1" name="Filter" class="btn btn-primary">Filtrer</button>
                    <a href="ListeCadeaux" class="btn btn-success">Annuler</a>
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
                        <th>Code</th>
                        <th>Titre</th>
                        <th>Famille</th>
                        <th>Point Bonus</th>
                        <th>Quantité</th>
                        <th>Groupe</th>
                        <th>Colisage</th>
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
                            <td><?=$cade['code_article']?></td>
                            <td><?=$cade['titre']?></td>
                            <td><?=getinfo($cade['famille'],'grm_gift_family','nom') ?></td>
                            <td><?=$cade['point_bonus']?></td>
                            <td><?=getStockProd($cade['id'])->qte?></td>
                            <? /*if($cade['famille']==5 || $cade['famille']==6){?>

                            <?}else {
                                 ?><td><?=$cade['qte']?></td>
                                 <? }
                            */?>
                            <td><?
                                if($cade['grp']) {
                                    echo getinfoByIdv3('nom', 'rep_mat', 'id=' . $cade['grp']);
                                }?></td>
                            <td><?=getStockCollisage($cade['id'])->qte?></td>
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
                                <? if($_SESSION['user']['type']<=102): ?>
                                    <a href="EdtitCadeau&id=<?=$cade['id']?>" class="btn btn-warning" data-toggle="tooltip" title="Modifier">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                <?endif;?>
                                <? if($cade['famille']!=5){?>
                                <a href="../fournisseur/addStocks&id=<?=$cade['id']?>" class="btn btn-flat" data-toggle="tooltip" title="Ajouter au stock">
                                    <i class="fa fa-truck" aria-hidden="true"></i>
                                </a>
                                <?}?>
                                <? if($_SESSION['user']['type']<=102): ?>
                                    <a href="ListeCadeaux&idToSup=<?=$cade['id']?>" class="btn btn-danger" data-toggle="tooltip" title="Supprimer">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?endif;?>
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

                Affichage de <?=($Limite>1) ? $Limite : 1?> à  <?=($Limite+30<$ListeCadeaux['total']) ? $Limite+30 : $ListeCadeaux['total']?> de <?=$ListeCadeaux['total']?> cadeaux

            </div>

        </div>

        <div class="col-md-7">

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                <?pagination($ListeCadeaux['total'],30,WEBRoot."/gift/ListeCadeaux".$link."&d=",""); ?>

            </div>

        </div>

    </div>
</section>