
<div class="modal inmodal fade" id="modal-rangement<?= $production->id ?>" style="z-index: 99999999">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Rangement de la production</h4>
            <small class="font-bold">La production du <?= datecourt($production->ladate)  ?></small>
        </div>
        
        <form class="formRangement">

            <div class="ibox">
                <div class="ibox-content"><br>
                    <div class="row text-center">
                        <?php foreach ($production->ligneproductionchantiers as $key => $ligne) {
                            $ligne->actualise(); ?>
                            <div class="col-sm-4 col-md-3" style="margin-bottom: 3%">
                                <label><b><?= $ligne->produit->name() ?></b> rangée <span class="text-muted gras"> / <?= $ligne->quantite ?></span></label>
                                <input type="number" value="<?= $ligne->quantite ?>" min=0 max="<?= $ligne->quantite ?>" number class="gras form-control text-center" name="range-<?= $ligne->produit->id ?>">
                            </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>

            <hr>
            <div class="container">
                <input type="hidden" name="id" value="<?= $production->id ?>">
                <button type="button" class="btn btn-sm  btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Annuler</button>
                <button class="btn dim btn-primary pull-right"><i class="fa fa-check"></i> Valider le rangement</button>
            </div>
            <br>
        </form>
    </div>


</div>
</div>


