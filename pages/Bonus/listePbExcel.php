<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 26/04/2018
 * Time: 12:28
 */
$users=get('*','users',array('active='=>1),'AND',array('Nom'=>'ASC'));
$sectrs=get('*','gouvernerat',NULL,'AND',array('nom'=>'ASC'));
$deleg=$gouver=$delegations=$from=$to=$isCart=NULL;
$deleg=filter_input(INPUT_GET,'user',FILTER_VALIDATE_INT);
$gouver=$_GET['secteur'];//filter_input(INPUT_GET,'secteur',FILTER_VALIDATE_INT);
$delegations=$_GET['delegation'];
$from=filter_input(INPUT_GET,'from',FILTER_DEFAULT);
$to=filter_input(INPUT_GET,'to',FILTER_DEFAULT);
$isCart=filter_input(INPUT_GET,'iscart',FILTER_DEFAULT);//echo $isCart;die;
if($from && $to) {
    $from = str_replace('/', '-', $from);
    $from= date('Y-m-d', strtotime($from));
    $to = str_replace('/', '-', $to);
    $to= date('Y-m-d', strtotime($to));
}
//echo $from.' '.$to;
$pointsBs=$pointsBonus->AllPBpros(NULL,NULL,$gouver,$delegations,$deleg,$from,$to,$isCart);
//echo '<pre>';print_r($pointsBs['reponse']);die;
$excel ="<table border='1px'>
<tr>
    <th>Secteur</th>
    <th>Délégation</th>
    <th>prospect</th>
    <th>Délégué</th>
    <th>Date</th>
    <th>Nombre des points</th>
</tr>";

foreach ($pointsBs['reponse'] as $pbs):
    $pros=get('*','prospect',array('id='=>$pbs['id_pros']));
    $datepb= date('d-m-Y', strtotime($pbs['date_validation']));
    $datepb = str_replace('-', '/', $datepb);
    $user=($pbs['grmuser'])?getinfo($pbs['id_demandeur'],'grm_users','Nom').' '.getinfo($pbs['id_demandeur'],'grm_users','Prenom'):getinfo($pbs['id_demandeur'],'users','Nom').' '.getinfo($pbs['id_demandeur'],'users','Prenom');
    // $excel .="Nom \t Prénom  \n";

    $excel.="
<tr>
<td>".getinfo($pros['reponse'][0]['gouvernorat'],'gouvernerat','nom')."</td> 
<td>".$pbs['delegt']."</td> 
<td>".$pros['reponse'][0]['id'].' '.$pros['reponse'][0]['nom'].' '.$pros['reponse'][0]['prenom']."</td> 
<td>".$user."</td> 
<td>".$datepb."</td> 
<td>".$pbs['totalPointBonus']."</td> 
</tr>";
endforeach;
$excel.="</table>";
header( 'content-type: text/html; charset=utf-8' );
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-disposition: attachment; filename=pointsBonus.xls");

print $excel;

exit;