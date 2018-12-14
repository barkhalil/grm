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

    function getPoids( $var)
    {
        global $PDO;

        $strSQL = "SELECT sum(poids) as somme FROM gouvernerat WHERE id IN ($var) ";
        $query = $PDO->prepare($strSQL);
        $query->execute();
        $retour = $query->fetch();
        return $retour->somme;
    }
    function gettotPBDate( $from,$to)
    {
        global $PDO;

        $strSQL = "SELECT sum(point_bonus) as total FROM `grm_demande_cadeaux`  WHERE  (isCart=0 OR isCart IS NULL)  and grm_demande_cadeaux.etat=4 and id_pros!=1 AND id_pros!=2  and date_validation BETWEEN '$from' AND '$to' ";
        $query = $PDO->prepare($strSQL);
        $query->execute();
        $retour = $query->fetch();
        return $retour->total;
    }

    public function gps($limit=NULL,$offset=NULL,$gvrn=NULL,$delegation=NULL,$deleg=NULL,$from=NULL,$to=NULL,$isCart=NULL) {
        global $PDO;
        $totalPB=array();
        $allPointBonus=0;
        //$pointBS=array();
        $request="SELECT DISTINCT(id_pros),point_bonus_reel as totalPointBonus,id_demandeur,date_validation,grmuser,delegation.nom as delegt FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id 
  JOIN delegation ON delegation.id=prospect.delegation
  JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id WHERE id_pros!=1 AND id_pros!=2 AND grm_demande_cadeaux.etat=4 ";
        ;
        $cnditions="";

            $cnditions.=" AND (grm_demande_cadeaux.isCart=0 OR grm_demande_cadeaux.isCart IS NULL)";

        if($from && $to) {
            $cnditions.=" AND grm_demande_cadeaux.date_validation BETWEEN '$from' AND '$to'";
        }
        $request.=$cnditions;
        $request=$request." GROUP BY grm_demande_cadeaux.id_pros";
        //echo $request;

        $stmt=$PDO->prepare($request);
        $stmt->execute();
        $prospects=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $total=count($prospects);
        //Calculer la totaliter des points bonus sans limite
        foreach ($prospects as $prospect) {
            $sql="SELECT *,	point_bonus_reel as totalPointBonus FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id
JOIN delegation ON delegation.id=prospect.delegation JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id 
WHERE id_pros=".$prospect['id_pros']." AND grm_demande_cadeaux.etat=4 ".$cnditions;
            $stmt=$PDO->prepare($sql);
            $stmt->execute();
            //echo '<br>'.$sql;
            $pointBS=$stmt->fetchAll(PDO::FETCH_ASSOC);
            //$totalPointBonus=0;
            $rest=0;
            foreach ($pointBS as $poinPros) {
                if($poinPros['totalPointBonus'])
                    $allPointBonus=$allPointBonus+$poinPros['totalPointBonus']-$rest;
                else
                    $allPointBonus=$allPointBonus+$poinPros['point_bonus']-$rest;
            }
        }//die;
        $request.="  ORDER BY gouvernerat.nom ";
        if($limit || $offset) {
            $request.="LIMIT $limit OFFSET $offset";
        }
        //echo $request;die;
        $stmt=$PDO->prepare($request);
        $stmt->execute();
//echo '<br>'.$request;
        $prospects=$stmt->fetchAll(PDO::FETCH_ASSOC);
        //calculer les points bonus par prospects
        foreach ($prospects as $prospect) {
            $sql="SELECT *,	point_bonus_reel as totalPointBonus FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id JOIN delegation ON delegation.id=prospect.delegation
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
            }
            $prospect['totalPointBonus']=$totalPointBonus;
            $totalPB[]=$prospect;
        }//die;
        $totalPB['reponse']=$totalPB;
        $totalPB['total']=$total;
        $totalPB['totalPointBonus']=$allPointBonus;
        return $totalPB;
    }



    public function AllPBpros($limit=NULL,$offset=NULL,$gvrn=NULL,$delegation=NULL,$deleg=NULL,$from=NULL,$to=NULL,$isCart=NULL) {
        global $PDO;
        $totalPB=array();
        $allPointBonus=0;
        //$pointBS=array();
        $request="SELECT DISTINCT(id_pros),point_bonus_reel as totalPointBonus,id_demandeur,date_validation,grmuser,delegation.nom as delegt FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id 
  JOIN delegation ON delegation.id=prospect.delegation
  JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id WHERE id_pros!=1 AND id_pros!=2 AND grm_demande_cadeaux.etat=4 ";
        ;
        $cnditions="";
        if($gvrn) {
            $gouvern=implode(',',$gvrn);
            $cnditions.=" AND gouvernerat.id IN ($gouvern)";
        }
        if($delegation) {
            $delegt=implode(',',$delegation);
            $cnditions.=" AND delegation.id IN ($delegt)";
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
        $request=$request." GROUP BY grm_demande_cadeaux.id_pros";
        //echo $request;

        $stmt=$PDO->prepare($request);
        $stmt->execute();
        $prospects=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $total=count($prospects);
        //Calculer la totaliter des points bonus sans limite
        foreach ($prospects as $prospect) {
            $sql="SELECT *,	point_bonus_reel as totalPointBonus FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id
JOIN delegation ON delegation.id=prospect.delegation JOIN gouvernerat ON prospect.gouvernorat=gouvernerat.id 
WHERE id_pros=".$prospect['id_pros']." AND grm_demande_cadeaux.etat=4 ".$cnditions;
            $stmt=$PDO->prepare($sql);
            $stmt->execute();
            //echo '<br>'.$sql;
            $pointBS=$stmt->fetchAll(PDO::FETCH_ASSOC);
            //$totalPointBonus=0;
            $rest=0;
            foreach ($pointBS as $poinPros) {
                if($poinPros['totalPointBonus'])
                    $allPointBonus=$allPointBonus+$poinPros['totalPointBonus']-$rest;
                else
                    $allPointBonus=$allPointBonus+$poinPros['point_bonus']-$rest;
            }
        }//die;
        $request.="  ORDER BY gouvernerat.nom ";
        if($limit || $offset) {
            $request.="LIMIT $limit OFFSET $offset";
        }
        //echo $request;die;
        $stmt=$PDO->prepare($request);
        $stmt->execute();
//echo '<br>'.$request;
        $prospects=$stmt->fetchAll(PDO::FETCH_ASSOC);
        //calculer les points bonus par prospects
        foreach ($prospects as $prospect) {
            $sql="SELECT *,	point_bonus_reel as totalPointBonus FROM `grm_demande_cadeaux` JOIN prospect ON grm_demande_cadeaux.id_pros=prospect.id JOIN delegation ON delegation.id=prospect.delegation
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