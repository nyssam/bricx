<!DOCTYPE html>
<html>

<?php include($this->rootPath("webapp/main/elements/templates/head.php")); ?>

<body class="" style="background-image: url(<?= $this->stockage("images", "societe", "fond.jpg") ?>); background-size: 100%;">

    <div class="loginColumns animated fadeInDown" style="z-index: 100000">

        <div class="row">
            <div class="col-md-6 text-white">

                <h2 class="font-bold text-uppercase"><i class="fa fa-shield"></i> espace de connexion</h2>
                <br>
                <h3>
                    <p>Veuillez entrer vos informations de connexion pour avoir accès à la plateforme de gestion.</p><br><br>

                    <p>Si vous avez oublié ou perdu votre mot de passe, veuillez contacter votre administrateur pour qu'il le reinitialise pour vous !</p><br>

                    <p>Pour toute autre chose, veuillez contacter votre administrateur système.</p>
                </h3>

            </div>
            <div class="col-md-6">
                <div class="ibox-content text-center">
                    <img style="width: 150px" src="<?= $this->stockage("images", "societe", $params->image) ?>">
                    <h1 class="logo-name text-center" style="font-size: 90px; letter-spacing: 5px; margin: 0% !important; padding: 0% !important;">BRICX</h1>
                    <form method="POST" class="m-t" role="form" id="formConnexion" >
                        <div class="form-group">
                            <input type="text" name="login" class="form-control" placeholder="identifiant" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="mot de passe" required>
                        </div><br>
                        <button type="submit" class="btn btn-primary block full-width m-b dim"><i class="fa fa-check"></i> Se connecter</button>

                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row text-white">
            <div class="col-md-6">
                &copy; DEVARIS 21, tous droits reservés
            </div>
            <div class="col-md-6 text-right">
             <small>© 2019-2020</small>
         </div>
     </div>

 </div>


 <div class="modal inmodal" id="modal-newUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Prémière connexion</h4>
            </div>
            <form id="formNewUser" method="post">
                <div class="modal-body">                
                    <p class="text-center">Bienvenue sur votre espace d'administration.<br> Pour plus de sécurité, vous devez changer vos paramètres de connexion pour que vous soyez le seul à les connaitre !</p><br>
                    <div class="form-group">
                        <label>Nouvel identifiant</label>
                        <input type="text" name="login" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="pass0" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Corfirmer mot de passe</label>
                        <input type="password" name="pass" required class="form-control">
                    </div>
                </div>
                <div class="">
                    <br>
                    <div class="container">
                        <button type="button" class="btn btn-default dim pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Annuler</button>
                        <button class="btn btn-primary dim pull-right"><i class="fa fa-check"></i> Je suis prêt !</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<?php include($this->rootPath("webapp/main/elements/templates/script.php")); ?>

</body>

</html>
