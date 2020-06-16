<style>
    table.table-bordered{
        border:1px solid #333;
        margin-top:20px;
    }
    table.table-bordered > thead > tr > th{
        border:1px solid #333;
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid #333;
    }
</style>
<?php

$de=$_SESSION['debut'];
$a=$_SESSION['au'];
$idDemandeau=$_SESSION['idel'];

echo $de.'<br>' ;
echo $a;
if($idDemandeau) {
    //if($idDemandeau ==2 )
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('id_demandeur='=>$idDemandeau,'isCart='=>0,'etat='=>0,'point_bonus>'=>0,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
} else {
    // récupération des cadeaux demander :
    $Cadeaux=get("*",'grm_demande_cadeaux',array('famille='=>10,'etat='=>0,'isCart='=>0,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
}






$data= '<table class="table table-bordered sameline-btns" id="listeCadeauTab" >
                    <thead>
                    <tr>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">nom/secteur</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">pharmacie</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">NBR1</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">VF1</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">NBR2</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">VF2</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">NBR3</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">VF3</th>
                         <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">NBR4</th>
                        <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">VF4</th>
                         <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">TOTAL</th>
                       <th style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">PERSONNALISATION SUR LES ENVELLOPES</th>
                    </tr>
                    </thead>
                    <tbody>';

foreach ($Cadeaux['reponse'] as $cdt){
    $data.='<tr>';
    $nb50=0;
    $nb30=0;
    $nb20=0;
    $nb5=0;
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">';
    if($cdt['id_demandeur']==2):
        $data.= getinfo(63,'users' ,'Nom').' '.getinfo(63,'users' ,'prenom');
    else:
        $data.=getinfo($cdt['id_demandeur'],'users' ,'Nom').' '.getinfo($cdt['id_demandeur'],'users' ,'prenom');
    endif;

    $data.='/'.getinfo(getinfo($cdt['id_pros'],'prospect' ,'gouvernorat'),'gouvernerat' ,'nom').'</td>';

    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.getinfo($cdt['id_pros'],'prospect' ,'nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom').'</td>';
    $ListeCadeaux=get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
    foreach ($ListeCadeaux['reponse'] as $cdx){
        if($cdx['id_cadeaux']==54){
            $nb50=$cdx['qte'];

        }
        if($cdx['id_cadeaux']==53 ){
            $nb30=$cdx['qte'];

        }
        if($cdx['id_cadeaux']==52 ){
            $nb20=$cdx['qte'];

        }
        if($cdx['id_cadeaux']==63 ){
            $nb5=$cdx['qte'];

        }
    }
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.$nb50.'</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">50</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.$nb30.'</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">30</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.$nb20.'</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">20</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.$nb5.'</td>';
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">5</td>';
    $tot=($nb50*50)+($nb30*30)+($nb20*20)+($nb5*5);
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.$tot.'</td>';
    $k='';
    if($cdt['id_demandeur']==2):
        $k.= getinfo(63,'users' ,'Nom').' '.getinfo(63,'users' ,'prenom');
    else:
        $k.=getinfo($cdt['id_demandeur'],'users' ,'Nom').' '.getinfo($cdt['id_demandeur'],'users' ,'prenom');
    endif;

    $k.='/'.getinfo(getinfo($cdt['id_pros'],'prospect' ,'gouvernorat'),'gouvernerat' ,'nom').'-';

    $k.=getinfo($cdt['id_pros'],'prospect' ,'nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom').'-'.$tot;
    $data.='<td style="border: 1px solid rgb(0,0,0);border: 1px solid rgb(0,0,0)">'.$k.'</td>';
    $data.='</tr>';
}
$data.= '</tbody>
                </table>';



header( 'content-type: text/html; charset=utf-8' );
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-disposition: attachment; filename=Liste.xls");

print $data;

exit;