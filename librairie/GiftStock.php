<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 14/02/18
 * Time: 09:46
 */

class GiftStock extends GrmController {
    public function getGiftAlert() {
        global $PDO;
        $Sql="SELECT * FROM grm_gift WHERE qte<=stoc_alert AND stoc_alert>0";
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllBnEntree($where=NULL,$limit=NULL,$offset=NULL) {
        global $PDO;
        if($limit=='') {
            $limit=0;
        }
        if($where) {
            $conditions = implode(' AND ', array_map(
                function ($v, $k) {
                    if(is_array($v)){
                        return $k.'[]='.implode('&'.$k.'[]=', $v);
                    }else{
                        return $k.' '.$v;
                    }
                },
                $where,
                array_keys($where)
            ));
            $condition=' AND '.$conditions;
        }
        $Sql="SELECT ref,GROUP_CONCAT(id,'//',system_date,'//',prod,'//',qte,'//',created_by) as champs FROM grm_stock WHERE ref IS NOT NULL $condition GROUP BY ref ";
        if($limit) {
            $Sql.="LIMIT $limit,$offset";
        }
        //echo $Sql;die;
        $stmt=$PDO->prepare($Sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
$giftStock=new GiftStock();