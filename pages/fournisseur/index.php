<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 14/12/2016
 * Time: 19:03
 */
?>
<section class="content-header">
    <h1 class="pull-left"> Liste des fournisseur</h1>
    <a href="addFourni" class="btn btn-primary pull-right">Ajouter un fournisseur</a>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-md-12">
    <div class="box box-danger">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Nom</th>
                <th>Contact</th>
                <th>Fax</th>
                <th>Tel.</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <? $four=get('*','grm_fournisseur');
            foreach ($four['reponse'] as $Fourni):
            ?><tr>
                <td><?=$Fourni['id']?></td>
                <td><?=$Fourni['code']?></td>
                <td><?=$Fourni['nom']?></td>
                <td><?=$Fourni['contact']?></td>
                <td><?=$Fourni['fax']?></td>
                <td><?=$Fourni['tel']?></td>
                <td>
                    <a href="editFourni&id=<?=$Fourni['id']?>" class="btn btn-success">
                        <i class="fa fa-pencil"></i>
                    </a>
                </td>
            </tr>

            <?endforeach;?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
</section>
