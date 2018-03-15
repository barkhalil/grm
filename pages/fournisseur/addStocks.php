<?
/*
 * ajouter un cadeau
 */
$id=filter_input(1,'id',FILTER_VALIDATE_INT);
$referrer=filter_input(INPUT_POST,'referrer',FILTER_DEFAULT);
//echo $referrer;die;
$serCh=false;
if(!$id){
    //pas d'id test s'i c'est un text  :
    $id =filter_input(1,'id',FILTER_DEFAULT);
    if(!$id){
        $_SESSION['msg'] = "Le produits rechercher est introuvable";
        $_SESSION['type'] = "alert-danger";
        redirect($referrer);
    }else{
        $serCh=true;
    }

}
if(!$serCh ){ // id int
    $Cadeaux=get("*",'grm_gift',array('id='=>$id));
    $CadeauxDetails=$Cadeaux['reponse'][0];
}else{ // recherche par String
    $Cadeaux=get("*",'grm_gift',array('code_article = '=>$id,'bare_code='=>$id),'OR');
    $CadeauxDetails=$Cadeaux['reponse'][0];
   // print_r($Cadeaux);
    if(count($CadeauxDetails)<=0){
        $_SESSION['msg'] = "Article non Trouver !!";
        $_SESSION['type'] = "alert-danger";
         redirect($referrer);
    }
    $id=$CadeauxDetails['id'];
}
if(filter_input(INPUT_POST,'addGift',FILTER_VALIDATE_INT)):
    $newQte=filter_input(0,'Newqte',257);
    if(filter_input(0,'Newqte',257)>0) {
        $data=array(
            'qte'=>filter_input(0,'Newqte',257),
            'qte_ex'=>$CadeauxDetails['qte'],
            'prod'=>$id,
            'fournisseur'=>filter_input(0,'four',257),
            'created_by'=>$_SESSION['user']['id'],
            'paht'=>filter_input(0,'paht',516),
            'pvht'=>filter_input(0,'pvht',516),
            'pvttc'=>filter_input(0,'pvttc',516),
            'ref'=>filter_input(0,'ref',516),
            'validation'=>0,
            'system_date'=>date('Y-m-d H:s')
        );
        $idStock=add($data,'grm_stock');
        if($idStock){
            $_SESSION['msg'] = "Stock et prix ajouter";
            $_SESSION['type'] = "alert-success";
            //update stock prod promotionnel :
            $valIni=$CadeauxDetails['qte'];
            $valFinal = $newQte + $valIni;
            update(
                $id,array('qte'=>$valFinal),'grm_gift'
            );
            redirect('../gift/ListeCadeaux');
        }else{
            $_SESSION['msg'] = "problème erreur mochkla wa 7lili";
            $_SESSION['type'] = "alert-danger";
        }
    } else {
        redirect($referrer);
    }
endif;

?>
<section class="content-header">
    <h1> Ajouter un cadeaux</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <form method="post" class="returPage">

            <div class="col-md-4 ">
                <div class="box box-primary box-body">
                    <div class="form-group">
                        <label>Titre/Nom du cadeaux</label>
                        <input type="text" value="<?=$CadeauxDetails['titre']?>" name="titre" class="form-control" readonly>
                    </div>
                    <div class="form-group ">
                        <label>Code à bare </label>
                        <input type="text" value="<?=$CadeauxDetails['bare_code']?>" name="bare_code" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nombre de point bonus</label>
                        <input type="number" value="<?=$CadeauxDetails['point_bonus']?>" min="0" step="1" name="point_bonus" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Quantité : </label>
                        <input type="number" min="0" step="1" value="<?=$CadeauxDetails['qte']?>" name="qte" class="form-control" readonly>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-success box-body">
                    <div class="form-group">
                        <label>PAHT</label>
                        <input type="text" name="paht" value="<?=$CadeauxDetails['paht']?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>PVHT</label>
                        <input type="text" name="pvht" value="<?=$CadeauxDetails['pvht']?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>PVTTC</label>
                        <input type="text" name="pvttc" value="<?=$CadeauxDetails['pvttc']?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nouvelle Quantité : </label>
                        <input type="number" min="0" step="1" value="" name="Newqte" class="form-control" required >
                    </div>
                    <div class="form-group">
                        <label>Ref.</label>
                        <input type="text" name="ref" value="" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

                <div class="box box-primary box-body">


                    <div class="form-group">
                        <label>Famille</label>
                        <select name="famille" class="form-control" readonly disabled>
                            <option value=""></option>
                            <? $Familes=get("*",'grm_gift_family');
                            foreach ($Familes['reponse'] as $fami):
                                ?>
                                <option value="<?=$fami['id']?>"  <? if($CadeauxDetails['famille']==$fami['id']) echo 'selected="selected"'?> > <?=$fami['nom']?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description : </label>
                        <textarea name="description" cols="8" rows="5" class="form-control" readonly><?=$CadeauxDetails['description']?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fournisseur</label>
                        <select name="four" class="form-control" required>
                            <option value="">Liste</option>
                            <? $four=get('*','grm_fournisseur',array('etat>'=>0));
                            foreach ($four['reponse'] as $Fourni):
                                ?>
                                <option value="<?=$Fourni['id']?>" > <?=$Fourni['code']?></option>
                            <?endforeach;?>
                        </select>
                    </div>


                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-danger">
                    <br/>
                    <button type="submit" value="1" name="addGift" class="btn btn-github pull-right pad"> Ajouter </button>
                    <a href="#" class="btn btn-danger pull-left pad" onclick="history.go(-1);"> Annuler </a>
                    <br/><div class="clearfix"></div><br/>
                </div></div>



        </form>

    </div>
</section>