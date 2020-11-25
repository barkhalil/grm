<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 16:39
 */
$IdSup=filter_input(INPUT_GET,'IdSup',FILTER_VALIDATE_INT);
if($IdSup){
    $TotSup=$_SESSION['PromoCmd'][$IdSup];
    unset($_SESSION['PromoCmd'][$IdSup]);
    redirect('materielPromotionnel');
}
$annuler=filter_input(INPUT_GET,'annuler',FILTER_VALIDATE_INT);
if($annuler==1){
    unset($_SESSION['PromoCmd']);
    unset($_SESSION['delegue']);
    redirect(WEBRoot.'/gestionDesDemandes/dmdPromotionnel');
}
?>
<section class="content-header">
    <h1> Demande de produits promotionnel  </h1>
</section><!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-4">
            <div class="box box-body box-success">
                <form method="get" id="FormEchant">
                    <div class="form-group">
                        <label>Demande pour</label>
                        <select name="delegue" id="delegue" class="form-control"  required  >
                            <option value=""></option>
                            <?
                            $users= get('*','users',array('active>='=>1),'AND');
                            foreach ($users['reponse'] as $user):?>
                                <option value="<?=$user['id']?>" <?= ($_SESSION['delegue']==$user['id'])? 'selected':''; ?>> <?=$user['Nom'].' '.$user['Prenom'];?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Les articles disponible : </label>
                        <select class="form-control select2" onchange="getversion()" name="prodListe" id="ProdSeaC">
                            <option value="">Choix</option>
                            <?
                            // ajouter filter par famille
                            $ListeGift = get('*', 'grm_gift',array(
                                //  'dispo =' => 1,
                                'qte >=' => 1,
                                'famille = '=>5
                            ));
                            foreach ($ListeGift['reponse'] as $Gift):
                                ?>
                                <option value="<?= $Gift['id'] ?>" ><?= $Gift['code_article'].' '. $Gift['titre'] ?></option>
                            <? endforeach; ?>

                        </select>
                    </div>
                    <div class="form-group">


                        <label>Version</label>

                        <select name="version" class="form-control"  id="version" required>



                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qte">Qte</label>
                        <input type="number" id="qte" name="qte" value="1" step="1" min="1" class="form-control">
                    </div>

                    <button type="button" id="BtnPromo" class="btn btn-block btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-body box-danger" id="ListeProdSessions">
                <!-- Liste by sessions :p  -->
                <?php
                if(isset($_SESSION['PromoCmd']) && count($_SESSION['PromoCmd'])):
                    ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nom du produits</th>
                            <th>Qte</th>
                            <th></th>
                        </tr>
                        </thead>

                        <?foreach ($_SESSION['PromoCmd'] as $key=>$value):?>
                            <tr>
                                <td><?echo getinfo($key,'grm_gift','titre')?></td>
                                <td><? echo $value?></td>

                                <td>
                                    <a href="materielPromotionnel&IdSup=<?=$key?>" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?  endforeach;?>
                    </table>

                    <br/>
                    <a href="materielPromotionnel&annuler=1" class="btn btn-danger pull-left">
                        Annuler
                    </a>
                    <a href="javascript:void(0)" class="btn btn-primary pull-right" id="BtnValiderPromo">
                        Valider ma liste
                    </a>
                <?endif;?>
            </div>
        </div>
    </div>
</section>