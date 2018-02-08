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
                <h3 class="" >Aprés validation aucune modification n'est autorisée</h3>
                <h3 class="" >Avant validation aucune modification n'est réelement enregistrée</h3>
                <form method="post">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Réference du bon d'entrée: </label>
                            <input type="text" placeholder="Réference..." class="form-control" name="ref"  required>
                        </div>
                        <div class="form-group" >
                            <label>Fournisseur: </label>
                            <select name="fournisseur" class="form-control"  required>
                                <option value="">Fournisseur</option>
                                <option value="Dépôt">Dépôt</option>
                                <option value="laboratoire">laboratoire</option>
                            </select>
                        </div>
                        <div class="form-group" >
                            <label>Date: </label>
                            <input type='text' id="datePicker" class="form-control" name="date" placeholder="Date..." onkeydown="return false" required/>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success btn-block" id="validBnEntr" disabled>
                    </div>
                    <div class="col-sm-6">
                        <h3>Liste des produits entrées</h3>
                        <ul class="prodInsertListe">
                        </ul>
                    </div>
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
                            <input type="text" id="searchCode" onkeyup="myFunction('searchCode',0)" placeholder="Recherche par code.." class="form-control">
                        </div>
                    </div>
                    <div id="listeSubmitProds">
                    </div>
                </div>
                <div class="btn btn-success loadAll">Charger tous</div>
                <div class="btn btn-success loadMore">Charger plus</div>
                <div class="btn btn-warning showLess">Montre moins</div>
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
                        <?$i=0; foreach ($products as $product): $i++;
                            ($i>3)?$class='hideIt':'';
                        ?>
                        <tr class="<?=$class?> prodLigne">
                            <td><?=$product['code_article'];?></td>
                            <td id="<?=$product['id'];?>"><?=$product['name'];?></td>
                            <td style="white-space: normal"><?=$product['description'];?></td>
                            <td class="<?=$product['id'];?>"><?=$product['qte'];?></td>
                            <td>
                                <div class="form-group editStock">
                                    <label>La quantité:</label><br/>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 " type="text" name="idPros" placeholder="Quantité" class="full-height qteAdded" rel="<?=$product['id'];?>">
                                    <button class="btn btn-success validQte" disabled>VALIDER</button>
                                </div>
                            </td>
                        </tr>
                        <?endforeach;?>
                    </tbody>
                </table>
                <div class="btn btn-success loadAll">Charger tous</div>
                <div class="btn btn-success loadMore">Charger plus</div>
                <div class="btn btn-warning showLess">Montre moins</div>
            </div>
        </div>
    </div>
</section>