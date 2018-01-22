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
            $_SESSION['TotPoint']=0;
            $_SESSION['TotalCdx']=0;
            $_SESSION['Obs']='';
            $_SESSION['ProdPbCmd']=null;
            $_SESSION['CdxCmd']=null;
            unset($_SESSION['PbClient']);
            unset($_SESSION['TotalCdx']);
            unset($_SESSION['ProdPbCmd']);
            unset($_SESSION['CdxCmd']);
            foreach ($Pbs['reponse'] as $pB) {
                unset($_SESSION['Point'.$pB['id']]);
            }
        }
    }
}
$pointsBonus= new PointsBonus();