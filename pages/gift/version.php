<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 17:39
 */
$id=filter_input(INPUT_GET,'prod',FILTER_VALIDATE_INT);
$v=filter_input(INPUT_GET,'version',FILTER_DEFAULT);

$Add=filter_input(INPUT_GET,'BtnPromo',257);

if($Add){

    $data=array(
        'id_art'=>$id,
        'version'=>$v,


        'created_by'=>$_SESSION['user']['id'],
        'created_at'=>date('Y-m-d')

    );
    $idStock=add($data,'grm_art_version');

}
//var_dump($Month);
?>
<section class="content-header">
    <h1> Nouvelle version  produits promotionnel  </h1>
</section><!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-4">
            <div class="box box-body box-success">
                <form method="get" id="FormEchant">
                    <div class="form-group">
                        <label>Les articles disponible : </label>
                        <select class="form-control select2" name="prod" id="prod">
                            <option value="">Choix</option>
                            <?
                            // ajouter filter par famille
                            $ListeGift = get('*', 'grm_gift',array(
                                //  'dispo =' => 1,

                                'famille = '=>5
                            ));
                            foreach ($ListeGift['reponse'] as $Gift):
                                ?>
                                <option value="<?= $Gift['id'] ?>" <?if($id==$Gift['id']){echo 'selected';}?>><?= $Gift['code_article'].' '. $Gift['titre'] ?></option>
                            <? endforeach; ?>

                        </select>
                    </div>


                    <div class="form-group">
                        <label>Nom de version</label>
                        <input class="form-control " type="text" name="version" id="version">
                    </div>



                    <button type="submit" value="1" name="BtnPromo"  id="BtnPromo" class="btn btn-block btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-body box-danger" id="ListeProdSessions">
                <span>Liste des versions :</span><br>
                <!-- Liste by sessions :p  -->
                <?php
                if($id){
                $artv= get('*','grm_art_version',array('id_art='=>$id));
                foreach ($artv['reponse'] as $artv){

                        echo $artv['version'].'<br>';

                }









                }


               ?>

            </div>
        </div>
    </div>
</section>