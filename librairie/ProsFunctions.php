<?php

/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 03/09/2016
 * Time: 23:01
 */
// @ tester pour effacer cette class !!!!!!!!!
class ProsFunctions {
    function GetUsedDmdQuota($idDem){
        global $PDO;
        $Sql="Select echant_demander.pour as month From echant_demander WHERE par = $idDem AND etat >=0 ORDER BY id DESC LIMIT 1 ";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        $res=$stmt->fetch(PDO::FETCH_OBJ)->month;
        return $res ? $res : false;
    }
    public function getUserSections(){
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
   function getFormaListe($id,$table){
       if($id) $where=array('id='=>$id);
       else $where=array('1='=>1);
       $parent=get(array('id'),$table,$where);
       return $parent['reponse'];
}
    function GetAllData($limit = null){
        global $PDO;
        $retour=array();
        $strSQLFilter="";
        $strSQL =  'SELECT * FROM prospect WHERE public > 0 AND';
        $Details=$this->getUserSections();
        $firest=true;
        foreach ($Details['Liste'] as $Sect):
        if(!$firest) $strSQLFilter.= " OR ";
        $firest=false;
            $strSQLFilter.= " (";
            $strSQLFilter.= " gouvernorat =$Sect ";
            
            if(count($Details['Secteur']["$Sect"]['specialite'])>0 && $Details['Secteur']["$Sect"]['specialite']!=""){
                $inSpec = implode(',', $Details['Secteur']["$Sect"]['specialite'] );
                $strSQLFilter.= " AND spec IN($inSpec) ";  
            }
            if(count($Details['Secteur']["$Sect"]['delegation'])>0 && $Details['Secteur']["$Sect"]['delegation']!=""){
                $indelegation = implode(',', $Details['Secteur']["$Sect"]['delegation'] );
                $strSQLFilter.= " AND delegation IN($indelegation) ";
            }
            if(count($Details['Secteur']["$Sect"]['activite'])>0 && $Details['Secteur']["$Sect"]['activite']!=""){
                $inactivite = implode(',', $Details['Secteur']["$Sect"]['activite'] );
                $strSQLFilter.= " AND activite IN($inactivite) ";
            }
            if(count($Details['Secteur']["$Sect"]['potentiel'])>0 && $Details['Secteur']["$Sect"]['potentiel']!=""){
                $inpotentiel = implode(',', $Details['Secteur']["$Sect"]['potentiel'] );
                $strSQLFilter.= " AND potentiel IN($inpotentiel) ";
            }
            if(count($Details['Secteur']["$Sect"]['etablissement'])>0 && $Details['Secteur']["$Sect"]['etablissement']!=""){
                $inetablissement = implode(',', $Details['Secteur']["$Sect"]['etablissement'] );
                $strSQLFilter.= " AND etablissement IN($inetablissement) ";
            }
            $strSQLFilter.=" )";
        endforeach;
        if (!is_null($limit) && is_array($limit) && is_numeric($limit[0]) && is_numeric($limit[1])) {
            $debut = $limit[0];
            $nbRows = $limit[1];
            $limitStr = " LIMIT " . $debut . "," . $nbRows;
        } elseif (!is_null($limit) && !is_array($limit)) {
            $retour['erreur'] = "LIMIT doit être construit via un tableau de deux entiers";
        } else {
            $limitStr = "";
        }
   //     echo $strSQL.$strSQLFilter.'<br/>';
        $stmt = $PDO->prepare($strSQL.$strSQLFilter." ORDER BY id ASC ".$limitStr);
// bindvalue is 1-indexed, so $k+1
        /*foreach ($SpecFilter as $k => $spec)
        {  $stmt->bindValue(($k+1), $spec);}*/

        $stmt->execute();
        $ListeProspect['reponse']=$stmt->fetchAll(PDO::FETCH_ASSOC);
// test if has acc or not

        // on construit la requête
        $sqlTotal = "SELECT COUNT(*) as total FROM ".'prospect WHERE public > 0 AND '.$strSQLFilter;
        $q = $PDO->prepare($sqlTotal);
        $q->execute(@$values);
        $tot = $q->fetchAll(PDO::FETCH_ASSOC);
        $ListeProspect['total'] = $tot[0]['total'];
        return $ListeProspect;

    }

    function getParent($id,$table){
        $parent=get(array('gouv_id'),$table,array('id='=>$id));
        return $parent['reponse'][0]['gouv_id'];
    }

    function GetDataFiltred($NomFilter=null,$PrenomFilter=null, $SpecFilter = array(), $SectFilter = array(), $DelFilter = null, $ActiviteFilter = array(), $Potentiel = array(),$Etab=null, $limit = null){
        global $PDO;
     if($DelFilter){
            foreach ($DelFilter as $DelP):
               $DelParent[]=$this->getParent($DelP,'delegation');
            endforeach;
        }
        if($Etab){
            foreach ($Etab as $EtabP):
                $EtabParent[]=$this->getParent($EtabP,'etablissement');
            endforeach;
        }
        $retour=array();
        $strSQLFilter="";
        $strSQL =  'SELECT * FROM prospect WHERE public > 0 AND';
        $Details=$this->getUserSections();
        $firest=true;
        foreach ($Details['Liste'] as $Sect):
            //test if we do look in or not
            if(($SectFilter && in_array($Sect,$SectFilter)) || ($SpecFilter && array_key_exists($Sect,$SpecFilter) )|| ($ActiviteFilter && array_key_exists($Sect,$ActiviteFilter) )||( $Potentiel && array_key_exists($Sect,$Potentiel)) || $NomFilter || $PrenomFilter || ($DelFilter && in_array($Sect,$DelParent))|| ($Etab && in_array($Sect,$EtabParent))):
            if(!$firest) $strSQLFilter.= " OR ";
            $firest=false;
            $strSQLFilter.= " (";

            $strSQLFilter.= " gouvernorat =$Sect ";
            if(count($SpecFilter["$Sect"])>0){
                $inSpec = implode(',', $SpecFilter["$Sect"]);
                $strSQLFilter.= " AND spec IN($inSpec) ";
            }elseif(count($Details['Secteur']["$Sect"]['specialite'])>0 && $Details['Secteur']["$Sect"]['specialite']!=""){
                $inSpec = implode(',', $Details['Secteur']["$Sect"]['specialite'] );
                $strSQLFilter.= " AND spec IN($inSpec) ";
            }
            if(count($DelFilter)>0){
                foreach ($DelFilter as $del){
                    if($this->getParent($del,'delegation')==$Sect){
                        $indelegation .= $del.',';
                    }

                }     $strSQLFilter.= " AND delegation IN(".substr($indelegation , 0, -1).") ";

            }elseif(count($Details['Secteur']["$Sect"]['delegation'])>0 && $Details['Secteur']["$Sect"]['delegation']!=""){
                $indelegation = implode(',', $Details['Secteur']["$Sect"]['delegation'] );
                $strSQLFilter.= " AND delegation IN($indelegation) ";
            }
                if(count($ActiviteFilter["$Sect"])>0){
                    $inactivite = implode(',', $ActiviteFilter["$Sect"]);
                    $strSQLFilter.= " AND activite IN($inactivite) ";
                }elseif(count($Details['Secteur']["$Sect"]['activite'])>0 && $Details['Secteur']["$Sect"]['activite']!=""){
                $inactivite = implode(',', $Details['Secteur']["$Sect"]['activite'] );
                $strSQLFilter.= " AND activite IN($inactivite) ";
            }
                if(count($Potentiel["$Sect"])>0){
                    $inpotentiel = implode(',', $Potentiel["$Sect"]);
                    $strSQLFilter.= " AND potentiel IN($inpotentiel) ";
                }elseif(count($Details['Secteur']["$Sect"]['potentiel'])>0 && $Details['Secteur']["$Sect"]['potentiel']!=""){
                $inpotentiel = implode(',', $Details['Secteur']["$Sect"]['potentiel'] );
                $strSQLFilter.= " AND potentiel IN($inpotentiel) ";
            }
                if(count($Etab)>0){
                    foreach ($Etab as $etab){
                        if($this->getParent($etab,'etablissement')==$Sect){
                            $inetablissement .= implode(',', $etab );

                        }
                    }   $strSQLFilter.= " AND etablissement IN($inetablissement) ";
                }elseif(count($Details['Secteur']["$Sect"]['etablissement'])>0 && $Details['Secteur']["$Sect"]['etablissement']!=""){
                $inetablissement = implode(',', $Details['Secteur']["$Sect"]['etablissement'] );
                $strSQLFilter.= " AND etablissement IN($inetablissement) ";
            }
                if($NomFilter)  $strSQLFilter.= " AND nom Like '$NomFilter%' ";
                if($PrenomFilter)  $strSQLFilter.= " AND prenom Like '$PrenomFilter%' ";
            $strSQLFilter.=" )";
                endif;
        endforeach;
        if (!is_null($limit) && is_array($limit) && is_numeric($limit[0]) && is_numeric($limit[1])) {
            $debut = $limit[0];
            $nbRows = $limit[1];
            $limitStr = " LIMIT " . $debut . "," . $nbRows;
        } elseif (!is_null($limit) && !is_array($limit)) {
            $retour['erreur'] = "LIMIT doit être construit via un tableau de deux entiers";
        } else {
            $limitStr = "";
        }
       // echo $strSQL.$strSQLFilter.'<br/>';
        $stmt = $PDO->prepare($strSQL.$strSQLFilter." ORDER BY id ASC ".$limitStr);
// bindvalue is 1-indexed, so $k+1
        /*foreach ($SpecFilter as $k => $spec)
        {  $stmt->bindValue(($k+1), $spec);}*/

        $stmt->execute();
        $ListeProspect['reponse']=$stmt->fetchAll(PDO::FETCH_ASSOC);
// test if has acc or not

        // on construit la requête
        $sqlTotal = "SELECT COUNT(*) as total FROM ".'prospect WHERE public > 0 AND '.$strSQLFilter;
        $q = $PDO->prepare($sqlTotal);
        $q->execute(@$values);
        $tot = $q->fetchAll(PDO::FETCH_ASSOC);
        $ListeProspect['total'] = $tot[0]['total'];
        return $ListeProspect;
    }
    /*
     * @name : nom du details
     */
    function getAccListe($name,$idv,$sect,$table=null){
        // test if user not admin and not super
        if(!$table) $table="liste_details";
        $Type=$_SESSION['user']['type'];
        $id=$_SESSION['user']['id'];
        if($Type==1 || $Type==2){
            return TRUE;
        }else{
            //get user liste
            $userListes=$this->getUserSections();

            if(empty($userListes)){
                return FALSE;
            }else{
           $bool=$this->testValidation($userListes['details'][$sect][$name],$idv);
                return $bool;
            }

        }
    }
    function testValidation($array,$val){
        if($array!=NULL || $array!="" || $array[0]!=""){
            if(in_array($val,$array)===false) {
                return false;
            }
        }
        return true;
    }
    
}
$Pro=new ProsFunctions();