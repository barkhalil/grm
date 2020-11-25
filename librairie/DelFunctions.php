<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 11/09/2016
 * Time: 02:10
 */
//not a class juste a functions :

function getDelVisite(){
// get visite number of del conected or get Total of Total
    if($_SESSION['user']['type']>2){
        $where=array(
          'id_visiteur = '=>$_SESSION['user']['id']
        );
    }else{
        $where=array(
            'public >'=>0
        );
    }
    $Vi=get('*','visite',$where);
    return $Vi['total'];// posible à amilo
}
function getUserAllSections(){
    // get user sections :
    $Sections=array();
    $user=$_SESSION['user']['id'];
    $userType=$_SESSION['user']['type'];
    if($userType>2):
        $Sections=get('*','liste_details',array(
            'user_id='=>$user
        ));
        foreach ($Sections['reponse'] as $Espace):
            $Sections['Secteur']['Liste'][]=$Espace['secteur'];
            $Sections['Secteur']['details'][$Espace['secteur']]=array(
                'etablissement'=>$Espace['etablissement'] ? explode('@_@',$Espace['etablissement']):'',
                'delegation'=>$Espace['delegation'] ?explode('@_@',$Espace['delegation']):'',
                'specialite'=>$Espace['specialite'] ?explode('@_@',$Espace['specialite']):'',
                'activite'=>$Espace['activite'] ?explode('@_@',$Espace['activite']):'',
                'potentiel'=>$Espace['potentiel'] ?explode('@_@',$Espace['potentiel']):'',
            );
        endforeach;
    endif;
    return $Sections;
}
/*
 * prospect par secteur :
 */
