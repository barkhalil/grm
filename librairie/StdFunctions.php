<?
//namespace StdF;
class StdFunctions {
    public function AfficheDateFr($dateTime){
        $Date=  explode(' ', $dateTime);
        $DateD=  explode('-', $Date['0']);
        // inversser la date à afficher :p 
        $DateRetour=$DateD['2'].'/'.$DateD['1'].'/'.$DateD['0'];
        return $DateRetour.' '.$Date['1'];
    }
    public function SetDate($dateTime){
        $Date=  explode(' ', $dateTime);
        $DateD=  explode('/', $Date['0']);
        $DateSys=$DateD['2'].'-'.$DateD['1'].'-'.$DateD['0'];
        return $DateSys.' '.$Date['1'];
    }
     public function Comparedate($date){
         $dEnd = new DateTime($date);
   $dStart  = new DateTime('2014-10-24 00:00');
   $dDiff = $dStart->diff($dEnd);
   $j= $dDiff->format('%R%a'); // use for point out relation: smaller/greater
  // echo $j=$dDiff->days;
    if($j>30){
        return array('class'=>'success',
            'nbr'=>$j
        ) ;
    }elseif ($j<30 && $j>0) {
         
           return array(
            'class'=>'warning',
            'nbr'=>$j
        ) ;
        }else{
           return array(
            'class'=>'danger',
            'nbr'=>$j
        ) ;
        }
    }
     function getGenInfo($id,$table,$Cand,$var){
        global $PDO;
        $strSQL="SELECT $var FROM $table WHERE  $Cand= ?";
        $query = $PDO->prepare($strSQL);
			$query->execute(array($id));
			$retour = $query->fetch();
                        return $retour->$var;
    }

    /*CRM FUNCTIONS*/
    /*
     * function to get spec text and changed by id
     */
    function getSpec($id,$text){
if(!is_numeric($text)){//c pas un id
    $idSpec=$this->testChaine($text,'specialite');
    update($id,array('spec'=>$idSpec),'prospect');
}
    }
    function getAct($id,$text){
if(!is_numeric($text)){//c pas un id
    $idactivite=$this->testChaine($text,'activite');
    update($id,array('activite'=>$idactivite),'prospect');
}
    }
    function getPotentiel($id,$text){
if(!is_numeric($text)){//c pas un id
    $Recherche=get("*",'potentiel',array(
            'valeur='=>$text
        ));
    if($Recherche['total']>0){
        $Pot=$Recherche['reponse'][0]['id'];
    }else{
        $Pot="";
    }
    update($id,array('potentiel'=>$Pot),'prospect');
}
    }
    /*
     * function to get spec text and changed by id
     */
    function getGov($id,$text){
if(!is_numeric($text)){//c pas un id
    $idgouvernorat=$this->testChaine($text,'gouvernerat');
    update($id,array('gouvernorat'=>$idgouvernorat),'prospect');
}
    }
    function getDelg($id,$idg,$text){
        if(!is_numeric($text)&& is_numeric($idg)){//c pas un id
            $idDelg=$this->testChaine($text,'delegation',$idg);
            update($id,array('delegation'=>$idDelg),'prospect');
        }
    }
    function getDelgCode($id,$idd,$code){
        // rechercher dans code postal si existe ou non
        $CodeListe=get("*",'postal_code',array('id_del='=>$idd));
        if($CodeListe['total']==0){//c pas un id
         add(array('id_del'=>$idd,'nom'=>$code),'postal_code');
        //     update($id,array('code_postal'=>$idCode),'prospect');
        }
    }
     
