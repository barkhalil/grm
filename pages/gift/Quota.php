<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 30/12/2016
 * Time: 14:21
 */
if(filter_input(INPUT_POST,'UpTypes',257)):
$TypePosted=filter_input(INPUT_POST,'type',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
foreach ($TypePosted as $UpType => $value){
    update($UpType,array(
        'quota'=>$value
    ),'user_type');
}
endif;
?>

<section class="content-header">
    <h1> validation demande de cadeaux :  </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-md-12">
        <form method="post">


        <div class="box box-body box-default">
            <?
            //récupération des types d'utilisateur du CRM
            $Types=get("*",'user_type');
            foreach ($Types['reponse'] as $type):
            ?>
                <div class="form-group">
                    <label><?=$type['name']?></label>
                    <input type="test" name="type[<?=$type['id']?>]" class="form-control" value="<?=$type['quota']?>" placeholder="Quota de ce type">
                </div>
            <?endforeach;?>
            <button type="submit" name="UpTypes" value="1" class="btn btn-primary">Changer le quota</button>
        </div>
        </form>
    </div>
    </div>
</section>

