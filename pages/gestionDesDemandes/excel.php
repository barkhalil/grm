<?php
$de          = $_SESSION['debut'];
$a           = $_SESSION['au'];
$idDemandeau = $_SESSION['idel'];

if($idDemandeau){
    $Cadeaux = get("*",'grm_demande_cadeaux',array('id_demandeur='=>$idDemandeau,'point_bonus>'=>0,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
} else {
    $Cadeaux = get("*",'grm_demande_cadeaux',array('famille='=>10,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
}

$data  = '<style>';
$data .= 'table,th,td{border:1px solid #333;border-collapse:collapse;padding:4px 8px;font-size:12px;font-family:Arial,sans-serif;}';
$data .= 'thead th{background:#d9e1f2;font-weight:bold;text-align:center;}';
$data .= 'td{vertical-align:middle;}';
$data .= '</style>';

$data .= '<table>';
$data .= '<thead><tr>';
$data .= '<th>#</th>';
$data .= '<th>Date remise</th>';
$data .= '<th>Point remis</th>';
$data .= '<th>Délégué</th>';
$data .= '<th>Pour</th>';
$data .= '<th>Etat demande</th>';
$data .= '<th>Cadeau</th>';
$data .= '<th>Qté</th>';
$data .= '<th>Type</th>';
$data .= '<th>Créé par</th>';
$data .= '</tr></thead>';
$data .= '<tbody>';

foreach($Cadeaux['reponse'] as $cdt){

    // Délégué
    if($cdt['id_demandeur']==2){
        $delegue = getinfo(63,'users','Nom').' '.getinfo(63,'users','prenom');
    } else {
        $delegue = getinfo($cdt['id_demandeur'],'users','Nom').' '.getinfo($cdt['id_demandeur'],'users','prenom');
    }

    // Etat
    if($cdt['etat']==0){
        $etat = "En cours de traitement";
    }elseif($cdt['etat']==1){
        $etat = "Pointer";
    }elseif($cdt['etat']==-1){
        $etat = "Refusée";
    }elseif($cdt['etat']==-2){
        $etat = "Annulée après validation";
    }elseif($cdt['etat']==2){
        $etat = "Points insuffisant, reste = ".$cdt['rest_point'];
    }elseif($cdt['etat']==4){
        $etat = "Validé avec reste = ".$cdt['rest_point'];
    }else{
        $etat = "Livré le ".$cdt['date_livraison'];
    }

    // Cadeaux
    $ListeCadeaux = get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
    $cadeauxItems = array();
    foreach($ListeCadeaux['reponse'] as $item){
        $nom = ($item['type_cdx']==1)
            ? getinfo($item['id_cadeaux'],'products','name')
            : getinfo($item['id_cadeaux'],'grm_gift','titre');
        $cadeauxItems[] = array('qte'=>$item['qte'], 'nom'=>$nom);
    }

    $nbCadeaux = max(1, count($cadeauxItems));
    $ref       = $cdt['id'].'/'.date("Y", strtotime($cdt['system_date']));
    $pour      = getinfo($cdt['id_pros'],'prospect','Nom').' '.getinfo($cdt['id_pros'],'prospect','prenom');
    $creePar   = getinfo($cdt['cree_par'],'grm_users','Nom').' '.getinfo($cdt['cree_par'],'grm_users','prenom');
    $type      = ($cdt['isCart']==0) ? "BA" : "Carte";

    if(empty($cadeauxItems)){
        $data .= '<tr>';
        $data .= '<td>'.$ref.'</td>';
        $data .= '<td>'.$cdt['date_remise_point'].'</td>';
        $data .= '<td>'.$cdt['point_bonus'].'</td>';
        $data .= '<td>'.htmlspecialchars($delegue).'</td>';
        $data .= '<td>'.htmlspecialchars($pour).'</td>';
        $data .= '<td>'.$etat.'</td>';
        $data .= '<td>—</td>';
        $data .= '<td>—</td>';
        $data .= '<td>'.$type.'</td>';
        $data .= '<td>'.htmlspecialchars($creePar).'</td>';
        $data .= '</tr>';
    } else {
        foreach($cadeauxItems as $idx => $cadeau){
            $data .= '<tr>';
            if($idx === 0){
                $data .= '<td rowspan="'.$nbCadeaux.'">'.$ref.'</td>';
                $data .= '<td rowspan="'.$nbCadeaux.'">'.$cdt['date_remise_point'].'</td>';
                $data .= '<td rowspan="'.$nbCadeaux.'">'.$cdt['point_bonus'].'</td>';
                $data .= '<td rowspan="'.$nbCadeaux.'">'.htmlspecialchars($delegue).'</td>';
                $data .= '<td rowspan="'.$nbCadeaux.'">'.htmlspecialchars($pour).'</td>';
                $data .= '<td rowspan="'.$nbCadeaux.'">'.$etat.'</td>';
            }
            $data .= '<td>'.htmlspecialchars($cadeau['nom']).'</td>';
            $data .= '<td>'.$cadeau['qte'].'</td>';
            if($idx === 0){
                $data .= '<td rowspan="'.$nbCadeaux.'">'.$type.'</td>';
                $data .= '<td rowspan="'.$nbCadeaux.'">'.htmlspecialchars($creePar).'</td>';
            }
            $data .= '</tr>';
        }
    }
}

$data .= '</tbody></table>';

header('content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-disposition: attachment; filename=Liste_Cadeaux_'.$de.'_'.$a.'.xls');

echo $data;
exit;
