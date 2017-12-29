<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 28/03/2017
 * Time: 15:13
 */
$AddPb =filter_input(INPUT_POST,'AddPb',FILTER_VALIDATE_INT);
if($AddPb){
    add(
        array(
            'value'=>filter_input(INPUT_POST,'ValPB',FILTER_DEFAULT),
            'etat'=>1,
            'cree_par'=>$_SESSION['user']['id'],
            'date_crea'=>date('Y-m-d')
        ),'grm_pb_type'
    );
}
$Blocid=filter_input(INPUT_GET,'Blocid',FILTER_VALIDATE_INT);
if($Blocid){
    update($Blocid,array(
            'etat'=>0,
            'cree_par'=>$_SESSION['user']['id'],
            'date_stop'=>date('Y-m-d')
    ),'grm_pb_type');
}

?>
<section class="content-header">
    <h1> Gestion PB </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
        <div class="box box-body box-danger">

            <form class="" method="post">
                <div class="form-group">
                    <label>Value PB</label>
                    <input type="number" name="ValPB" value="" min="0.1" step="0.01" class="form-control">
                </div>
                <button type="submit" name="AddPb" class="btn btn-block btn-primary" value="1">Ajouter </button>
            </form>

        </div>
        </div>
        <div class="col-md-8">
            <?php $TypePb=get('*','grm_pb_type')?>
            <div class="box box-body box-success">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Valeur</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($TypePb['reponse'] as $Ty):?>
                    <tr >
                        <td><?php echo $Ty['id'] ?></td>
                        <td><?php echo $Ty['value'] ?></td>
                        <td>
                            <?php  if($Ty['etat']==1 && $Ty['id']!=1):?>
                            <a href="GestionPB&Blocid=<?php echo $Ty['id'] ?>" class="btn btn-danger">
                                <i class="fa fa-ban"></i>
                            </a>
                            <?else:?>
                                Date de désactivation : <?php echo $Ty['date_stop']?>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
