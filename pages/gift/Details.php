<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 10/11/2016
 * Time: 11:35
 */
$id=filter_input(1,'id',257);
if(!$id){
    $_SESSION['msg'] = "Une Erreur c'est produite";
    $_SESSION['type'] = "alert-danger";
    redirect('ListeCadeaux');
}
$Cadeaux=get("*",'grm_gift',array('id='=>$id));
$CadeauxDetails=$Cadeaux['reponse'][0]; 
?>
<section class="content-header">
    <h1> Produit :  <?=$CadeauxDetails['titre']?></h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <div class="box box-success box-body">
            <div class="col-md-6">
                <h3> Produit :  <?=$CadeauxDetails['titre']?></h3>
                <p> Description : <br/>
                    <?=$CadeauxDetails['description']?>
                </p>
                <ul>
                    <li>Point Bonus :  <?=$CadeauxDetails['point_bonus']?></li>
                    <li>Disponible :  <?=$CadeauxDetails['dispo']==1 ? "Oui" : "Non"?></li>
                    <li>Quantité :  <?=$CadeauxDetails['qte']?></li>
                    <li>Quantité utiliser :  <?=$CadeauxDetails['qte_utiliser']?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3> Information</h3>
                <ul>
                    <li>Code  : <?=$CadeauxDetails['bare_code']?></li>
                    <li>Sérialisable :  <?=$CadeauxDetails['serialisable']==1 ? "Oui" : "Non"?></li>
                    <li>PAHT  : <?=$CadeauxDetails['paht']?></li>
                    <li>PVHT  : <?=$CadeauxDetails['pvht']?></li>
                    <li>PVTTC : <?=$CadeauxDetails['pvttc']?></li>
                    <li>Stock Alert : <?=$CadeauxDetails['stoc_alert']?></li>
                </ul>
            </div>

            </div>
            </div>
        </div>
    </section>
