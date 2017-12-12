<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 30/10/2016
 * Time: 14:56
 */
if(filter_input(INPUT_POST,'AddBudget',257)){
    $Budg=filter_input(0,'budget',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY );
    $Depar=filter_input(0,'Departement',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY );
    $zoneI=filter_input(0,'zone',FILTER_VALIDATE_INT);
    $Type= array_keys($Budg);
    $TypeDep= array_keys($Depar);
    $data=array(
        'departement'=>$Depar[$TypeDep[0]],
        'type'=>$Type[0],
        'zone'=>$zoneI,
        'sold'=>$Budg[$Type[0]],
        'years'=>date("Y")
    );
    print_r($data);
    if(add($data,'grm_budget_annuel_zone')){
        $_SESSION['msg'] = "Votre demande est sauvegarder";
        $_SESSION['type'] = "alert-success";
    }else{
        $_SESSION['msg'] = "Une erreur c'est produite";
        $_SESSION['type'] = "alert-danger";
    }

}
$listeDamnde=get('*','type_demande',array('departement='=>3),'And',array('departement'=>'ASC'));

?>
<section class="content-header">
    <h1> Budget pour <?=date("Y")?> :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body">
                <? $Zones=get("*",'zone',array('id<='=>4));
                foreach ($Zones['reponse'] as $zone):
                ?>
                    <h3><?=$zone['nom']?></h3>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Département</th>
                        <th>Type</th>
                        <th>Budget Total</th>
                        <th>Budget par zone 1 <?=date("Y")?></th>
                        <th>Reste du budget</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($listeDamnde['reponse'] as $dem): ?>
                        <tr>

                            <td><?=getinfo($dem['departement'],'departement' , 'nom')?></td>
                            <td><?=$dem['name']?></td>
                            <td><?=$Gcc->getBudget($dem['id'],date("Y") )?></td>
                            <td><?
                                $NowBudget=$Gcc->getBudgetZone($dem['id'],date("Y"),$zone['id']);
                                if($NowBudget=="" || $NowBudget==null){
                                    ?>
                                    <!-- Form To add budget :   -->
                                    <form class="form-inline" method="post">
                                        <input type="hidden" name="Departement[<?=$dem['id']?>]" value="<?=$dem['departement']?>">
                                        <input type="hidden" name="zone" value="<?=$zone['id']?>">
                                        <div class="form-group">
                                            <label>Budget pour <?=date("Y")?></label>
                                            <input type="number" value="0.00" min="0" step="0.001" class="form-control" name="budget[<?=$dem['id']?>]">
                                        </div>
                                        <button type="submit" name="AddBudget" value="1" class="btn btn-primary">
                                            <i class="fa fa-dollar"></i>
                                        </button>
                                    </form>
                                <? }else{
                                    echo $NowBudget;
                                }
                                ?></td>
                            <td></td>

                        </tr>
                    <?endforeach;?>
                    </tbody>
                </table>
                <?endforeach;?>

            </div>
        </div>
    </div>
</section>
