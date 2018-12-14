<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 16/04/2018
 * Time: 10:55
 */

$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$users=get('*','users',array('active='=>1),'AND',array('Nom'=>'ASC'));
$sectrs=get('*','gouvernerat',NULL,'AND',array('nom'=>'ASC'));
$deleg=$gouver=$delegations=$from=$to=$isCart=NULL;
$deleg=filter_input(INPUT_GET,'user',FILTER_VALIDATE_INT);
$gouver=$_GET['secteur'];//filter_input(INPUT_GET,'secteur',FILTER_VALIDATE_INT);
$delegations=$_GET['delegation'];
$from=filter_input(INPUT_GET,'from',FILTER_DEFAULT);
$to=filter_input(INPUT_GET,'to',FILTER_DEFAULT);
$isCart=filter_input(INPUT_GET,'iscart',FILTER_DEFAULT);//echo $isCart;die;
$totalpbdate=0;
$index=0;
if($from && $to) {
    $from = str_replace('/', '-', $from);
    $from= date('Y-m-d', strtotime($from));
    $to = str_replace('/', '-', $to);
    $to= date('Y-m-d', strtotime($to));
   $totalpbdate=$pointsBonus->gps(30,$Limite,$gouver,$delegations,$deleg,$from,$to,$isCart);
   // $Bs=$pointsBonus->AllPBpros($from,$to);
}

//echo $from.' '.$to;
$totalpbdate=$pointsBonus->gps(30,$Limite,$gouver,$delegations,$deleg,$from,$to,$isCart);
$pointsBs=$pointsBonus->AllPBpros(30,$Limite,$gouver,$delegations,$deleg,$from,$to,$isCart);