function GetTotalProspectDel($user=null,$Qdel=true,$Qetab=true,$limit=null){
    if(!$user && $_SESSION['user']['type']>2){
        $user=$_SESSION['user']['id'];
    }
    global $PDO;
    $strSQLFilter="";
    $strSQL =  'SELECT * FROM prospect WHERE public > 0 AND';
    $Secteur=get("*",'liste',array('user_id='=>$user)); // récupération des section de l'utilisateur
   // print_r($Secteur);
    $details=explode('@_@',$Secteur['reponse'][0]['secteur']);
                $firest=true;
    foreach ($details as $sect):

        if(!$firest) $strSQLFilter.= " OR ";
        $firest=false;
        $strSQLFilter.= " (";
    // requet multiple de count Sql :
        $strSQLFilter.= " gouvernorat =$sect ";
        $DetListe=get("*",'liste_details',array('user_id='=>$user,'secteur='=>$sect));
      //  print_r($DetListe);
        if($DetListe['total']>0):
        $SqlDEta=$DetListe['reponse'][0];
        if($SqlDEta['etablissement']!=""){
         $inEtab = str_replace("@_@",',',$SqlDEta['etablissement'] );
         $strSQLFilter.= " AND etablissement IN($inEtab) ";
        }
        if($SqlDEta['delegation']!=""){
            $indelegation = str_replace("@_@",',',$SqlDEta['delegation'] );
            $strSQLFilter.= " AND delegation IN($indelegation) ";
        }
        if($SqlDEta['specialite']!=""){
            $inspec = str_replace("@_@",',',$SqlDEta['specialite'] );
            $strSQLFilter.= " AND spec IN($inspec) ";
        }
        if($SqlDEta['activite']!=""){
            $inactivite = str_replace("@_@",',',$SqlDEta['activite'] );
            $strSQLFilter.= " AND activite IN($inactivite) ";
        }
        if($SqlDEta['potentiel']!=""){
            $ipotentiel = str_replace("@_@",',',$SqlDEta['potentiel'] );
            $strSQLFilter.= " AND potentiel IN($ipotentiel) ";
        }
        endif;
        $strSQLFilter.=" )";

    endforeach;
   // echo $strSQLFilter;

      // $strSQL.$strSQLFilter.'<br/>';
    $stmt = $PDO->prepare($strSQL.$strSQLFilter." ORDER BY id ASC ");
    $stmt->execute();
    $ListeProspect=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $ListeProspect;

}
function RetSql($user){
    $strSQLFilter="";
    $Secteur=get("*",'liste',array('user_id='=>$user)); // récupération des section de l'utilisateur
    // print_r($Secteur);
    $details=explode('@_@',$Secteur['reponse'][0]['secteur']);
    $firest=true;
    if($Secteur['total']>0):

    $strSQLFilter.= " AND ( ";
    foreach ($details as $sect):

        if(!$firest) $strSQLFilter.= " OR ";
        $firest=false;
        $strSQLFilter.= " (";
        // requet multiple de count Sql :
        $strSQLFilter.= " prospect.gouvernorat =$sect ";
        $DetListe=get("*",'liste_details',array('user_id='=>$user,'secteur='=>$sect));
        //  print_r($DetListe);
        if($DetListe['total']>0):
            $SqlDEta=$DetListe['reponse'][0];
            if($SqlDEta['etablissement']!=""){
                $inEtab = str_replace("@_@",',',$SqlDEta['etablissement'] );
                $strSQLFilter.= " AND prospect.etablissement IN($inEtab) ";
            }
            if($SqlDEta['delegation']!=""){
                $indelegation = str_replace("@_@",',',$SqlDEta['delegation'] );
                $strSQLFilter.= " AND prospect.delegation IN($indelegation) ";
            }
            if($SqlDEta['specialite']!=""){
                $inspec = str_replace("@_@",',',$SqlDEta['specialite'] );
                $strSQLFilter.= " AND prospect.spec IN($inspec) ";
            }
            if($SqlDEta['activite']!=""){
                $inactivite = str_replace("@_@",',',$SqlDEta['activite'] );
                $strSQLFilter.= " AND prospect.activite IN($inactivite) ";
            }
            if($SqlDEta['potentiel']!=""){
                $ipotentiel = str_replace("@_@",',',$SqlDEta['potentiel'] );
                $strSQLFilter.= " AND prospect.potentiel IN($ipotentiel) ";
            }
        endif;
        $strSQLFilter.=" )";

    endforeach;
    $strSQLFilter.=" )";
        endif;
    return $strSQLFilter;
}
function GetTotalProspectDelVisite($user=null,$a,$de,$limit=0,$potentiel=null,$Cond){
      //SELECT prospect.* FROM prospect LEFT JOIN visite on prospect.id=visite.id_pros WHERE visite.id is null
    global $PDO;
    $strSQLINI = "Select prospect.*,COUNT(visite.id_pros) as TotalPros ";
    $strSQLReq="FROM prospect LEFT JOIN visite on prospect.id=visite.id_pros WHERE  prospect.public > 0 AND visite.date_visite >='$de' AND visite.date_visite <='$a' AND visite.public = 1 AND visite.type = 1 ";
    $strSQL=$strSQLINI.$strSQLReq;
    if($user)        $strSQLFilter=RetSql($user)." AND visite.id_visiteur=$user";
    else $strSQLFilter="";
   ;
    if($potentiel){
        $strSQLFilter.=" AND prospect.potentiel = $potentiel";
    }

    if (!is_null($limit) && is_array($limit) && is_numeric($limit[0]) && is_numeric($limit[1])) {
        $debut = $limit[0];
        $nbRows = $limit[1];
        $limitStr = " LIMIT " . $debut . "," . $nbRows;
    } elseif (!is_null($limit) && !is_array($limit)) {
        $retour['erreur'] = "LIMIT doit être construit via un tableau de deux entiers";
    } else {
        $limitStr = "";
    }

    $stmt = $PDO->prepare($strSQL.$strSQLFilter." GROUP BY visite.id_pros HAVING TotalPros $Cond ".$limitStr);
    $stmt->execute();
    $retour['requete'] = $strSQL.$strSQLFilter." GROUP BY visite.id_pros HAVING TotalPros $Cond ".$limitStr;
    $retour['reponse'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sqlTotal = "SELECT COUNT(prospect.id) as total " . $strSQLReq.$strSQLFilter;
    $q = $PDO->prepare($sqlTotal);
    $q->execute();
    $tot = $q->fetchAll(PDO::FETCH_ASSOC);
    $retour['total'] = $tot[0]['total'];
    return $retour;

}
/*
 * SELECT prospect.*,COUNT(visite.id_pros) as total FROM prospect LEFT JOIN visite on prospect.id=visite.id_pros WHERE prospect.potentiel = 1 AND visite.date_visite >='2016-01-01' AND visite.date_visite <='2016-09-01' AND visite.public = 1 AND visite.type = 1 GROUP BY visite.id_pros HAVING total >=2
 */
function GetTotalProspectDelNonVisited($limit=0,$user=null,$potentiel=null){
    global $PDO;
    $strSQLINI = "SELECT prospect.* ";
    $strSQLReq="FROM prospect LEFT JOIN visite on prospect.id=visite.id_pros WHERE prospect.public > 0 ";
    $strSQL=$strSQLINI.$strSQLReq;
    if($user) $strSQLFilter=RetSql($user);
    else $strSQLFilter="";
    $strSQLFilter.=$strSQLFilter." and visite.id is null";
    if($potentiel){
        $strSQLFilter.=" AND prospect.potentiel = $potentiel";
    }
    //SELECT prospect.id,COUNT(visite.id_pros) AS Total FROM `prospect` LEFT JOIN visite ON prospect.id = visite.id_pros GROUP BY visite.id_pros
    if (!is_null($limit) && is_array($limit) && is_numeric($limit[0]) && is_numeric($limit[1])) {
        $debut = $limit[0];
        $nbRows = $limit[1];
        $limitStr = " LIMIT " . $debut . "," . $nbRows;
    } elseif (!is_null($limit) && !is_array($limit)) {
        $retour['erreur'] = "LIMIT doit être construit via un tableau de deux entiers";
    } else {
        $limitStr = "";
    }
  //  echo $strSQL.$strSQLFilter.$limitStr.'<br/>';
    $stmt = $PDO->prepare($strSQL.$strSQLFilter.$limitStr);
    $stmt->execute();
    $retour['requete'] = $strSQL.$strSQLFilter.$limitStr;
    $retour['reponse'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sqlTotal = "SELECT COUNT(*) as total " . $strSQLReq.$strSQLFilter;
    $q = $PDO->prepare($sqlTotal);
    $q->execute();
    $tot = $q->fetchAll(PDO::FETCH_ASSOC);
    $retour['total'] = $tot[0]['total'];
    return $retour;
}

function getThiVisites($listePros,$user,$de,$a,$limite=1){
    $tot=0;
    foreach ($listePros as $pros):
    //    print_r($pros);
     //    $tot+=  getNbrVi($pros['id'], $user,$de,$a,$limite);
    endforeach;
    return $tot;
}
function getNbrVi($de,$a,$user=null,$limite=0){
    global $PDO;
  $sql="SELECT COUNT(id_pros) AS Total FROM `visite` WHERE `id_visiteur` = $user AND `type` = 1 AND date_visite >= '$de' AND date_visite <= '$a' GROUP By id_pros HAVING Total >$limite  ORDER BY id_pros  DESC"; //GROUP BY how to count this groupe ?

    //  print_r($Vi);
    $stmt = $PDO->prepare($sql);
    $stmt->execute();
    $Vi=$stmt->fetchAll(PDO::FETCH_ASSOC);
     return $Vi;
}
function getProdDm($de,$a,$user,$prod){
    global $PDO;
    $sql="SELECT sum(promo_prod.qte) as tot from promo_demander,promo_prod where promo_prod.id_promo=promo_demander.id 
and promo_demander.par=$user and  promo_demander.etat=1 and  promo_demander.date_validation>='$de' 
and promo_demander.date_validation<='$a' and promo_prod.id_prod=$prod";


    /*$sql="SELECT COUNT(id_pros) AS Total FROM `visite` WHERE `id_visiteur` = $user
AND `type` = 1 AND date_visite >= '$de' AND date_visite <= '$a' 
GROUP By id_pros HAVING Total >$limite  ORDER BY id_pros  DESC"; //GROUP BY how to count this groupe ?*/

    //  print_r($Vi);
    $query = $PDO->query($sql);
    // $query->execute($query);
    $retour = $query->fetch();
    return $retour->tot;
}
function getNbreDayOuv($de,$a){
    $jourV=get('*','date_jouv',array(
        'date_j>='=>$de,
        'date_j<='=>$a,
    ));
    return $jourV['total'];
}
function getNbreDayAbs($de,$a,$userid){
    $jourV=get('*','pointage',array(
        'user_id='=>$userid,
        'date_debut>='=>$de,
        'date_debut<'=>$a,
    ));
    $totAbs=0;
    foreach ($jourV['reponse'] as $abs){
//print_r($abs);
    $totAbs+=$abs['nbreJours'];
    }
    return $totAbs;
}
function GetLasteVisiteDate($viNbrC,$pros,$user=null){
    $ret= "jamais";
    $where=array(
        'id_pros='=>$pros
    );
    if($viNbrC){
        $V=get("*", 'visite',$where,'AND',array('date_visite'=>'DESC'));
        $ret=$V['reponse'][0]['date_visite'].'<br/>Par: '.getinfo($V['reponse'][0]['id_visiteur'],'users' ,'Nom').' '
        .getinfo($V['reponse'][0]['id_visiteur'],'users' ,'Prenom');
    }
    return $ret;
}
