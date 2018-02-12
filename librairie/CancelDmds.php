<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 12/02/18
 * Time: 11:41
 */

class CancelDmds extends GrmController {
    public function cancelVitrOrdnDmd($idDmd){
        $cadeauxDmd=get('*','grm_cadeaux_demander',array('id_demande='=>$idDmd));
        foreach ($cadeauxDmd['reponse'] as $cdx) {
            $this->edit_stock_gifts($cdx['id_cadeaux'],$cdx['qte']);
        }
        if(update($idDmd,array(
            'etat'=>-2,
            'modifier_par'=>$_SESSION['user']['id'],
            'date_validation'=>date("Y-m-d")
        ) ,'grm_demande_cadeaux')) {
            return true;
        } else {
            return false;
        }
    }
    public function cancelCdxDmd($idDmd){
        $cadeauxDmd=get('*','grm_cadeaux_demander',array('id_demande='=>$idDmd));
        foreach ($cadeauxDmd['reponse'] as $cdx) {
            if($cdx['type_cdx']==2)
                $this->edit_stock_gifts($cdx['id_cadeaux'],$cdx['qte']);
            else
                $this->edit_stock_prods($cdx['id_cadeaux'],$cdx['qte']);
        }
        if(update($idDmd,array(
            'etat'=>-2,
            'modifier_par'=>$_SESSION['user']['id'],
            'date_validation'=>date("Y-m-d")
        ) ,'grm_demande_cadeaux')) {
            return true;
        } else {
            return false;
        }
    }
    public function cancelPromotDmd($idDmd){
        $cadeauxDmd=get('*','promo_prod',array('id_promo='=>$idDmd));
        foreach ($cadeauxDmd['reponse'] as $cdx) {
            $this->edit_stock_gifts($cdx['id_prod'],$cdx['qte']);
        }
        if(update($idDmd,array(
            'etat'=>-2,
            'valider_par'=>$_SESSION['user']['id'],
            'date_validation'=>date("Y-m-d")
        ) ,'promo_demander')) {
            return true;
        } else {
            return false;
        }
    }
    public function cancelEchantDmd($idDmd){
        $cadeauxDmd=get('*','echant_prod',array('id_echant='=>$idDmd));
        foreach ($cadeauxDmd['reponse'] as $cdx) {
            $this->edit_stock_prods($cdx['id_prod'],$cdx['qte']);
        }
        if(update($idDmd,array(
            'etat'=>-2,
            'valider_par'=>$_SESSION['user']['id'],
            'date_validation'=>date("Y-m-d")
        ) ,'echant_demander')) {
            return true;
        } else {
            return false;
        }
    }
    public function cancelMatDGDmd($idDmd){
        $cadeauxDmd=get('*','materiel_deleg_details',array('id_dmd='=>$idDmd));
        foreach ($cadeauxDmd['reponse'] as $cdx) {
            $this->edit_stock_gifts($cdx['id_prod'],$cdx['qte']);
        }
        if(update($idDmd,array(
            'etat'=>-2,
            'id_validateur'=>$_SESSION['user']['id'],
            'date_validation'=>date("Y-m-d")
        ) ,'materiel_deleg')) {
            return true;
        } else {
            return false;
        }
    }
}
$cancelDmds=new CancelDmds();