$(function(){

	$("select[name=id]").change(function(){
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		var id = $(this).val();
		var formdata = new FormData();
		formdata.append('id', id);
		formdata.append('action', "changer");
		$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
			window.location.href = data.url;
		}, "json")
	})


	newcommande = function(){
		alerty.confirm("Une ou plusieurs commandes sont déjà en cours, voulez-vous continuer avec l'une d'entre elles ?", {
			title: "Nouvelle commande",
			cancelLabel : "Non, une nouvelle commande",
			okLabel : "OUI, continuer avec elles",
		}, function(){			
			modal("#modal-listecommande");
		}, function(){
			session("commande-encours", null);
			modal("#modal-newcommande");
		})
	}

	chosir = function(id){
		session('commande-encours', id);
		$("#modal-listecommande").modal("hide")
		modal("#modal-newcommande");
	}

	fichecommande = function(id){	
		Loader.start();	
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		$.post(url, {action:"fichecommande", id:id}, (data)=>{
			$("body #modal-groupecommande").remove();
			$("body").append(data);
			$("body #modal-groupecommande").modal("show");
			$("select.select2").select2();
			Loader.stop();	
		},"html");
	}

	newlivraison = function(id){	
		Loader.start();	
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		$.post(url, {action:"newlivraison", id:id}, (data)=>{
			$("body #modal-newlivraison").remove();
			$("body").append(data);
			$("body #modal-newlivraison").modal("show");
			$("select.select2").select2();
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
			});
			$("div.tricycle").hide()
			$("div.location").hide()
			$("div.chauffeur").hide()
			$("div.montant_location").hide()
			Loader.stop();	
		},"html");
	}

	changement = function(id){	
		Loader.start();	
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		$.post(url, {action:"changement", id:id}, (data)=>{
			$("body #modal-changement").remove();
			$("body").append(data);
			$("body #modal-changement").modal("show");
			$("select.select2").select2();
			Loader.stop();	
		},"html");
	}

	newProgrammation = function(id){	
		Loader.start();	
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		$.post(url, {action:"newProgrammation", id:id}, (data)=>{
			$("body #modal-programmation").remove();
			$("body").append(data);
			$("body #modal-programmation").modal("show");
			$("select.select2").select2();
			Loader.stop();	
		},"html");
	}



	fairenewcommande = function(id){	
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		$.post(url, {action:"modalcommande", id:id}, (data)=>{
			$("body #modal-newcommande").remove();
			$("body").append(data);
			$("body #modal-newcommande").modal("show");
			$("select.select2").select2();
			$("div.modepayement_facultatif").hide();
			Loader.stop();	
		},"html");
	}



		//nouvelle commande
		$("body").on("click", ".newproduit", function(event) {
			var url = "../../webapp/boutique/modules/master/client/ajax.php";
			var id = $(this).attr("data-id");
			var zone = $("select[name=zonelivraison_id]").val();
			$.post(url, {action:"newproduit", id:id, zone:zone}, (data)=>{
				$("tbody.commande").append(data);
				$("button[data-id ="+id+"]").hide(200);
				calcul()
			},"html");
		});


		calcul = function(){
			var url = "../../webapp/boutique/modules/master/client/ajax.php";
			var formdata = new FormData($("#formCommande")[0]);
			var tableau = new Array();
			$("#modal-newcommande .commande tr").each(function(index, el) {
				var id = $(this).attr('data-id');
				var val = $(this).find('input').val();
				var item = id+"-"+val;
				tableau.push(item);
			});
			var zone = $("#modal-newcommande select[name=zonelivraison_id]").val();
			formdata.append('tableau', tableau);
			formdata.append('zone', zone);
			formdata.append('action', "calcul");
			$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
				$("#modal-newcommande tbody.commande").html(data);

				formdata.append('action', "total");
				$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
					$("#modal-newcommande .tva").html(data.tva);
					$("#modal-newcommande .montant").html(data.montant);
					$("#modal-newcommande .total").html(data.total);
				}, 'json')
			}, 'html')
			return formdata;
		}


		$("body").on("change", "tbody.commande input, select[name=zonelivraison_id]", function() {
			calcul();
		})


		supprimeProduit = function(id){
			var url = "../../webapp/boutique/modules/master/client/ajax.php";
			$.post(url, {action:"supprimeProduit", id:id}, (data)=>{
				$("tbody.commande tr#ligne"+id).hide(400).remove();
				$("button[data-id ="+id+"]").show(200);
				calcul()
			},"html");
		}



		validerCommande = function(){
			var formdata = new FormData($("#formCommande")[0]);
			tableau = new Array();
			$("#modal-newcommande .commande tr").each(function(index, el) {
				var id = $(this).attr('data-id');
				var val = $(this).find('input').val();
				var item = id+"-"+val;
				tableau.push(item);
			});
			formdata.append('listeproduits', tableau);

			alerty.confirm("Voulez-vous vraiment valider la commande ?", {
				title: "Validation de la commande",
				cancelLabel : "Non",
				okLabel : "OUI, valider",
			}, function(){
				Loader.start();
				var url = "../../webapp/boutique/modules/master/client/ajax.php";
				formdata.append('action', "validerCommande");
				$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
					if (data.status) {
						window.open(data.url, "_blank");
						window.location.reload();
						window.open(data.url1, "_blank");
					}else{
						Alerter.error('Erreur !', data.message);
					}
				}, 'json')
			})
		}


		annulerCommande = function(id){
			alerty.confirm("Voulez-vous vraiment annuler cette commande. \n Elle implique la suppression de la facture associé, et l'annulation de la dette si il y a! \n Voulez-vous vraiment continuer ?", {
				title: "Annuler la commande",
				cancelLabel : "Non",
				okLabel : "OUI, annuler",
			}, function(){
				var url = "../../webapp/boutique/modules/master/client/ajax.php";
				alerty.prompt("Entrer votre mot de passe pour confirmer l'opération !", {
					title: 'Récupération du mot de passe !',
					inputType : "password",
					cancelLabel : "Annuler",
					okLabel : "Valider"
				}, function(password){
					Loader.start();
					$.post(url, {action:"annulerCommande", id:id, password:password}, (data)=>{
						if (data.status) {
							window.location.reload()
						}else{
							Alerter.error('Erreur !', data.message);
						}
					},"json");
				})
			})
		}


		validerLivraison = function(){
			var formdata = new FormData($("#formLivraison")[0]);
			var tableau = new Array();
			$("#modal-newlivraison .commande tr").each(function(index, el) {
				var id = $(this).attr('data-id');
				var livree = $(this).find('input[name=livree]').val();
				var surplus = $(this).find('input[name=surplus]').val();
				var perte = $(this).find('input[name=perte]').val();
				var item = id+"-"+livree+"-"+surplus+"-"+perte;
				tableau.push(item);
			});
			formdata.append('listeproduits', tableau);

			alerty.confirm("Voulez-vous vraiment confirmer la livraison de ces produits ?", {
				title: "livraison de la commande",
				cancelLabel : "Non",
				okLabel : "OUI, livrer",
			}, function(){
				Loader.start();
				var url = "../../webapp/boutique/modules/master/client/ajax.php";
				formdata.append('action', "livraisonCommande");
				$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
					if (data.status) {
						window.open(data.url, "_blank");
						window.location.reload();
					}else{
						Alerter.error('Erreur !', data.message);
					}
				}, 'json')
			})
		}



		validerChangement = function(){
			var formdata = new FormData($("#formChangement")[0]);
			var tableau = new Array();
			$("#modal-changement .commande tr").each(function(index, el) {
				var id = $(this).attr('data-id');
				var val = $(this).find('input').val();
				var item = id+"-"+val;
				tableau.push(item);
			});
			formdata.append('tableau', tableau);

			alerty.confirm("Voulez-vous vraiment confirmer la changement de ces produits ?", {
				title: "Changement de produits",
				cancelLabel : "Non",
				okLabel : "OUI, changer",
			}, function(){
				Loader.start();
				var url = "../../webapp/boutique/modules/master/client/ajax.php";
				formdata.append('action', "validerChangement");
				$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
					if (data.status) {
						window.location.reload();
					}else{
						Alerter.error('Erreur !', data.message);
					}
				}, 'json')
			})
		}



		validerProgrammation = function(){
			var formdata = new FormData($("#formLivraison")[0]);
			var tableau = new Array();
			$("#modal-programmation .commande tr").each(function(index, el) {
				var id = $(this).attr('data-id');
				var format = $(this).attr('data-format');
				var val = $(this).find('input').val();
				var item = id+"-"+format+"-"+val;
				tableau.push(item);
			});
			formdata.append('tableau', tableau);
			formdata.append('datelivraison', $("#modal-programmation input[name=datelivraison]").val());

			alerty.confirm("Voulez-vous vraiment confirmer la programmation de cette ivraison ?", {
				title: "Programmation de la livraison",
				cancelLabel : "Non",
				okLabel : "OUI, programmer",
			}, function(){
				Loader.start();
				var url = "../../webapp/boutique/modules/master/client/ajax.php";
				formdata.append('action', "validerProgrammation");
				$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
					if (data.status) {
						window.location.reload();
					}else{
						Alerter.error('Erreur !', data.message);
					}
				}, 'json')
			})
		}


		$("#formReglerCommande").submit(function(event) {
			$this = $(this);
			var url = "../../webapp/boutique/modules/master/client/ajax.php";
			alerty.confirm("Voulez-vous vraiment valider le payement ?", {
				title: "Recouvrement de la commande",
				cancelLabel : "Non",
				okLabel : "OUI, valider",
			}, function(){
				alerty.prompt("Entrer votre mot de passe pour confirmer l'opération !", {
					title: 'Récupération du mot de passe !',
					inputType : "password",
					cancelLabel : "Annuler",
					okLabel : "Valider"
				}, function(password){
					var formdata = new FormData($this[0]);
					formdata.append('password', password);
					formdata.append('action', "formReglerCommande");
					Loader.start();
					$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
						if (data.status) {
							window.open(data.url, "_blank");
							window.location.reload();
						}else{
							Alerter.error('Erreur !', data.message);
						}
					}, 'json')
				})
			})
			return false;
		});


		$("#formAcompte").submit(function(event) {
			var url = "../../webapp/boutique/modules/master/client/ajax.php";
			alerty.confirm("Voulez-vous vraiment créditer ce montant sur ce compte ?", {
				title: "Créditer l'acompte",
				cancelLabel : "Non",
				okLabel : "OUI, créditer",
			}, function(){
				alerty.prompt("Entrer votre mot de passe pour confirmer l'opération !", {
					title: 'Récupération du mot de passe !',
					inputType : "password",
					cancelLabel : "Annuler",
					okLabel : "Valider"
				}, function(password){
					var formdata = new FormData($("#formAcompte")[0]);
					formdata.append('password', password);
					formdata.append('action', "acompte");
					Loader.start();
					$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
						if (data.status) {
							window.open(data.url, "_blank");
							window.location.reload();
						}else{
							Alerter.error('Erreur !', data.message);
						}
					}, 'json')
				})
			})
			return false;
		});


		reglerToutesDettes = function(id){
			alerty.confirm("Voulez-vous vraiment regler toutes les dettes de ce client ? \n Le Recouvrement se fera via l'acompte de celui-ci. veuillez donc l'approvisionner. \n Le recouvrement se fera également dans la limite des fonds disponibles", {
				title: "Recouvrement de dettes",
				cancelLabel : "Non",
				okLabel : "OUI, valider",
			}, function(){
				var url = "../../webapp/boutique/modules/master/client/ajax.php";
				alerty.prompt("Entrer votre mot de passe pour confirmer l'opération !", {
					title: 'Récupération du mot de passe !',
					inputType : "password",
					cancelLabel : "Annuler",
					okLabel : "Valider"
				}, function(password){
					Loader.start();
					$.post(url, {action:"reglerToutesDettes", id:id, password:password}, (data)=>{
						if (data.status) {
							window.location.reload()
						}else{
							Alerter.error('Erreur !', data.message);
						}
					},"json");
				})
			})
		}


	// $("#formDette").submit(function(event) {
	// 	var url = "../../webapp/boutique/modules/master/client/ajax.php";
	// 	alerty.confirm("Voulez-vous vraiment faire le réglement de ce montant ?", {
	// 		title: "Reglement de dette",
	// 		cancelLabel : "Non",
	// 		okLabel : "OUI, régler la dette",
	// 	}, function(){
	// 		alerty.prompt("Entrer votre mot de passe pour confirmer l'opération !", {
	// 			title: 'Récupération du mot de passe !',
	// 			inputType : "password",
	// 			cancelLabel : "Annuler",
	// 			okLabel : "Valider"
	// 		}, function(password){
	// 			var formdata = new FormData($("#formDette")[0]);
	// 			formdata.append('password', password);
	// 			formdata.append('action', "dette");
	// 			Loader.start();
	// 			$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
	// 				if (data.status) {
	// 					if (data.url != null) {
	// 						window.open(data.url, "_blank");
	// 					}
	// 					window.location.reload();
	// 				}else{
	// 					Alerter.error('Erreur !', data.message);
	// 				}
	// 			}, 'json')
	// 		})
	// 	})
	// 	return false;
	// });


	$("#formRembourser").submit(function(event) {
		var url = "../../webapp/boutique/modules/master/client/ajax.php";
		alerty.confirm("Voulez-vous vraiment rembourser ce montant à ce client ?", {
			title: "rembourser l'acompte",
			cancelLabel : "Non",
			okLabel : "OUI, créditer",
		}, function(){
			alerty.prompt("Entrer votre mot de passe pour confirmer l'opération !", {
				title: 'Récupération du mot de passe !',
				inputType : "password",
				cancelLabel : "Annuler",
				okLabel : "Valider"
			}, function(password){
				var formdata = new FormData($("#formRembourser")[0]);
				formdata.append('password', password);
				formdata.append('action', "rembourser");
				Loader.start();
				$.post({url:url, data:formdata, contentType:false, processData:false}, function(data){
					if (data.status) {
						window.open(data.url, "_blank");
						window.location.reload();
					}else{
						Alerter.error('Erreur !', data.message);
					}
				}, 'json')
			})
		})
		return false;
	});


	$('.input-group.date').datepicker({
		autoclose: true,
		format: "dd MM yyyy",
		language: "fr"
	});

})