<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 12/02/18
 * Time: 15:56
 */
$Limite=filter_input(INPUT_GET,'d',257);
if(!$Limite) $Limite=0;
$submitSearch=filter_input(INPUT_GET,'submitSearch',FILTER_DEFAULT);
if($submitSearch) {
    $dateBn=filter_input(INPUT_GET,'dateBn',FILTER_DEFAULT);
    $ref=filter_input(INPUT_GET,'ref',FILTER_DEFAULT);
    $userInser=filter_input(INPUT_GET,'user',FILTER_VALIDATE_INT);
    $where=array();
    if($dateBn) {
        $dateBn = str_replace('/', '-', $dateBn);
        $dateBn= date('Y-m-d', strtotime($dateBn));
        $where["date_bn_entr ="]=$dateBn;//echo $dateBn;die;
        //$link.="&dateBn=$dateBn";
    }
    if($ref) {
        $where['reference like']='%'.$ref.'%';
        //$link.="&ref=$ref";
    }
    if($userInser) {
        $where['created_by=']=$userInser;
        //$link.="&user=$userInser";
    }
    //echo '<pre>';print_r($where);die;
    if($where) {
        $bnsEntr=get('*','prod_ref_stock',$where,'AND',array('created_at'=>'DESC'),array($Limite,30));
    } else {
        $bnsEntr=get('*','prod_ref_stock',NULL,'AND',array('created_at'=>'DESC'),array($Limite,30));
    }

    //echo'<pre>';print_r($bnsEntr);die;
} else {
    $bnsEntr=get('*','prod_ref_stock',NULL,'AND',array('created_at'=>'DESC'),array($Limite,30));
}
//echo'<pre>';print_r($bnsEntr);die;
$users=get('*','grm_users',array('active>'=>0),'AND',array('Nom'=>'DESC'));
?>
<section class="content-header">
    <h1 class="pull-left">Bons d'entrée</h1>
    <a href="<?=WEBRoot?>/products/bonEntree" class="btn btn-primary pull-right">
        Nouveau
    </a>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body">
                <form class="form-inline" action="listeBnEntree" method="get">
                        <div class="form-group">
                            <label>Date de Bon</label>
                            <?php
                            if($dateBn) {
                                $dateBn= date('d-m-Y', strtotime($dateBn));
                                $dateBn = str_replace('-', '/', $dateBn);
                            }
                            ?>
                            <input type='text' id="datePicker" class="form-control" name="dateBn" placeholder="Date..." onkeydown="return false" value="<?=($dateBn)?$dateBn:'';?>"/>
                        </div>
                        <div class="form-group">
                            <label>Référence de Bon</label>
                            <input value="<?=($ref)?$ref:''; ?>" type='text'  class="form-control" name="ref" placeholder="Référence..."/>
                        </div>
                        <div class="form-group">
                            <label>Saisie par:</label>
                            <select class="form-control" name="user">
                                <option value="">Saisie par...</option>
                                <?foreach ($users['reponse'] as $user):?>
                                    <option <?=($userInser==$user['id'])?'selected':''; ?> value="<?=$user['id'];?>"><?=$user['Nom'].' '.$user['Prenom'];?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Filtrer" class="btn btn-primary" name="submitSearch" >
                            <a href="listeBnEntree" class="btn btn-danger">Annuler</a>
                        </div>
                </form><br/>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Référence</th>
                            <th>Date</th>
                            <th>Date de saisie</th>
                            <th>Par</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bnsEntr['reponse'] as $bn):?>
                        <tr>
                            <td><?='BN'.$bn['id'].'/'.date('Y',strtotime($bn['created_at']));?></td>
                            <td><?=$bn['reference'];?></td>
                            <td><?php
                                $dateBn= date('d-m-Y', strtotime($bn['date_bn_entr']));
                                $dateBn = str_replace('-', '/', $dateBn);
                                echo $dateBn;?></td>
                            <td><?php
                                $dateInsert= date('d-m-Y', strtotime($bn['created_at']));
                                $dateInsert = str_replace('-', '/', $dateInsert);
                                echo $dateInsert;?></td>
                            <td><?=getinfo($bn['created_by'],'grm_users','Nom').' '.getinfo($bn['created_by'],'grm_users','Prenom');?></td>
                            <td>
                                <a href="detailsBnEntr&idBn=<?=$bn['id']?>" class="btn btn-primary" data-toggle="tooltip" title="Visualiser">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-5">

            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                Affichage de <?= ($Limite > 1) ? $Limite : 1 ?>
                à <?= ($Limite + 30 < $bnsEntr['total']) ? $Limite + 30 : $bnsEntr['total'] ?>
                de <?= $bnsEntr['total'] ?> Bons d'entrée

            </div>

        </div>

        <div class="col-md-7">
            <?$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if(isset($_GET['d'])) {
                $link=explode('&',$actual_link);
                unset($link[count($link)-1]);
                $actual_link=implode('&',$link);
            }
            ?>

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                <? pagination($bnsEntr['total'], 30, $actual_link."&d=", ""); ?>
            </div>

        </div>

    </div>
</section>
