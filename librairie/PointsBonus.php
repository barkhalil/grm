<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 25/12/17
 * Time: 17:42
 */

class PointsBonus {
    public function viderSession() {
        $Pbs=get('*','grm_pb_type',array('etat='=>1));
        if($_SESSION['PbClient']) {
            $_SESSION['Point']=0;
            $_SESSION['newPb']=0;
            $_SESSION['TotPoint']=0;
            $_SESSION['TotalCdx']=0;
            $_SESSION['Obs']='';
            $_SESSION['ProdPbCmd']=null;
            $_SESSION['CdxCmd']=null;
            unset($_SESSION['PbClient']);
            unset($_SESSION['cdxSansPB']);
            unset($_SESSION['TotalCdx']);
            unset($_SESSION['ProdPbCmd']);
            unset($_SESSION['CdxCmd']);
            unset($_SESSION['firsttime']);
            foreach ($Pbs['reponse'] as $pB) {
                unset($_SESSION['Point'.$pB['id']]);
            }
        }
    }
    public function AllPBpros($limit=NULL,$offset=NULL,$gvrn=NULL,$deleg=NULL,$from=NULL,$to=NULL,$isCart=NULL) {
        global $PDO;
        $totalPB=array();
        $allPointBonus=0;
        //$pointBS=array();
        $request="SELECT DISTINCT(id_pros),point_bonus_reel as totalPointBonus,id_demandeur,date_validation,grmuser FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id
  JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id WHERE id_pros!=1 AND id_pros!=2 AND grm_demande_cadeaux.etat=4 ";
        $cnditions="";
        if($gvrn) {
            $cnditions.=" AND gouvernerat.id=$gvrn";
        }
        if($deleg) {
            if($deleg==63)
                $cnditions.=" AND (grm_demande_cadeaux.id_demandeur=2 OR grm_demande_cadeaux.id_demandeur=$deleg)";
            else
                $cnditions.=" AND grm_demande_cadeaux.id_demandeur=$deleg";
        }
        if($isCart) {
            $cnditions.=" AND grm_demande_cadeaux.isCart=1";
        } else {
            $cnditions.=" AND (grm_demande_cadeaux.isCart=0 OR grm_demande_cadeaux.isCart IS NULL)";
        }
        if($from && $to) {
            $cnditions.=" AND grm_demande_cadeaux.date_validation BETWEEN '$from' AND '$to'";
        }
        $request.=$cnditions;
        $request=$request." GROUP BY grm_demande_cadeaux.id_pros";//echo $request;die;
        /*$stmt=$PDO->prepare($request);
        $stmt->execute();
        $total=$stmt->fetchAll(PDO::FETCH_ASSOC);*/
        $stmt=$PDO->prepare($request);
        $stmt->execute();
        $prospects=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $total=count($prospects);
        //Calculer la totaliter des points bonus sans limite
        foreach ($prospects as $prospect) {
            $sql="SELECT *,	point_bonus_reel as totalPointBonus FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id
  JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id WHERE id_pros=".$prospect['id_pros']." AND grm_demande_cadeaux.etat=4 ".$cnditions;
            $stmt=$PDO->prepare($sql);
            $stmt->execute();
            $pointBS=$stmt->fetchAll(PDO::FETCH_ASSOC);
            //$totalPointBonus=0;
            $rest=0;
            foreach ($pointBS as $poinPros) {
                //$rest=$poinPros['rest_point'];echo $poinPros['id_pros'].' '. $rest.'<br/>';
                if($poinPros['totalPointBonus'])
                    $allPointBonus=$allPointBonus+$poinPros['totalPointBonus']-$rest;
                else
                    $allPointBonus=$allPointBonus+$poinPros['point_bonus']-$rest;
                $rest=$poinPros['rest_point'];//echo $poinPros['id_pros'].' '. $rest.'<br/>';
                /*if($poinPros['totalPointBonus'])
                    $allPointBonus+=array_sum(explode('@_@',$poinPros['totalPointBonus']));
                else
                    $allPointBonus+=array_sum(explode('@_@',$poinPros['ponitsByType']));*/
            }
        }//die;
        $request.="  ORDER BY gouvernerat.nom LIMIT $limit OFFSET $offset";
        //echo $request;die;
        $stmt=$PDO->prepare($request);
        $stmt->execute();
        $prospects=$stmt->fetchAll(PDO::FETCH_ASSOC);
        //calculer les points bonus par prospects
        foreach ($prospects as $prospect) {
            $sql="SELECT *,	point_bonus_reel as totalPointBonus FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id
  JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id WHERE id_pros=".$prospect['id_pros']." AND grm_demande_cadeaux.etat=4 ".$cnditions." ORDER BY grm_demande_cadeaux.date_validation";
            $stmt=$PDO->prepare($sql);
            $stmt->execute();
            $pointBS=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalPointBonus=0;
            $rest=0;
            foreach ($pointBS as $poinPros) {
                if($poinPros['totalPointBonus'])
                    $totalPointBonus=$totalPointBonus+$poinPros['totalPointBonus']-$rest;
                else
                    $totalPointBonus=$totalPointBonus+$poinPros['point_bonus']-$rest;
                $rest=$poinPros['rest_point'];
                /*if($poinPros['totalPointBonus']!='')
                    $totalPointBonus+=array_sum(explode('@_@',$poinPros['totalPointBonus']));
                else
                    $totalPointBonus+=array_sum(explode('@_@',$poinPros['ponitsByType']));*/
                //echo $allPointBonus.'<br/>';
            }
            $prospect['totalPointBonus']=$totalPointBonus;
            $totalPB[]=$prospect;
        }//die;
        $totalPB['reponse']=$totalPB;
        $totalPB['total']=$total;
        $totalPB['totalPointBonus']=$allPointBonus;
        return $totalPB;
    }
}
$pointsBonus= new PointsBonus();