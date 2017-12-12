<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 14/12/2016
 * Time: 20:21
 */
$idForuni=filter_input(INPUT_GET,'id',257);
if(!$idForuni) redirect('index');
$AddFourni=filter_input(INPUT_POST,'AddFourni',257);
if($AddFourni){
    $nom=filter_input(INPUT_POST,'nom',FILTER_SANITIZE_STRING);
    $code=filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
    $fax=filter_input(INPUT_POST,'fax',FILTER_SANITIZE_STRING);
    $tel=filter_input(INPUT_POST,'tel',FILTER_SANITIZE_STRING);
    $contact=filter_input(INPUT_POST,'contact',FILTER_SANITIZE_STRING);
    if($nom){
        $data=[
          'nom'=>$nom,
          'code'=>$code,
          'fax'=>$fax,
          'tel'=>$tel,
          'contact'=>$contact
        ];
        $id=update($idForuni,$data,'grm_fournisseur');
        if($id){
            $_SESSION['msg'] = "Fournisseur modifier";
            $_SESSION['type'] = "alert-success";
            redirect('index');
        } else {
            $_SESSION['msg'] = "Une Erreur c'est produite";
            $_SESSION['type'] = "alert-danger";
            redirect('index');
        }
    }
}
$Fourni=get('*','grm_fournisseur',array('id='=>$idForuni));
$DEt=$Fourni['reponse'][0];
?>
<section class="content-header">
    <h1 class="pull-left"> Ajouter un fournisseur</h1>
    <a href="index" class="btn btn-dropbox pull-right">Liste des fournisseur</a>
    <div class="clearfix"></div>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <form method="post" class=" ">
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>RC</label>
                                <input type="text" name="nom" placeholder="Nom du fournisseur" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" placeholder="Code du fournisseur" class="form-control" required value="<?=$DEt['code']?>">
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" placeholder="Nom du fournisseur" class="form-control" required value="<?=$DEt['nom']?>">
                            </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" name="contact" placeholder="Nom du contact" class="form-control" required value="<?=$DEt['contact']?>">
                            <div class="form-group">
                                <label>Fax</label>
                                <input type="text" name="fax" placeholder="Fax du fournisseur" class="form-control" required value="<?=$DEt['fax']?>">
                            </div>
                            <div class="form-group">
                                <label>Tel.</label>
                                <input type="text" name="tel" placeholder="Tel du fournisseur" class="form-control" required value="<?=$DEt['tel']?>">
                            </div>
                            <br/>
                            <button type="submit" name="AddFourni" class="btn btn-warning pull-right" value="1">Modifier un fournisseur</button>
                            <div class="clearfix"></div>
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

