<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 18/09/2016
 * Time: 12:20
 */
session_start();
require '../Connextion.php';
require '../librairie/loadall.php';
$de=filter_input(INPUT_POST,'de');
$a=filter_input(INPUT_POST,'a');
$usr=filter_input(INPUT_POST,'user');
$SpecFilter = filter_input(INPUT_POST, 'Spec',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$SectFilter = filter_input(INPUT_POST, 'secteur', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$Potentiel = filter_input(INPUT_POST, 'Potentiel', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if(!$de)$de=date('Y-m-d',strtotime('-1 month',strtotime(date('Y-m-01'))));
if(!$a)$a=date('Y-m-01');
$where=array('public='=>1);

?>
<table class="table table-bordered">
    <thead>
    <tr>
        <td>DM </td>
        <td>Nbre de jours ovrable </td>
        <td>Nbre de jours de repos </td>
        <td>Nbre de de jours sur terrain</td>
        <td>Nbre de clients affecter </td>
        <td>Nbre de visite au moins 1 </td>
        <td>Nbre de visite au moins 2 </td>
        <td>Nbre de visite au moins 3 </td>
        <td>Nbre de visite au moins 4 </td>
        <td>Nbre de visite 5+ </td>
    </tr>
    </thead>
    <tbody>
    <?
    $dEnd = new DateTime("$a 00:00");
    $dStart  = new DateTime("$de 00:00");
    $dDiff = $dStart->diff($dEnd);
    // echo  $j= $dDiff->format('%R%a'); // use for point out relation: smaller/greater
    $jTravaille=$dDiff->days;
    $ListeUser=get('*','users',array('type>'=>2,'type<='=>4));
   // $t=1;
    $GTot2=$GTot1=$GTot3=$GTot4=$GTot5=$GTot6=0; 

    foreach($ListeUser['reponse'] as $user):
       // if($t+1 >2) break;
        if($_SESSION['user']['type']<2 || $_SESSION['user']['id']==$user['id']):
            $Tot1=$Tot2=$Tot3=$Tot4=$Tot5=$Tot6=0;
            $jours=$joursOuv=0;
            ?>
            <tr class="<? if(!$user['active']) echo 'bg-red-gradient'?>">
                <td><?=$user['Nom'].' '.$user['Prenom'] ?></td>
                <td><?=$joursOuv+getNbreDayOuv($de,$a)?></td>
                <td><?=$jours=getNbreDayAbs($de,$a,$user['id'])?></td>
                <td><?=$jTravaille-$joursOuv-$jours?></td>
                <td>
                    <?=count( $LiProuser=GetTotalProspectDel($user['id']));?>
                </td>
                <td><?=$Tot1=count(getNbrVi($de,$a,$user['id'],0));?></td>
                <td><?=$Tot3=count(getNbrVi($de,$a,$user['id'],1));?> </td>
                <td><?=$Tot4=count(getNbrVi($de,$a,$user['id'],2));?> </td>
                <td><?=$Tot5=count(getNbrVi($de,$a,$user['id'],3));?> </td>
                <td><?=$Tot6=count(getNbrVi($de,$a,$user['id'],4));?> </td>



            </tr>
            <?
               $GTot1+=$Tot1;
            $GTot2+=$Tot2;
            $GTot3+=$Tot3;
            $GTot4+=$Tot4;
            $GTot5+=$Tot5;
            $GTot6+=$Tot6;
            // $GTot+=($Tot1+$Tot2);
            ?>

        <?endif;endforeach;?>
    </tbody>
    <tfoot>
    <tr>
        <td>Total :</td>
        <td></td>
        <td></td>
        <td></td>
        <td><?//=$GTot1?></td>
        <td><?=$GTot2?></td>
        <td><?=$GTot3?></td>
        <td><?=$GTot4?></td>
        <td><?=$GTot5?></td>
        <td><?=$GTot6?></td>
    </tr>
    </tfoot>
</table>