if($gouver) {
    $secteurs=implode(',',$gouver);
    $request = "SELECT * FROM delegation WHERE gouv_id IN ($secteurs) ORDER BY nom";
    $request = $PDO->prepare($request);
    $request->execute();
    $listeDelegation= $request->fetchAll(PDO::FETCH_ASSOC);
    $poids=$pointsBonus->getPoids($secteurs);
} else {
    $poids=0;
    $listeDelegation=get('*','delegation',NULL,'AND',array('nom'=>'ASC'));
    $listeDelegation=$listeDelegation['reponse'];
}
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_GET['d'])) {
    $link=explode('&',$actual_link);
    unset($link[count($link)-1]);
    $actual_link=implode('&',$link);
}
$axtract=str_replace('listePointsBonus','listePbExcel',$actual_link);
if ($poids !=0 && $totalpbdate['totalPointBonus']!=0 ){
    $index=number_format(($pointsBs['totalPointBonus'] /(($poids/100)*$totalpbdate['totalPointBonus'])*100),2);
    if ($index<100 ){
        $color='#FF0000';
    }else{
        $color='#32CD32';
    }
}
//echo '<pre>';print_r($pointsBs);die;
?>
<section class="content-header">
    <h1> Liste des points bonus</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <form class="form-inline" method="GET" action="listePointsBonus">
                        <div class="form-group">
                            <label>Délégués</label>
                            <select class="form-control" name="user">
                                <option value="">Délégués</option>
                                <?foreach ($users['reponse'] as $user):?>
                                    <option <?=($deleg==$user['id'])?'selected':''; ?> value="<?=$user['id'];?>"><?=$user['Nom'].' '.$user['Prenom'];?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                        <div class="form-group" id="getSect">
                            <label>Sécteurs</label>
                            <select class="selectpicker" multiple data-live-search="true" name="secteur[]" title="Sécteur">
                                <?foreach ($sectrs['reponse'] as $sectr):?>
                                    <option <?php if($gouver && in_array($sectr['id'], $gouver))  echo "selected";?> value="<?=$sectr['id'];?>"><?=$sectr['nom'];?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                        <div class="form-group" id="getDelegation">
                            <label>Délégations</label>
                            <select id="delegationListe" name="delegation[]" class="selectpicker" multiple data-live-search="true" title="Délégations">
                                <?

                                foreach ($listeDelegation as $peos):  ?>
                                    <option <?php if($delegations && in_array($peos['id'], $delegations))  echo "selected";?> value="<?=$peos['id']?>"><?=$peos['nom']?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class='input-group date' id='from'>
                                <?php
                                if($from) {
                                    $from= date('d-m-Y', strtotime($from));
                                    $from = str_replace('-', '/', $from);
                                }
                                ?>
                                <input type='text' name="from" class="form-control" placeholder="<?=($from)?$from:'';?>" onkeydown="return false" value="<?=($from)?$from:'';?>" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class='input-group date' id='to'>
                                <?php
                                if($to) {
                                    $to= date('d-m-Y', strtotime($to));
                                    $to = str_replace('-', '/', $to);
                                }
                                ?>
                                <input type='text' name="to" class="form-control" placeholder="<?=($to)?$to:'';?>" onkeydown="return false" value="<?=($to)?$to:'';?>"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <span id="persoCheck">
                            <input type="checkbox" id="iscart" name="iscart" <?=($isCart)?'checked':''; ?>>
                            <label for="iscart">Carte bon</label>
                        </span>
                        <div class="form-group">
                            <input type="submit" value="Filtrer" class="btn btn-primary" name="submitSearch" >
                            <a href="listePointsBonus" class="btn btn-danger">Annuler</a>
                        </div>
                    </form>
                    <br/>
                    <a class="btn btn-success" href="<?=$axtract?>">Extraction EXCEL</a><br/><br/>
                </div>
                <div class="box-body">
                    <h3>Total Points Bonus National: <?=$totalpbdate['totalPointBonus'];?></h3>
                    <div style="display: inline;">
                        <span><h3>Poids du Sécteur/s: <?=str_replace(".", ",",$poids);?><span style="padding-left: 2%">Total Points Bonus du Sécteur/s: <?=$pointsBs['totalPointBonus'];?></span></h3></span>


                    </div>
                        <div>
                    <h3>index de penetration: <span style="color: <?=$color?>"><?=str_replace(".", ",",$index);?>%</span></h3></div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Secteur</th>
                            <th>Délégation</th>
                            <th>prospect</th>
                            <th>Délégué</th>
                            <th>Date</th>
                            <th>Nombre des points</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pointsBs['reponse'] as $pbs):?>
                            <?$pros=get('*','prospect',array('id='=>$pbs['id_pros']));?>
                            <tr>
                                <td><?= getinfo($pros['reponse'][0]['gouvernorat'],'gouvernerat','nom')?></td>
                                <td><?= $pbs['delegt']?></td>
                                <td><?=$pros['reponse'][0]['id'].' '.$pros['reponse'][0]['nom'].' '.$pros['reponse'][0]['prenom'];?></td>
                                <td><?=($pbs['grmuser'])?getinfo($pbs['id_demandeur'],'grm_users','Nom').' '.getinfo($pbs['id_demandeur'],'grm_users','Prenom'):getinfo($pbs['id_demandeur'],'users','Nom').' '.getinfo($pbs['id_demandeur'],'users','Prenom');?></td>
                                <td><?php
                                    $datepb= date('d-m-Y', strtotime($pbs['date_validation']));
                                    $datepb = str_replace('-', '/', $datepb);
                                    echo $datepb;?></td>
                                <td><?=$pbs['totalPointBonus'];?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-5">

            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                Affichage de <?= ($Limite > 1) ? $Limite : 1 ?>
                à <?= ($Limite + 30 < $pointsBs['total']) ? $Limite + 30 : $pointsBs['total']; echo ' de '.$pointsBs['total'].' lignes.'?>

            </div>

        </div>

        <div class="col-md-7">

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                <? pagination($pointsBs['total'], 30, $actual_link."&d=", ""); ?>
            </div>

        </div>

    </div>
</section>