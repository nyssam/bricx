<!DOCTYPE html>
<html>

<?php include($this->rootPath("webapp/master/elements/templates/head.php")); ?>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom white-bg">
                <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
                    <!--<div class="navbar-header">-->
                        <!--<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">-->
                            <!--<i class="fa fa-reorder"></i>-->
                            <!--</button>-->

                            <a href="#" class="navbar-brand " style="padding: 3px 15px;"><h1 class="mp0 gras" style="font-size: 45px">BRICX</h1></a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fa fa-reorder"></i>
                            </button>

                            <!--</div>-->
                            <div class="navbar-collapse collapse" id="navbar">
                                <ul class="nav navbar-nav mr-auto">
                                    <li class="gras <?= (isJourFerie(dateAjoute(1)))?"text-red":"text-muted" ?>">
                                        <span class="m-r-sm welcome-message text-uppercase" id="date_actu"></span> 
                                        <span class="m-r-sm welcome-message gras" id="heure_actu"></span> 
                                    </li>

                                </ul>
                                <ul class="nav navbar-top-links navbar-right">
                                    <li class=""><a class="dropdown-item text-red" href="#" id="btn-deconnexion" ><i class="fa fa-sign-out"></i> Déconnexion</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>


                    <div class="wrapper-content">
                        <div class="animated fadeInRightBig">

                            <div class=" border-bottom white-bg dashboard-header">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center" style="margin-top: 15%;">
                                            <img src="<?= $this->stockage("images", "societe", $params->image) ?>" style="width: 70%;" alt=""><br>
                                            <h2 class="text-uppercase"><?= $params->societe ?></h2><br>
                                        </div>
                                    </div>
                                    <div class="col-md-9 border-left">
                                        <div class="row text-center">
                                            <div class="col-sm-4 border-left border-bottom">
                                                <div class="p-lg">
                                                    <i class="fa fa-hospital-o fa-4x text-orange"></i>
                                                    <h1 class="m-xs"><?= start0(count(Home\CHANTIER::getAll()))  ?></h1>
                                                    <h4 class="no-margins text-uppercase gras">Chantiers</h4>
                                                    <small><?= $params->societe ?></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border-left border-bottom">
                                                <div class="p-lg">
                                                    <i class="fa fa-bank fa-4x text-green"></i>
                                                    <h1 class="m-xs"><?= start0(count(Home\AGENCE::getAll()))  ?></h1>
                                                    <h4 class="no-margins text-uppercase gras">Entrepôts</h4>
                                                    <small><?= $params->societe ?></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border-left border-bottom">
                                                <div class="p-lg">
                                                    <i class="fa fa-cube fa-4x text-orange"></i>
                                                    <h1 class="m-xs"><?= start0(count(Home\PRODUIT::findBy(["isActive ="=>Home\TABLE::OUI])))  ?></h1>
                                                    <h4 class="no-margins text-uppercase gras">Produits</h4>
                                                    <small><?= $params->societe ?></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border-left">
                                                <div class="p-lg">
                                                    <i class="fa fa-users fa-4x text-green"></i>
                                                    <h1 class="m-xs"><?= start0(count([]))  ?></h1>
                                                    <h4 class="no-margins text-uppercase gras">CLients</h4>
                                                    <small><?= $params->societe ?></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border-left">
                                                <div class="p-lg">
                                                    <i class="fa fa-bicycle fa-4x text-orange"></i>
                                                    <h1 class="m-xs"><?= start0(count(Home\VEHICULE::getAll() ) - 2)  ?></h1>
                                                    <h4 class="no-margins text-uppercase gras">Véhicules de livraisons </h4>
                                                    <small><?= $params->societe ?></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border-left">
                                                <div class="p-lg">
                                                    <i class="fa fa-male fa-4x text-green"></i>
                                                    <h1 class="m-xs"><?= start0(count(Home\EMPLOYE::getAll()))  ?></h1>
                                                    <h4 class="no-margins text-uppercase gras">Utilisateurs</h4>
                                                    <small><?= $params->societe ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><br>


                            <div class="row justify-content-center">

                                <?php if ($employe->isAutoriser("chantier")) { ?>
                                    <div class="col-lg-3">
                                        <a href="<?= $this->url("chantier", "master", "chantiers")  ?>">
                                            <div class="ibox">
                                                <div class="ibox-content">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="text-uppercase">Construction</h5>
                                                            <h4 class="no-margins text-orange">Mes chantiers</h4>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <i class="fa fa-hospital-o fa-4x text-green"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if ($employe->isAutoriser("manager")) { ?>
                                    <div class="col-lg-3">
                                        <a href="<?= $this->url("manager", "master", "dashboard")  ?>">
                                            <div class="ibox">
                                                <div class="ibox-content">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="text-uppercase">Construction</h5>
                                                            <h4 class="no-margins text-dark">Gestion générale</h4>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <i class="fa fa-pied-piper-alt fa-4x text-warning"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if ($employe->isAutoriser("config")) { ?>
                                    <div class="col-lg-3">
                                        <a href="<?= $this->url("config", "master", "dashboard")  ?>">
                                            <div class="ibox">
                                                <div class="ibox-content">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="text-uppercase">Configuration</h5>
                                                            <h4 class="no-margins text-dark">Config technique</h4>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <i class="fa fa-gears fa-4x text-danger" style="color: #ddd"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>

                    <?php include($this->rootPath("webapp/master/elements/templates/footer.php")); ?>


                </div>
            </div>


            <?php include($this->rootPath("webapp/master/elements/templates/script.php")); ?>

        </body>



        </html>