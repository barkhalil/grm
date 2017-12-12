<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 06/03/2017
 * Time: 12:56
 */
if(filter_input(INPUT_POST, 'CatAdd')):
    if (add(array(
        'nom'=>filter_input(INPUT_POST,'catName' )
    ), 'prod_categorie')) {
        $_SESSION['msg'] = "L'ajout du catégorie est bien passer";
        $_SESSION['type'] = "alert-success";
    } else {
        $_SESSION['msg'] = "Une Erreur c'est produite";
        $_SESSION['type'] = "alert-danger";
    }

endif;
$ListeOfGamme=get("*",'prod_categorie');

?>
<section class="content-header">
    <h1>Gestion des Gamme </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
       <div class="col-md-4">
           <div class="box box-success box-body table-responsive">
           <form method="post">
               <div class="form-group">
                   <label>Gamme</label>
                   <input type="text" name="catName" value="" class="form-control" required>
               </div>
               <button type="submit" name="CatAdd" value="1" class="btn btn-microsoft">Ajouter une Gamme</button>
           </form>
           </div>
       </div>
       <div class="col-md-8">
           <div class="box box-success box-body table-responsive">
           <table class="table">
               <thead>
               <th>#</th>
               <th>Nom</th>
               <th>Action</th>
               </thead>
               <tbody>
               <? foreach ($ListeOfGamme['reponse'] as $gamme): ?>
               <tr>
                   <td><?=$gamme['id']?></td>
                   <td><?=$gamme['nom']?></td>
                   <td></td>
               </tr>
                <?endforeach;?>
               </tbody>
           </table>
       </div>
       </div>
    </div>
</section>