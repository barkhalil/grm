<?php
/**
 * Created by PhpStorm.
 * User: NAGUI
 * Date: 17/11/2015
 * Time: 22:34
 */
session_start();
require '../Connextion.php';
require '../librairie/loadall.php';
$SpecFilter=filter_input(INPUT_POST,'spec',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$SectFilter=filter_input(INPUT_POST,'secteur',FILTER_VALIDATE_INT);
$DelFilter=filter_input(INPUT_POST,'del',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$FilterListe=get('*','liste_details',array('user_id='=>$_SESSION['user']['id'],'secteur='=>$SectFilter));
//if($FilterListe['total']>0)
    $FilterInf=$FilterListe['reponse'][0];
if($DelFilter=="" && $FilterInf['delegation']!=""){$DelFilter=explode('@_@',$FilterInf['delegation']);  }
if($SpecFilter=="" && $FilterInf['specialite']!=""){$SpecFilter=explode('@_@',$FilterInf['specialite']);  }
if($FilterInf['etablissement']!=""){$Etab=explode('@_@',$FilterInf['etablissement']); }
if($FilterInf['activite']!=""){$ActiviteFilter=explode('@_@',$FilterInf['activite']); }
if($FilterInf['potentiel']!=""){$Potentiel=explode('@_@',$FilterInf['potentiel']); }
$strSQLFilter='';
if($SectFilter) {
    //  $inSect = implode(',', $SectFilter );
    $strSQLFilter.= " AND gouvernorat IN($SectFilter) ";
}
if($SpecFilter){
    $inSpec = implode(',', $SpecFilter );
    $strSQLFilter.= " AND spec IN($inSpec) ";
}
if($DelFilter){
    $inDel = implode(',', $DelFilter );
    $strSQLFilter.= " AND delegation IN($inDel) ";
}
$strSQL =  'SELECT * FROM prospect WHERE public > 0';
  // echo $strSQL.$strSQLFilter;
$stmt = $PDO->prepare($strSQL.$strSQLFilter." ORDER BY id ASC ");
// bindvalue is 1-indexed, so $k+1
/*foreach ($SpecFilter as $k => $spec)
{  $stmt->bindValue(($k+1), $spec);}*/

$stmt->execute();
$ListeProspect['reponse']=$stmt->fetchAll(PDO::FETCH_ASSOC);
$sqlTotal = "SELECT COUNT(*) as total FROM ".'prospect WHERE public > 0 '.$strSQLFilter;
$q = $PDO->prepare($sqlTotal);
$q->execute(@$values);
$tot = $q->fetchAll(PDO::FETCH_ASSOC);
$TotValue = $tot[0]['total'];
?>
<div class="checkbox">
    <label>
        <input type="checkbox" id="AllLink" name="AllPros" value="<?=$TotValue?>"/>Tous
    </label>
</div>

<? foreach ($ListeProspect['reponse'] as $peos): ?><div class="checkbox">
    <label>
        <input type="checkbox" value="<?=$peos['id']?>" name="ProListe[]" id="PRosSel_<?=$peos['id']?>" class="checkbox1"><?=$peos['nom']?> <?=$peos['prenom']?> </label> </div>
<?  endforeach;?>
                          