    function getEtab($id,$text){
        if(!is_numeric($text)){//c pas un id
    $idSpec=$this->testChaine($text,'etablissement');
    update($id,array('etablissement'=>$idSpec),'prospect');
}
    }
    /*
     * test if chaine add in table or not
     */
    function testChaine($text,$table,$idEtr=null){
        $Recherche=get("*",$table,array(
            'nom='=>$text
        ));
        $addArray=array(
            'nom'=>$text
        );
        if($idEtr){
            $addArray['gouv_id']=$idEtr;
        }
         if($Recherche['total']>0){
             //existe déja
             return $Recherche['reponse'][0]['id'];
         }else{
             //ajouter la chaine au table
             return add($addArray,$table);
         }
    }
function filterShow($Titre,$Liste,$table, $var){
     if(count($Liste)>0){
                                echo "<div class=\"pull-left \" style=\"margin-right: 5px;min-width: 20%;\"><h5>$Titre</h5> <ul>";
                             if(count($Liste)>1 || is_array($Liste)):
                                foreach ($Liste as $k=>$v):
                                    echo "<li>".getinfo($v, $table, $var)."</li>";
                                endforeach;
                             else: 
                                 echo "<li>".getinfo($Liste, $table, $var)."</li>";
                             endif;
                                echo '</ul></div>';
                            }
}
function testAffectation($IdProd,$idPRo){
    $Cible=get("*",'cible',array("gamme_id="=>$IdProd,"prospect="=>$idPRo),'AND');
    if($Cible['total']>0){
        return $Cible['reponse'][0];
    }else{
        return false;
    }
}
/*
 * function to get access do think by :p
 */
function getListe($name,$idv,$table=null){
    // test if user not admin and not super
    if(!$table) $table="liste";
    $Type=$_SESSION['user']['type'];
    $id=$_SESSION['user']['id'];
    if($Type==1 || $Type==2){
        return TRUE;
    }else{
        //get user liste
        $userListes=get("*",$table,array('user_id='=>$id));
       // print_r($userListes);
        if($userListes['total']==0){
            return FALSE;
        }else{
            $bool=$this->testValidation(explode("@_@", $userListes['reponse'][0][$name]),$idv);
            return $bool;
         }
        
}
    }
    function testValidation($array,$val){ 
       
         if($array!=NULL){
             if(in_array($val,$array)===false) {
                 return false; 
             }
         }
         return true;
    }
    function getDetailFiltre($FiltreParam,$SecteurId,$hasSecteur=true){
        $userid=$_SESSION['user']['id'];
        $order=array();
        // elle test s'il a des liste ou non donc il prend tous :
        $where=array(
            'user_id='=>$userid,
            'secteur='=>$SecteurId
        );

        $Detail=get(array($FiltreParam),'liste_details',$where);
        if($Detail['total']>0 && $Detail['reponse'][0][$FiltreParam]!=""){//ce utilisateur à des params :
            $idsEtab=explode("@_@",$Detail['reponse'][0][$FiltreParam]);
            foreach ($idsEtab as $itemEtab):
                $ListeEtab[]=get("*",$FiltreParam,array('id ='=>$itemEtab),"AND",array('id' => 'ASC'));
            endforeach;
        }else{
            $whereG['1=']=1;
            if($hasSecteur){
                   $whereG['gouv_id=']=$SecteurId;
                    $order=array('nom' => 'ASC');
            }else{
                $order=array('id' => 'ASC');
            }
          $ListeEtab[]=get("*",$FiltreParam,$whereG,"AND",$order);
        }
        return $ListeEtab;
    }
    function getDemande($userid){
        $Type=$_SESSION['user']['type'];
    $id=$_SESSION['user']['id'];
    switch ($Type) {
        case 1:
            return true;
        break;
        case 5:
            return true;
            break;
        case 2:
            return $this->getSup($id, $userid);
        break;
        default:
            return false;
    }
    } 
    function getSup($supID,$userid){
        $ind=get("*",'liste',array('supID='=>$supID,'user_id='=>$userid));
        if($ind['total']>0){
            return true;
        }else{
            return false;
        }
    }
    function countVisite ($user,$de,$a,$activite,$type,$eq){
        $Visite=array();$c=0;
        $where['date_visite >= ' ]=$de;
        $where['date_visite <= ' ]=$a;
        $where['type= ' ]=1;
        $where['public> ' ]=0;
        if($user)$where['id_visiteur=']=$user;
        $ListeVi=get('*','visite',$where);
        foreach($ListeVi['reponse'] as $vi):
           $prosActivite=getinfo($vi['id_pros'],'prospect','activite');
           $prosType=getinfo($vi['id_pros'],'prospect','spec');
        if($prosActivite==$activite && in_array($prosType,$type)==$eq){
            $Visite[$c++]=$vi;
        }
        endforeach;
        return $Visite;
    }
    function countVisiteA ($user,$de,$a,$activite){
        $Visite=array();$c=0;
        $where['date_visite >= ' ]=$de;
        $where['date_visite <= ' ]=$a;
        $where['type= ' ]=1;
        $where['public> ' ]=0;
        if($user)$where['id_visiteur=']=$user;
        $ListeVi=get('*','visite',$where);
        foreach($ListeVi['reponse'] as $vi):
            $prosActivite=getinfo($vi['id_pros'],'prospect','activite');
           // if($activite==null)
            if($activite==null){
                if($prosActivite==null || $prosActivite==""){
                    $Visite[$c++]=$vi;
                }
            }else{
                if($prosActivite==$activite){
                    $Visite[$c++]=$vi;
                }
            }



        endforeach;
        return $Visite;
    }
    function countVisiteG ($user,$de,$a,$type,$eq){
        $Visite=array();$c=0;
        $where['date_visite >= ' ]=$de;
        $where['date_visite <= ' ]=$a;
        $where['type= ' ]=1;
        $where['public> ' ]=0;
        if($user)$where['id_visiteur=']=$user;
        $ListeVi=get('*','visite',$where);
        foreach($ListeVi['reponse'] as $vi):
           // $prosActivite=getinfo($vi['id_pros'],'prospect','activite');
            $prosType=getinfo($vi['id_pros'],'prospect','spec'); 
            if(in_array($prosType,$type)==$eq){
                $Visite[$c++]=$vi;
            }
        endforeach;
        return $Visite;
    }
    function countGlobal ($Cond){
        global $PDO;
        $sqlTotal = "SELECT COUNT(*) as total FROM  visite WHERE $Cond";
        $q = $PDO->prepare($sqlTotal);
        $q->execute();
        $tot = $q->fetchAll(PDO::FETCH_ASSOC);
        return $tot[0]['total'];
    }
    function getWeek($week, $year) {
        $dto = new DateTime();
        $result['start'] = $dto->setISODate($year, $week, 1)->format('Y-m-d');
        $result['end'] = $dto->setISODate($year, $week, 7)->format('Y-m-d');
        return $result;
    }
    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
    function CheckDate($begin,$end,$id_pros,$id_visiteur){
        $paymentDate = date('Y-m-d');
        $paymentDate=date('Y-m-d', strtotime($paymentDate));;
        //echo $paymentDate; // echos today!
        $contractDateBegin = date('Y-m-d', strtotime($begin));
        $contractDateEnd = date('Y-m-d', strtotime($end));
		$visite=get("*",'visite',array(
                'id_visiteur='=>$id_visiteur,
                'id_pros='=>$id_pros,
                'date_visite<='=>$contractDateEnd,
                'date_visite>='=>$contractDateBegin
            ));
			//print_r($visite);
        if($paymentDate < $contractDateBegin){ 
             $cls=  $cls="btn-primary";
        }
		elseif($paymentDate > $contractDateEnd){
			 if($visite['total']>0)  $cls="btn-success";
            else $cls= "btn-danger";
		}
		 
        elseif (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
        {            
            if($visite['total']>0)  $cls= "btn-success";
            else $cls="btn-primary";
        }
        return $cls;
    }
    function renderCalendar($strMonth, $strYear) {
        $date = mktime(12, 0, 0, $strMonth, 1, $strYear);
        $daysInMonth = date("t", $date);
        $dayOffset = date("w", $date);
        $nextday = 1;
        $next = date("Y-m-",strtotime($strYear."-".$strMonth."-01 +1 months"));
        if ($dayOffset > 0) {
            $prevmonthdaystart = date("t",strtotime($strYear."-".$strMonth."-01 -1 months")) - ($dayOffset -1);
            $prev = date("Y-m-",strtotime($strYear."-".$strMonth."-01 -1 months"));
        }
        $calendarWeeks = ceil(($daysInMonth + $dayOffset) / 7) - 1;
        echo '<h1>'.date("F", $date).' '.$strYear.'</h1>';
        for ($rowIndex = 0; $rowIndex <= $calendarWeeks; $rowIndex++) {
            for ($colIndex = 1; $colIndex <= 7; $colIndex++) {
                $currentDay = ($colIndex - $dayOffset) + ($rowIndex * 7);
                if ($currentDay > $daysInMonth) {
                    $after = str_pad($nextday, 2 , "0",STR_PAD_LEFT);
			echo '<time datetime="'.$next.$after.'" class="not month">’;
			echo "<a href="#">'.$after.'</a></time>';
			$nextday++;
		} elseif ($currentDay < 1) {
                    echo '<time datetime="'.$prev.str_pad($prevmonthdaystart, 2 , "0",STR_PAD_LEFT).
                     "class=\"notmonth\"><a href=\"#\">".$prevmonthdaystart.'</a>';
			echo "</time>";
			$prevmonthdaystart++;
			} else {
			$current = str_pad($currentDay, 2 , "0",STR_PAD_LEFT);
			echo '<time datetime="'.$strYear.'-'.str_pad($strMonth, 2 , "0",STR_PAD_LEFT);
			echo '-'.$current.'"><a href="#" id='.$currentDay.'" >';
echo $current.'</a></time>';
			}
            }
        }
    }
    function getAllPoint($prosId){
        $p=0;
        // get_rest Point from the last gift demande (all rest point well be accumuler in the last one)
        $point=get(array('rest_point'),'grm_demande_cadeaux',array('id_pros='=>$prosId,'etat >='=>3),'AND',array('id'=>'DESC'));
        if($point['total']!=0){
            $p=$point['reponse'][0]['rest_point'];
        }
        return $p;
    }
}
$StdFunctions = new StdFunctions();
