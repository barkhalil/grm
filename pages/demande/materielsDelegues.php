<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 04/01/18
 * Time: 10:02
 */
//ALTER TABLE `materiel_deleg` ADD `created_by` INT NULL AFTER `id_deleg`;
if(filter_input(0,'Add',257)):
// 1er test si ensemble de ponts des produits <= total point
    $Products=filter_input(INPUT_POST,'prodValue',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    $delegue=filter_input(INPUT_POST,'delegue',FILTER_VALIDATE_INT);
    // 2 étape (ajouter la demande et ajouter les produists :
    $dataDemande=array(
        'id_deleg'=>$delegue,
        'created_by'=>$_SESSION['user']['id'],
        'observation'=>filter_input(0,'observation_client',FILTER_SANITIZE_STRING),
        'date_dmd'=>date("Y-m-d"),
        'etat'=>0
    );
    $IdDEmande=add($dataDemande,'materiel_deleg');
    if($IdDEmande){
        foreach ($Products as $key => $value):
            add(array(
                'id_dmd'=>$IdDEmande,
                'id_prod'=>$key,
                'qte'=>$value
            ), 'materiel_deleg_details');
        endforeach;
        $_SESSION['msg'] = "Votre demande est sauvegarder";
        $_SESSION['type'] = "alert-success";
        redirect(WEBRoot.'/gestionDesDemandes/listeDmdMaterelDeleg');
    }else{
        $_SESSION['msg'] = "Votre demande n'est pas sauvegarder";
        $_SESSION['type'] = "alert-danger";
    }
endif;
?>
<section class="content-header">
    <h1>matériels délégué</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <form class="" method="post">
            <div class="col-md-6">
                <div class="box box-comment box-body">
                    <div class="form-group">
                        <label>Demande pour: </label>
                        <select name="delegue" id="delegue" class="form-control"  required  >
                            <option value=""></option>
                            <?
                            $users= get('*','users',array('active>='=>1),'AND');
                            foreach ($users['reponse'] as $user):?>
                                <option value="<?=$user['id']?>"> <?=$user['Nom'].' '.$user['Prenom'];?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div id="ProdInfos">
                        <div class="form-group">
                            <label>Les articles disponible : </label>
                            <select class="form-control select2" name="cadeaux" id="ProdSelect" onchange="AddPRodDemande()" required>
                                <option value="">Choix</option>
                                <?
                                // ajouter filter par famille
                                $ListeGift = get('*', 'grm_gift',array(
                                    //  'dispo =' => 1,
                                    'qte >=' => 1,
                                    'famille = '=>6
                                ));
                                foreach ($ListeGift['reponse'] as $Gift):
                                    ?>
                                    <option value="<?= $Gift['id'] ?>" ><?= $Gift['titre'] ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div id="ProdListeINp"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success box-body">
                    <div class="form-group">
                        <label>Observation demandeur : </label>
                        <textarea class="form-control" name="observation_client" rows="8">RAS</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-info box-footer">
                    <button type="submit" name="Add" value="1" class="btn btn-primary pull-right">Ajouter votre demande</button>
                </div>
            </div>
        </form>
    </div>
</section>