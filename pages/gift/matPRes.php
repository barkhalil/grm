<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 14/04/2016
 * Time: 17:53
 */
$de = filter_input(INPUT_GET, 'de');
$res = filter_input(INPUT_GET, 'res');
$a = filter_input(INPUT_GET, 'a');
$usr = filter_input(INPUT_GET, 'user');
//if(!$de) $de = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01'))));
if(!$de) $de = date('Y-m-01');
if(!$a) $a = date('Y-m-d');
if(!$res) $res=6;


$ListeUsers = get("*", 'delRes', array('res='=>$res), 'AND', array('id_Del' => 'ASC'));

$ListeUser=$ListeUsers['reponse']

?>
<section class="content-header">
    <h1 class="">Cartographie Réseau</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-body table-responsive">

                <div class="box-header">
                    <form method="get" class="form-inline pull-left">

                        <select class="form-control" name="res" required>
                            <option value="6" selected>BU 1</option>
                            <option value="7">BU 2</option>
                            <option value="8">BU 3</option>
                            <option value="9">BU 4</option>
                        </select>

                        <div class="form-group"><label>De </label><input type="date" name="de" class="form-control"
                                                                         required value="<?= $de ?>"></div>
                        <div class="form-group"><label>A </label><input type="date" name="a" class="form-control"
                                                                        required value="<?= $a; ?>"></div>

                        <button type="submit" name="Filterdate" value="1" class="btn btn-danger">Filtre</button>
                    </form>


                </div>
                <div class="clearfix"></div>
                <br/>
                <button type="button"  onclick="toexcel()"
                        class="btn btn-primary pull-right" >Extraction</button>
                <div class="clearfix"></div>


                <table class="table table-bordered" name="venteanim" id="venteanim" >
                    <thead>
                    <tr>
                        <td></td>
                        <?php  foreach ($ListeUser as $userInfo){
                            $usr=$userInfo['id_Del'];
                            ?>
                            <td>
                                <?=getinfo($userInfo['id_Del'],'users','nom').' '.getinfo($userInfo['id_Del'],'users','prenom');?>
                            </td>




                        <?php }?>
                    </tr>
                    </thead>




                        <tbody>
                        <?
                        $ListeGift = get('*', 'grm_gift',array(
                        //  'dispo =' => 1,

                        'famille = '=>5
                        ));
                        foreach ($ListeGift['reponse'] as $Gift):
                        ?>
                        <tr>
                            <td>
                                <?=$Gift['titre']?>

                            </td>
                            <?php  foreach ($ListeUser as $userInfo){
                                $usr=$userInfo['id_Del'];
                                ?>
                                <td style="text-align: center">
                                    <? $nb=getProdDm($de,$a,$usr,$Gift['id']);
                                    if($nb){
                                        echo $nb;
                                    }else{
                                        echo '0';
                                    }

                                    ?>
                                </td>




                            <?php ;}?>
                        </tr>
                         <? endforeach; ?>



                        </tbody>

                </table>

            </div>
        </div>
    </div>
</section>
