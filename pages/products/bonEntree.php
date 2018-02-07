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
    <h1>Gestion des bons d'entrée</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">
                <table class="table table-bordered sameline-btns">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach ($products as $product):?>
                        <tr>
                            <td><?=$product['code_article'];?></td>
                            <td><?=$product['name'];?></td>
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