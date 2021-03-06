<?php
$reste = $tricycle->resteAPayer();
?>
<div class="modal inmodal fade" id="modal-paye-tricycle<?= $tricycle->getId() ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Payement du tricyle</h4>
            </div>
            <form method="POST" class="formShamman" classname="payementtricycle">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Montant à payer</label>
                            <div class="form-group">
                                <input type="number" number class="form-control" name="montant" value="<?= $reste  ?>" max="<?= $reste  ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Mode de payement </label>
                            <div class="form-group">
                                <?php Native\BINDING::html("select", "modepayement"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row modepayement_facultatif">
                        <div class="col-sm-6">
                            <label>Structure d'encaissement<span style="color: red">*</span> </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-bank"></i></span><input type="text" name="structure" class="form-control">
                            </div>
                        </div><br>
                        <div class="col-sm-6">
                            <label>N° numero dédié<span style="color: red">*</span> </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span><input type="text" name="numero" class="form-control">
                            </div>
                        </div>
                    </div>
                </div><hr>
                <div class="container">
                    <input type="hidden" name="id" >
                    <input type="hidden" name="tricycle_id" value="<?= $tricycle->getId() ?>">
                    <button type="button" class="btn btn-sm  btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Annuler</button>
                    <button class="btn btn-sm btn-primary dim pull-right"><i class="fa fa-money"></i> Faire la paye</button>
                </div>
                <br>
            </form>
        </div>
    </div>
</div>