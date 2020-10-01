<!DOCTYPE html>
<html>

<?php include($this->rootPath("webapp/boutique/elements/templates/head.php")); ?>


<body class="fixed-sidebar">

    <div id="wrapper">

        <?php include($this->rootPath("webapp/boutique/elements/templates/sidebar.php")); ?>  

        <div id="page-wrapper" class="gray-bg">

            <?php include($this->rootPath("webapp/boutique/elements/templates/header.php")); ?>  


            <div class="wrapper wrapper-content">
                <div class="animated fadeInRightBig">
                    <div class="ibox">
                       <div class="ibox-title">
                        <h5>Recapitulatif de la journée</h5>
                        <div class="ibox-tools">
                            <form id="formFiltrer" method="POST">
                                <div class="row" style="margin-top: -1%">
                                    <div class="col-8">
                                        <input type="date" value="<?= $date ?>" class="form-control input-sm" name="date">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" onclick="filtrer()" class="btn btn-sm btn-white"><i class="fa fa-search"></i> Filtrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-4">
                                <img style="width: 20%" src="<?= $this->stockage("images", "societe", $params->image) ?>">
                            </div>
                            <div class="col-sm-8 text-right">
                                <h2 class="title text-uppercase gras">Recapitulatif de la journée</h2>
                                <h3>Du <?= datecourt3($date) ?></h3>
                            </div>
                        </div><hr><br>

                        <div class="row">
                            <div class="col-sm-9" style="border-right: 2px solid black">

                                <div>
                                   <?php if ($employe->isAutoriser("production")) { ?>
                                    <h3 class="text-uppercase text-center">Commandes</h3>
                                    <?php if (count($commandes) > 0) { ?>
                                        <div class="row">
                                            <?php foreach ($commandes as $key => $commande) { 
                                                $commande->actualise();
                                                $datas = $commande->fourni("lignecommande"); ?>
                                                <div class="col-md-6">
                                                    <div class="text-left">
                                                        <h6 class="mp0"><span>Zone de livraison :</span> <span class="text-uppercase"><?= $commande->zonelivraison->name() ?></span></h6>   
                                                        <h6 class="mp0"><span>Lieu de livraison :</span> <span class="text-uppercase"><?= $commande->lieu ?></span></h6>                              
                                                        <h6 class="mp0"><span>Client :</span> <span class="text-uppercase"><?= $commande->groupecommande->client->name() ?></span></h6>
                                                    </div>
                                                    <table class="table table-bordered mp0">
                                                        <thead>
                                                            <tr>
                                                                <?php foreach ($commande->lignecommandes as $key => $ligne) { 
                                                                    if ($ligne->quantite > 0) {
                                                                        $ligne->actualise(); ?>
                                                                        <th class="text-center"><?= $ligne->produit->name() ?></th>
                                                                    <?php }
                                                                } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <?php foreach ($commande->lignecommandes as $key => $ligne) {
                                                                    if ($ligne->quantite > 0) { ?>
                                                                        <td class="text-center"><?= $ligne->quantite ?></td>
                                                                    <?php   } 
                                                                } ?>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <span class="mp0 pull-right"><span>Coût :</span> <span class="text-uppercase"><?= money($commande->montant) ?> <?= $params->devise ?></span></span>
                                                </div>
                                                <hr>
                                            <?php } ?>
                                        </div>
                                    <?php }else{ ?>
                                        <p class="text-center text-muted italic">Aucune commande ce jour </p>
                                    <?php } ?>
                                </div>

                                <hr><br>

                                <div class="">
                                    <h3 class="text-uppercase text-center">livraisons</h3>
                                    <?php if (count($livraisons) > 0) { ?>
                                        <div class="row">
                                            <?php foreach ($livraisons as $key => $livraison) { 
                                                $livraison->actualise();
                                                $datas = $livraison->fourni("lignelivraison"); ?>
                                                <div class="col-md-6">
                                                    <div class="text-left">
                                                        <h6 class="mp0"><span>Zone de livraison :</span> <span class="text-uppercase"><?= $livraison->zonelivraison->name() ?></span></h6>                            
                                                        <h6 class="mp0"><span>Client :</span> <span class="text-uppercase"><?= $livraison->groupecommande->client->name() ?></span></h6>
                                                        <h6 class="mp0"><span>Chauffeur :</span> <span class="text-uppercase"><?= $livraison->chauffeur->name() ?></span></h6>
                                                    </div>
                                                    <table class="table table-bordered mp0">
                                                        <thead>
                                                            <tr>
                                                                <?php foreach ($livraison->lignelivraisons as $key => $ligne) { 
                                                                    if ($ligne->quantite > 0) {
                                                                        $ligne->actualise(); ?>
                                                                        <th colspan="2" class="text-center"><?= $ligne->produit->name() ?></th>
                                                                    <?php }
                                                                } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <?php foreach ($livraison->lignelivraisons as $key => $ligne) {
                                                                    if ($ligne->quantite > 0) { ?>
                                                                        <td data-toogle="tooltip" title="effectivement livré" class="  text-center text-green"><?= $ligne->quantite_livree ?></td>
                                                                        <td data-toogle="tooltip" title="perte" class="text-center text-red"><?= $ligne->perte ?></td>
                                                                    <?php   } 
                                                                } ?>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <h6 class="mp0 pull-right"><span>Véhicule :</span> <span class="text-uppercase"><?= $livraison->vehicule->name() ?></span></h6>
                                                </div>
                                                <hr>
                                            <?php } ?>
                                        </div>
                                    <?php }else{ ?>
                                        <p class="text-center text-muted italic">Aucune livraison ce jour </p>
                                    <?php } ?>
                                </div> <hr>

                            <?php } ?>


                            <?php if ($employe->isAutoriser("caisse")) { ?>
                                <div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover ">
                                            <thead>
                                                <tr class="text-center text-uppercase">
                                                    <th colspan="2" style="visibility: hidden; width: 62%"></th>
                                                    <th>Entrée</th>
                                                    <th>Sortie</th>
                                                    <th>Résultats</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tableau">
                                                <tr>
                                                    <td colspan="2">Repport du solde </td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td style="background-color: #fafafa" class="text-center"><?= money($repport = $last = $comptebanque->solde(Home\PARAMS::DATE_DEFAULT , dateAjoute1($date, -1))) ?> <?= $params->devise ?></td>
                                                </tr>
                                                <?php foreach ($mouvements as $key => $mouvement) {  ?>
                                                    <tr>
                                                        <td class="text-center" width="15"><a target="_blank" href="<?= $this->url("fiches", "master", "boncaisse", $mouvement->id)  ?>"><i class="fa fa-file-text-o fa-2x"></i></a> 
                                                        </td>
                                                        <td>
                                                            <h6 style="margin-bottom: 3px" class="mp0 text-uppercase gras <?= ($mouvement->typemouvement_id == Home\TYPEMOUVEMENT::DEPOT)?"text-green":"text-red" ?>"><?= $mouvement->name() ?>  

                                                            <?php if ($employe->isAutoriser("modifier-supprimer")) { ?>
                                                                |
                                                                &nbsp;&nbsp;<i onclick="modifierOperation(<?= $mouvement->id ?>)" class="cursor fa fa-pencil text-dark"></i> 
                                                                &nbsp;&nbsp;<i class="cursor fa fa-close text-red" onclick="suppressionWithPassword('operation', <?= $mouvement->id ?>)"></i>
                                                            <?php } ?>

                                                            <span class="pull-right"><i class="fa fa-clock-o"></i> <?= datelong($mouvement->created) ?></span>
                                                        </h6>
                                                        <i><?= $mouvement->comment ?> ## <u style="font-size: 9px; font-style: italic;"><?= $mouvement->structure ?> - <?= $mouvement->numero ?></u></i>
                                                    </td>
                                                    <?php if ($mouvement->typemouvement_id == Home\TYPEMOUVEMENT::DEPOT) { ?>
                                                        <td class="text-center text-green gras" style="padding-top: 12px;">
                                                            <?= money($mouvement->montant) ?> <?= $params->devise ?>
                                                        </td>
                                                        <td class="text-center"> - </td>
                                                    <?php }elseif ($mouvement->typemouvement_id == Home\TYPEMOUVEMENT::RETRAIT) { ?>
                                                        <td class="text-center"> - </td>
                                                        <td class="text-center text-red gras" style="padding-top: 12px;">
                                                            <?= money($mouvement->montant) ?> <?= $params->devise ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php $last += ($mouvement->typemouvement_id == Home\TYPEMOUVEMENT::DEPOT)? $mouvement->montant : -$mouvement->montant ; ?>
                                                    <td class="text-center gras" style="padding-top: 12px; background-color: #fafafa"><?= money($last) ?> <?= $params->devise ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr style="height: 15px;"></tr>
                                            <tr>
                                                <td style="border-right: 2px dashed grey" colspan="2"><h4 class="text-uppercase mp0 text-right">Total des comptes au <?= datecourt(dateAjoute()) ?></h4></td>
                                                <td><h3 class="text-center text-green"><?= money(comptage($entrees, "montant", "somme") + $repport) ?> <?= $params->devise ?></h3></td>
                                                <td><h3 class="text-center text-red"><?= money(comptage($depenses, "montant", "somme")) ?> <?= $params->devise ?></h3></td>
                                                <td style="background-color: #fafafa"><h3 class="text-center text-blue gras"><?= money($last) ?> <?= $params->devise ?></h3></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>


                    </div>
                    <div class="col-sm-3 text-right">
                        <h4 class="text-uppercase">Employés connectés</h4>
                        <ul>
                            <?php foreach ($employes as $key => $emp) { 
                                $emp->actualise();  ?>
                                <li><?= $emp->name(); ?></li>
                            <?php } ?>
                        </ul><br>
                        <hr>


                        <?php if ($employe->isAutoriser("caisse")) { ?>
                            <h4 class="text-uppercase">SOLDE DU COMPTE</h4>
                            <div class="">
                                <small>Solde en Ouverture</small>
                                <h2 class="no-margins"><?= money(Home\OPERATION::resultat(Home\PARAMS::DATE_DEFAULT , dateAjoute1($date, -1))) ?> <?= $params->devise ?></h2>
                                <div class="progress progress-mini">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div><br>

                            <small>Entrées du jour</small>
                            <h3 class="no-margins text-green"><?= money(Home\OPERATION::entree(dateAjoute() , dateAjoute(+1))) ?></h3>
                            <br>

                            <small>Dépenses du jour</small>
                            <h3 class="no-margins text-red"><?= money(Home\OPERATION::sortie(dateAjoute() , dateAjoute(+1))) ?></h3>
                            <br>

                            <div class="">
                                <small>Solde à la fermeture</small>
                                <h2 class="no-margins"><?= money(Home\OPERATION::resultat(Home\PARAMS::DATE_DEFAULT , $date)) ?> <?= $params->devise ?></h2>
                                <div class="progress progress-mini">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                        <br>

                        <h4 class="text-uppercase">COMMENTAIRE</h4>
                        <p class="text-justify"><?= $production->comment ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include($this->rootPath("webapp/boutique/elements/templates/footer.php")); ?>


</div>
</div>


<?php include($this->rootPath("webapp/boutique/elements/templates/script.php")); ?>


</body>

</html>