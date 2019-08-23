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


    if($idDemandeau) {
        //if($idDemandeau ==2 )
        // récupération des cadeaux demander :
        $Cadeaux=get("*",'grm_demande_cadeaux',array('id_demandeur='=>$idDemandeau,'point_bonus>'=>0,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
    } else {
        // récupération des cadeaux demander :
        $Cadeaux=get("*",'grm_demande_cadeaux',array('famille='=>10,'date_remise_point>='=>$de,'date_remise_point<='=>$a),'AND',array('id'=>'ASC'));
    }





 $data= '<table class="table table-bordered sameline-btns" id="listeCadeauTab" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>date remise</th>
                        <th>Point remis</th>
                        <th>Délégué</th>
                        <th>Pour : </th>
                        <th>Etat demande</th>
                        <th>Cadeaux demander</th>
                        <th>Suivi</th>

                    </tr>
                    </thead>
                    <tbody>';

                    foreach ($Cadeaux['reponse'] as $cdt){
                       $data.='
                       <tr>
                            <td>';
                        $data.= $cdt['id']. '/' . date("Y", strtotime($cdt['system_date'])).'</td>'.
                            '<td>';
                        $data.= $cdt['date_remise_point'].'</td>'.
                            '<td>';
                        $data.=$cdt['point_bonus'].'</td>'.
                            '<td>';
                                if($cdt['id_demandeur']==2):
                                    $data.= getinfo(63,'users' ,'Nom').' '.getinfo(63,'users' ,'prenom');
                                else:
                                    $data.=getinfo($cdt['id_demandeur'],'users' ,'Nom').' '.getinfo($cdt['id_demandeur'],'users' ,'prenom');
                                endif;

                        $data.='</td>'.
                            '<td>'.getinfo($cdt['id_pros'],'prospect' ,'Nom').' '.getinfo($cdt['id_pros'],'prospect' ,'prenom').'</td>'
                            .'<td>';
                                if($cdt['etat']==0){
                                   $data.= "En cours de traitement";
                                }elseif($cdt['etat']==1){
                                    $data.=  "Pointer";
                                }elseif($cdt['etat']==-1){
                                    $data.=  "Refusée";
                                }elseif($cdt['etat']==-2){
                                    $data.=  "Annulée aprés validation";
                                }elseif($cdt['etat']==2){
                                    $data.=  "Points insufissant, avec reste =  ".$cdt['rest_point'];
                                }elseif($cdt['etat']==4){
                                    $data.=  "Valider avec reste = ".$cdt['rest_point'];
                                }else{
                                    $data.=  "Livrer le " .$cdt['date_livraison'];
                                }
                               $data.=  '</td>'
                            .'<td>
                                <ul class="small-padding">';
                                    $ListeCadeaux=get("*",'grm_cadeaux_demander',array('id_demande='=>$cdt['id']));
                                    for($i=0;$i<3;$i++):
                                        if($ListeCadeaux['total']<=$i) break;
                                       $data.='<li>';
                                        $data.=$ListeCadeaux['reponse'][$i]['qte'].' pour';
                                            if($ListeCadeaux['reponse'][$i]['type_cdx']==1){
                                                $data.=   getinfo($ListeCadeaux['reponse'][$i]['id_cadeaux'],'products' ,'name');
                                            }else{
                                                $data.=  getinfo($ListeCadeaux['reponse'][$i]['id_cadeaux'],'grm_gift' ,'titre') ;
                                            }

                                        $data.='</li>';
                                    endfor;
                                    if($ListeCadeaux['total']>3):
                                        $data.='<br/>...';
                                    endif;
                               $data.=' </ul>
                            </td>
                            <td>';
                               $suivi='';
                        if($cdt['suivi']==0){$suivi='non valider';}
                            else{$suivi='valider';}

                            $data.=$suivi;
                            $data.='</td>

                        </tr>';
                  }
$data.= '</tbody>
                </table>';



header( 'content-type: text/html; charset=utf-8' );
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-disposition: attachment; filename=Liste.xls");

print $data;

exit;