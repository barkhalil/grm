<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 14/12/2016
 * Time: 20:21
 */
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
            'contact'=>$contact,
            'cree_par'=>$_SESSION['user']['id']
        ];
        $id=add($data,'grm_fournisseur');
        if($id){
            $_SESSION['msg'] = "Fournisseur ajouter";
            $_SESSION['type'] = "alert-success";
            redirect('index');
        } else {
            $_SESSION['msg'] = "Une Erreur c'est produite";
            $_SESSION['type'] = "alert-danger";
            redirect('index');
        }
    }
}
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" name="contact" placeholder="Nom du contact" class="form-control" required value="<?=$DEt['contact']?>">
                            </div>
                            <div class="form-group">
                                <label>Fax</label>
                                <input type="text" name="fax" placeholder="Fax du fournisseur" class="form-control" required value="<?=$DEt['fax']?>">
                            </div>
                            <div class="form-group">
                                <label>Tel.</label>
                                <input type="text" name="tel" placeholder="Tel du fournisseur" class="form-control" required value="<?=$DEt['tel']?>">
                            </div>
                            <br/>
                            <button type="submit" name="AddFourni" class="btn btn-primary" value="1">Ajouter un fournisseur</button>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

