

<div class="modal inmodal fade" id="modal-pertemateriel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-red">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Enregistrer une perte</h4>
                <small>Veuillez renseigner les informations pour enregistrer la perte</small>
            </div>
            <form method="POST" class="formShamman" classname="pertechantiermateriel">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <label>Matériel perdu </label>
                            <div class="form-group">
                                <?php Native\BINDING::html("select", "materiel"); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>Quantité perdue </label>
                            <div class="form-group">
                                <input type="number" step="0.01" number class="form-control" name="quantite" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Cause de la perte </label>
                            <div class="form-group">
                                <?php Native\BINDING::html("select", "typeperte"); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Plus de détails </label>
                            <div class="form-group">
                                <textarea class="form-control" name="comment" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div><hr>
                <div class="container">
                    <input type="hidden" name="id" >
                    <button type="button" class="btn btn-sm  btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Annuler</button>
                    <button class="btn btn-sm btn-danger dim pull-right"><i class="fa fa-money"></i> Enregistrer la perte</button>
                </div>
                <br>
            </form>
        </div>
    </div>
</div>
