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
    $Type= array_keys($Budg);
    $TypeDep= array_keys($Depar);
    $data=array(
        'departement'=>$Depar[$TypeDep[0]],
        'type'=>$Type[0],
        'sold'=>$Budg[$Type[0]],
        'years'=>date("Y")
    );
    if(add($data,'grm_budget_annuel')){
        $_SESSION['msg'] = "Votre demande est sauvegarder";
        $_SESSION['type'] = "alert-success";
    }else{
        $_SESSION['msg'] = "Une erreur c'est produite";
        $_SESSION['type'] = "alert-danger";
    }

}
$listeDamnde=get('*','type_demande',null,'And',array('departement'=>'ASC'));

?>
<section class="content-header">
    <h1> Budget pour <?=date("Y")?> :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td></td>
                        <th>Département</th>
                        <th>Type</th>
                        <th>Budget <?=date("Y")-1?></th>
                        <th>Budget <?=date("Y")?></th>
                        <th>Reste du budget</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($listeDamnde['reponse'] as $dem): ?>
                        <tr>
                            <td><?echo $dem['id']?></td>
                            <td><?=getinfo($dem['departement'],'departement' , 'nom')?></td>
                            <td><?=$dem['name']?></td>
                            <td><?=$Gcc->getBudget($dem['id'],date("Y")-1 )?></td>
                            <td><?
                                $NowBudget=$Gcc->getBudget($dem['id'],date("Y") );
                                if($NowBudget=="" || $NowBudget==null){
                                    ?>
                                    <!-- Form To add budget :   -->
                                    <form class="form-inline" method="post">
                                        <input type="hidden" name="Departement[<?=$dem['id']?>]" value="<?=$dem['departement']?>">
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
            </div>
        </div>
    </div>
</section>
