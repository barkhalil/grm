<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <?
        $ActivePage=explode('/',$Pga);
        ?>




        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="<?if($ActivePage[0]=="default") echo 'active'?>"><a href="<?=WEBRoot?>"><i class="fa fa-dashboard"></i> <span>Tableau de Bord</span></a></li>
            <?php    if($_SESSION['user']['type']<104){?>
                <li class=" <?if($ActivePage[0]=="prospects") echo 'active'?>">
                    <a href="<?=WEBRoot?>/prospects/listeAdmin"><i class="fa fa-users"></i> <span>Prospect</span> </a>
                </li>
                <? if($_SESSION['user']['type']<=102): ?>
                    <li class="treeview <?if($ActivePage[0]=="admin") echo 'active'?>">
                        <a href="#"><i class="fa fa-adn"></i> <span>Administrateur</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">

                            <li><a href="<?=WEBRoot?>/admin/Ajouter-Utilisateur">Ajouter un utilisateur</a></li>
                            <li><a href="<?=WEBRoot?>/admin/liste">Liste des utilisateurs</a></li>


                        </ul>
                    </li>
                    <li class="treeview <?if($ActivePage[0]=="General") echo 'active'?>">
                        <a href="#"><i class="fa fa-codepen"></i> <span>Configuration</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?=WEBRoot?>/General/TypeDemande">Type des demandes</a></li>
                            <li><a href="<?=WEBRoot?>/General/GestionPB">Gestion PB</a></li>


                        </ul>
                    </li>
                <?endif;?>
                <li class="treeview <?if($ActivePage[0]=="products") echo 'active'?>">
                    <a href="#"><i class="fa fa-codepen"></i> <span>Gestion des produits</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <? if($_SESSION['user']['type']<=102): ?>
                            <li><a href="<?=WEBRoot?>/products/GestionProduits">Produits</a></li>
                            <li><a href="<?=WEBRoot?>/products/GestionPrixProduits">Prix Produits</a></li>
                            <li><a href="<?=WEBRoot?>/products/Gammes">Gamme</a></li>
                        <?endif;?>
                        <li><a href="<?=WEBRoot?>/products/bonEntree">Bon d'entrée</a></li>
                        <li><a href="<?=WEBRoot?>/products/listeBnEntree">Liste des bons d'entrée</a></li>

                        <!--<li><a href="<?=WEBRoot?>/gift/AddDemande">Ajouter une demande</a></li>-->


                    </ul>
                </li>
                <li class="treeview <?if($ActivePage[0]=="gift" || $ActivePage[0]=="Bonus") echo 'active'?>">
                    <a href="#"><i class="fa fa-codepen"></i> <span>Gestion des articles</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <? if($_SESSION['user']['type']<=102): ?>
                            <li><a href="<?=WEBRoot?>/gift/Liste">Autre demandes</a></li>
                        <?endif;?>
                        <li><a href="<?=WEBRoot?>/gift/ListeCadeaux">Liste des articles</a></li>
                        <li><a href="<?=WEBRoot?>/gift/AddCadeaux">Ajouter un article</a></li>
                        <li><a href="<?=WEBRoot?>/gift/listeBnEntree">Liste des bons d'entrée</a></li>
                        <? if($_SESSION['user']['type']<=102): ?>
                            <li><a href="<?=WEBRoot?>/gift/Quota">Quota</a></li>
                        <?endif;?>
                        <!--<li><a href="<?=WEBRoot?>/gift/AddDemande">Ajouter une demande</a></li>-->
                    </ul>
                </li>
                <li class="treeview <?if($ActivePage[0]=="gestionDesDemandes") echo 'active'?>">
                    <a href="#"><i class="fa fa-codepen"></i> <span>Gestion des demandes</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?=WEBRoot?>/gestionDesDemandes/Liste">Demandes cadeaux</a></li>
                        <li><a href="<?=WEBRoot?>/gestionDesDemandes/listeDemandeVitrine">Demandes vitrine</a></li>
                        <li><a href="<?=WEBRoot?>/gestionDesDemandes/listeDemandeOrdonnancier">Demandes ordonnancier</a></li>
                        <li><a href="<?=WEBRoot?>/gestionDesDemandes/dmdPromotionnel">Matériel promotionnel</a></li>
                        <li><a href="<?=WEBRoot?>/gestionDesDemandes/listedmdEchantillons">Demandes échantillons</a></li>
                        <li><a href="<?=WEBRoot?>/gestionDesDemandes/listeDmdMaterelDeleg">Matériel délégué</a></li>
                    </ul>
                </li>
                <? if($_SESSION['user']['type']<=102): ?>
                    <li class="<?if($ActivePage[0]=="fournisseur") echo 'active'?>">
                        <a href="<?=WEBRoot?>/fournisseur/index">
                            <i class="fa fa-mortar-board"></i>
                            Gestion fournisseur
                        </a>
                    </li>
                <?endif;?>
                <? if($_SESSION['user']['type']<=101): ?>
                    <li class="<?if($ActivePage[0]=="Bonus") echo 'active'?>">
                        <a href="<?=WEBRoot?>/Bonus/listePointsBonus">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            Liste des points bonus
                        </a>
                    </li>
                <?endif;?>
            <?php } ?>
            <!------   assis  achat -->
                <?php if ($_SESSION['user']['type']==104){ ?>

                    <li class="treeview <?if($ActivePage[0]=="gift" || $ActivePage[0]=="Bonus") echo 'active'?>">
                        <a href="#"><i class="fa fa-codepen"></i> <span>Gestion des articles</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <? if($_SESSION['user']['type']<=102): ?>
                                <li><a href="<?=WEBRoot?>/gift/Liste">Autre demandes</a></li>
                            <?endif;?>
                            <li><a href="<?=WEBRoot?>/gift/ListeCadeaux">Liste des articles</a></li>
                            <li><a href="<?=WEBRoot?>/gift/AddCadeaux">Ajouter un article</a></li>
                            <li><a href="<?=WEBRoot?>/gift/listeBnEntree">Liste des bons d'entrée</a></li>
                            <? if($_SESSION['user']['type']<=102): ?>
                                <li><a href="<?=WEBRoot?>/gift/Quota">Quota</a></li>
                            <?endif;?>
                            <!--<li><a href="<?=WEBRoot?>/gift/AddDemande">Ajouter une demande</a></li>-->
                        </ul>
                    </li>
                    <li class="<?if($ActivePage[0]=="fournisseur") echo 'active'?>">
                        <a href="<?=WEBRoot?>/fournisseur/index">
                            <i class="fa fa-mortar-board"></i>
                            Gestion fournisseur
                        </a>
                    </li>
            <?php if ($_SESSION['user']['id']==9){ ?>
                    <li class="treeview <?if($ActivePage[0]=="gestionDesDemandes") echo 'active'?>">
                        <a href="#"><i class="fa fa-codepen"></i> <span>Gestion des demandes</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?=WEBRoot?>/gestionDesDemandes/Liste">Demandes cadeaux</a></li>

                        </ul>
                    </li>
                    <?php  } ?>
                 <?php  } ?>

           <!---- -->
            <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>-->
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>