<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 07/02/18
 * Time: 18:01
 */
$products=$ProdClass->getAll();
//echo '<pre>';print_r($products);die;
?>
<section class="content-header">
    <h1>Gestion des bons d'entrée</h1><br/>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-warning box-body">
                <form method="post">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Réference du bon d'entrée: </label>
                            <input type="text" placeholder="Réference..." class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group" >
                            <label>Date: </label>
                            <input type='text' id="datePicker" class="form-control" name="date" placeholder="Date..." onkeydown="return false" required/>
                        </div>
                    </div>
                    <input type="submit" value="Valider" class="btn btn-success" style="margin: 25px auto;" >
                </form>
            </div>
        </div>
    </div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Recherche par Code</label>
                            <input type="text" id="searchCode" onkeyup="myFunction('searchCode')" placeholder="Recherche par code.." class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Recherche par désignation</label>
                            <input type="text" id="serchName" onkeyup="myFunction('serchName',1)" placeholder="Recherche par désignation.." class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Filtrer par:</label>
                            <select class="form-control">
                                <option>Code</option>
                                <option>Désignation</option>
                                <option>Quantité</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered sameline-btns" id="myTable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Désignation <i class="fa fa-sort"></i></th>
                            <th>Déscription</th>
                            <th>Quantité</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach ($products as $product):?>
                        <tr>
                            <td><?=$product['code_article'];?></td>
                            <td><?=$product['name'];?></td>
                            <td style="white-space: normal"><?=$product['description'];?></td>
                            <td><?=$product['qte'];?></td>
                            <td>
                                <div class="form-group" id="editStock">
                                    <label>La quantité:</label><br/>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" type="text" name="idPros" placeholder="Quantité" class="full-height">
                                    <button class="btn btn-success">VALIDER</button>
                                </div>
                            </td>
                        </tr>
                        <?endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>