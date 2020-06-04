<?php
/**
 * Created by PhpStorm.
 * User: T-Yazen
 * Date: 30/10/2016
 * Time: 21:48
 */

$ListeCadeaux=get('*','stockbon',array('1='=>1),'AND',array('idbn'=>'ASC'));
//print_r($ListeCadeaux);
?>
<section class="content-header">
    <h1> Liste des cadeaux </h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">


    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <div class="box box-success box-body">


                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Titre</th>
                        <th>Secteur</th>

                        <th>Quantité</th>

                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($ListeCadeaux['reponse'] as $cade):
                        /* update($cade['id'],array(
                                 'paht'=>str_replace(',','.',$cade['paht']), //paht
                                 'pvht'=>str_replace(',','.',$cade['pvht']), //paht
                                 'pvttc'=>str_replace(',','.',$cade['pvttc']), //paht
                             )
                             ,'grm_gift')*/
                        ?>
                        <tr>
                            <td><?=
                                getinfo($cade['idbn'],'grm_gift','code_article');
                                ?></td>
                            <td><?=getinfo($cade['idbn'],'grm_gift','titre');?></td>
                            <td><?=getinfo($cade['idbn'],'gouvernerat','nom') ?></td>

                            <td><?=$cade['qte']?></td>


                        </tr>
                    <?endforeach;?>
                    </tbody>
                </table>
            </div>   </div>

    </div>

</section>