<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 18/10/2016
 * Time: 22:07
 */
if(filter_input(INPUT_POST,'add')){
    $name=filter_input(INPUT_POST,'DemName',FILTER_SANITIZE_STRING);
    if($name){
        add(array(
            'name'=>$name
        ), 'type_demande');
        $_SESSION['msg'] = "Type ajouter";
        $_SESSION['type'] = "alert-success";
    }
}
$Limite=filter_input(1,'d',257);
if(!$Limite) $Limite=0;
$listeDamnde=get('*','type_demande',null,'AND',array('id'=>'ASC'),array($Limite,30));
?>
<section class="content-header">
    <h1> Interface d'ajout des types de demande</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <form method="post">
        <div class="col-md-3">
            <div class="box box-primary pad">

                    <div class="form-group">
                        <label>Nom du type</label>
                        <input type="text" value="" name="DemName" class="form-control" required />
                    </div>
                <div class="form-group">
                    <label>Département</label>
                    <select name="departement" class="form-control">
                        <?$Departement=get('*','departement');
                        foreach ($Departement['reponse'] as $Dep):
                        ?>
                        <option value="<?=$Dep['id']?>"><?=$Dep['nom']?></option>
                        <?endforeach;?>
                    </select>

                </div>
                    <button type="submit" name="add" value="1" class="btn btn-block btn-primary">Ajouter un nouveau type</button>

                </div>
            </div>
    </form>
    <div class="col-md-9">
        <div class="box box-success"> 
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Département : </th>
                    <th>Nom du type</th>

                </tr>
                </thead>
                <tbody>
                <? foreach ($listeDamnde['reponse'] as $dem): ?>
                <tr>
                    <td><?=$dem['id']?></td>
                    <td><?=getinfo($dem['departement'],'departement' , 'nom')?></td>
                    <td><?=$dem['name']?></td>


                </tr>
                <?endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <div class="row">

        <div class="col-md-5">

            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                Affichage de <?= ($Limite > 1) ? $Limite : 1 ?>
                à <?= ($Limite + 30 < $listeDamnde['total']) ? $Limite + 30 : $listeDamnde['total'] ?>
                de <?= $listeDamnde['total'] ?> Demande

            </div>

        </div>

        <div class="col-md-7">

            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                <? pagination($listeDamnde['total'], 30, WEBRoot . "/General/TypeDemande&d=", ""); ?>

            </div>

        </div>

    </div>
    </section>
