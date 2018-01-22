<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 03/01/18
 * Time: 17:39
 */
$IdSup=filter_input(INPUT_GET,'IdSup',FILTER_VALIDATE_INT);
if($IdSup){
    $TotSup=$_SESSION['EchantCmd'][$IdSup];
    unset($_SESSION['EchantCmd'][$IdSup]);
    $_SESSION['TotalEchant']=$_SESSION['TotalEchant']-$TotSup;
    redirect('echantiants');
}
$annuler=filter_input(INPUT_GET,'annuler',FILTER_VALIDATE_INT);
if($annuler==1){
    unset($_SESSION['EchantCmd']);
    unset($_SESSION['TotalEchant']);$_SESSION['TotalEchant']=0;
    redirect(WEBRoot.'/gestionDesDemandes/listedmdEchantiants');
}
$GetLastEchan=$Pro->GetUsedDmdQuota($_SESSION['user']['id']);
if(!$GetLastEchan) $Month=date('Y-m');
else $Month = date('Y-m', strtotime('+1 month', strtotime($GetLastEchan)));
//var_dump($Month);
?>
<section class="content-header">
    <h1> Demande d'échantillant  </h1>
</section><!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-4">
            <div class="box box-body box-success">
                <form method="get" id="FormEchant">
                    <div class="form-group">
                        <label for="delegue">Demande Pour :</label>
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
                        <label>Pour </label>
                        <input type="month" id="MonthValue" name="pour" value="<?=$Month?>" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gamme">Gamme :</label>
                        <select id="gamme" name="gamme" class="form-control" onchange="GetProdListe()">
                            <option value="">Choix</option>
                            <?php $Gammes=get("*",'prod_categorie');
                            foreach ($Gammes['reponse'] as $gamme): ?>
                                <option value="<?=$gamme['id']?>"><?=$gamme['nom']?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div id="ProdACmd"></div>

                    <button type="button" id="BtnEchant" class="btn btn-block btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-body box-danger" id="ListeProdSessions">
                <!-- Liste by sessions :p  -->
                <?php
                $Quota=getinfo($_SESSION['user']['type'],'user_type','quota'); // général :
                if(isset($_SESSION['EchantCmd']) && count($_SESSION['EchantCmd'])):
                    echo "<h3>Total Qte : ".$_SESSION['TotalEchant']." / $Quota </h3>";
                    ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nom du produits</th>
                            <th>Qte</th>
                            <th></th>
                        </tr>
                        </thead>

                        <?foreach ($_SESSION['EchantCmd'] as $key=>$value):?>
                            <tr>
                                <td><?echo getinfo($key,'products','name')?></td>
                                <td><? echo $value?></td>

                                <td>
                                    <a href="echantiants&IdSup=<?=$key?>" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?  endforeach;?>
                    </table>

                    <br/>
                    <a href="echantiants&annuler=1" class="btn btn-danger pull-left">
                        Annuler
                    </a>
                    <a href="javascript:void(0)" class="btn btn-primary pull-right" id="BtnValiderEchant">
                        Valider ma liste
                    </a>
                <?endif;?>
            </div>
        </div>
    </div>
</section>