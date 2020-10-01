<?php 
namespace Home;
use Native\ROOTER;
require '../../../../../core/root/includes.php';

use Native\RESPONSE;
$params = PARAMS::findLastId();
$rooter = new ROOTER;
$data = new RESPONSE;
extract($_POST);


if ($action == "changer") {
	$data->setUrl("boutique", "master", "client", $id);
	echo json_encode($data);
}


if ($action == "newproduit") {
	$params = PARAMS::findLastId();
	$rooter = new ROOTER;
	$produits = [];
	if (getSession("produits") != null) {
		$produits = getSession("produits"); 
	}
	if (!in_array($id, $produits)) {
		$produits[] = $id;
		$datas = PRODUIT::findBy(["id ="=> $id]);
		if (count($datas) == 1) {
			$produit = $datas[0];
			$produit->fourni("prix_zonelivraison", ["zonelivraison_id ="=> $zone]);
			if (count($produit->prix_zonelivraisons) > 0) {
				$prix = $produit->prix_zonelivraisons[0]->price;
			}else{
				$prix = 1000;
			}
			?>
			<tr class="border-0 border-bottom " id="ligne<?= $id ?>" data-id="<?= $id ?>">
				<td><i class="fa fa-close text-red cursor" onclick="supprimeProduit(<?= $id ?>)" style="font-size: 18px;"></i></td>
				<td >
					<img style="width: 40px" src="<?= $rooter->stockage("images", "produits", $produit->image) ?>">
				</td>
				<td class="text-left">
					<h4 class="mp0 text-uppercase"><?= $produit->name() ?></h4>
					<small><?= $produit->comment ?></small>
				</td>
				<td><h5 class="price" data-price="<?= $prix  ?>"><?= money($prix) ?> <?= $params->devise ?></h5></td>
				<td><h4>X</h4></td>
				<td width="100"><input type="text" number class="form-control text-center gras" value="1" style="padding: 3px"></td>
			</tr>
			<?php
		}
	}
	session("produits", $produits);
}



if ($action == "calcul") {
	$montant = 0;
	$produits = explode(",", $tableau);
	foreach ($produits as $key => $value) {
		$data = explode("-", $value);
		$id = $data[0];
		$val = end($data);
		$datas = PRODUIT::findBy(["id ="=> $id]);
		if (count($datas) == 1) {
			$produit = $datas[0];
			$produit->fourni("prix_zonelivraison", ["zonelivraison_id ="=> $zone]);
			if (count($produit->prix_zonelivraisons) > 0) {
				$prix = $produit->prix_zonelivraisons[0]->price;
			}else{
				$prix = 1000;
			}
			$montant += $prix * $val;
			?>
			<tr class="border-0 border-bottom " id="ligne<?= $id ?>" data-id="<?= $id ?>">
				<td><i class="fa fa-close text-red cursor" onclick="supprimeProduit(<?= $id ?>)" style="font-size: 18px;"></i></td>
				<td >
					<img style="width: 40px" src="<?= $rooter->stockage("images", "produits", $produit->image) ?>">
				</td>
				<td class="text-left">
					<h4 class="mp0 text-uppercase"><?= $produit->name() ?></h4>
					<small><?= $produit->comment ?></small>
				</td>
				<td><h5 class="price" data-price="<?= $prix  ?>"><?= money($prix) ?> <?= $params->devise ?></h5></td>
				<td><h4>X</h4></td>
				<td width="100"><input type="text" number class="form-control text-center gras" value="<?= $val ?>" style="padding: 3px"></td>
				<td class="text-right"><h4 class="" style="font-weight: normal;"><?= money($prix*$val) ?> <?= $params->devise ?></h4></td>
			</tr>
			<?php
		}
	}


	$tva = ($montant * $params->tva) / 100;
	$total = $montant + $tva;


	session("tva", $tva);
	session("montant", $montant);
	session("total", $total);
}



if ($action == "total") {
	$params = PARAMS::findLastId();
	$data = new \stdclass();
	$data->tva = money(getSession("tva"))." ".$params->devise;
	$data->montant = money(getSession("montant"))." ".$params->devise;
	$data->total = money(getSession("total"))." ".$params->devise;
	echo json_encode($data);
}



if ($action == "supprimeProduit") {
	$produits = [];
	if (getSession("produits") != null) {
		$produits = getSession("produits"); 
		foreach ($produits as $key => $value) {
			if ($value == $id) {
				unset($produits[$key]);
			}
			session("produits", $produits);
		}
	}
}




if ($action == "validerCommande") {
	$total = 0;
	$datas = CLIENT::findBy(["id ="=> $client_id]);
	if (count($datas) > 0) {
		$client = $datas[0];
		$listeproduits = explode(",", $listeproduits);
		if (count($listeproduits) > 0) {

			if (getSession("total") > 0) {
				if ($modepayement_id == MODEPAYEMENT::PRELEVEMENT_ACOMPTE || ($modepayement_id != MODEPAYEMENT::PRELEVEMENT_ACOMPTE && intval($avance) <= getSession("total") && intval($avance) > 0)) {
					if ($modepayement_id == MODEPAYEMENT::PRELEVEMENT_ACOMPTE) {
						$avance = $client->acompte ;
					}

					$seuil	= ($client->seuilCredit > 0) ? $client->seuilCredit : $params->seuilCredit;

					if ( ($avance == getSession("total")) || ((getSession("total") - intval($avance) + $client->resteAPayer()) <= $seuil) ) {
						if (getSession("commande-encours") != null) {
							$datas = GROUPECOMMANDE::findBy(["id ="=>getSession("commande-encours")]);
							if (count($datas) > 0) {
								$groupecommande = $datas[0];
								$groupecommande->etat_id = ETAT::ENCOURS;
								$groupecommande->save();
							}else{
								$groupecommande = new GROUPECOMMANDE();
								$groupecommande->hydrater($_POST);
								$groupecommande->enregistre();
							}
						}else{
							$groupecommande = new GROUPECOMMANDE();
							$groupecommande->hydrater($_POST);
							$groupecommande->enregistre();
						}

						$commande = new COMMANDE();
						$commande->hydrater($_POST);
						$commande->groupecommande_id = $groupecommande->id;
						$data = $commande->enregistre();
						if ($data->status) {
							foreach ($listeproduits as $key => $value) {
								$lot = explode("-", $value);
								$id = $lot[0];
								$qte = end($lot);
								$datas = PRODUIT::findBy(["id ="=> $id]);
								if (count($datas) == 1) {
									$produit = $datas[0];
									$produit->fourni("prix_zonelivraison", ["zonelivraison_id ="=> $zonelivraison_id]);
									if (count($produit->prix_zonelivraisons) > 0) {
										$prix = $produit->prix_zonelivraisons[0]->price;
									}else{
										$prix = 1000;
									}

									$lignecommande = new LIGNECOMMANDE;
									$lignecommande->commande_id = $commande->id;
									$lignecommande->produit_id = $id;
									$lignecommande->quantite = $qte;
									$lignecommande->price =  $prix * $qte;
									$lignecommande->enregistre();	
								}
							}

							$total =  getSession("total");

							if ($modepayement_id == MODEPAYEMENT::PRELEVEMENT_ACOMPTE ) {
								if ($client->acompte >= $total) {
									$commande->avance = $total;
								}else{
									$commande->avance = $client->acompte;
								}
								$lot = $client->debiter($total);

							}else{

								if ($total > intval($avance)) {
									$client->dette($total - intval($avance));
								}

								$payement = new REGLEMENTCLIENT();
								$payement->hydrater($_POST);
								$payement->montant = $commande->avance;
								$payement->client_id = $client_id;
								$payement->commande_id = $commande->id;
								$payement->comment = "Réglement de la facture pour la commande N°".$commande->reference;
								$lot = $payement->enregistre();
								$commande->reglementclient_id = $lot->lastid;

								$client->actualise();
								$payement->acompteClient = $client->acompte;
								$payement->detteClient = $client->resteAPayer();
								$payement->save();
							}

							$commande->tva = getSession("tva");
							$commande->reduction = getSession("reduction");
							$commande->montant = $total;
							$commande->reste = $commande->montant - $commande->avance;

							$commande->acompteClient = $client->acompte;
							$data = $commande->save();

							$commande->detteClient = $client->resteAPayer();
							$data = $commande->save();
							$data->setUrl("fiches", "master", "boncommande", $data->lastid);
						}

					}else{
						$data->status = false;
						$data->message = "Le crédit restant pour la commande ne doit pas excéder ".money($seuil)." ".$params->devise." pour ce client ";
					}
				}else{
					$data->status = false;
					$data->message = "Le montant de l'avance de la commande est incorrect, verifiez-le!";
				}
			}else{
				$data->status = false;
				$data->message = "Veuillez verifier le montant de la commande !";
			}
		}else{
			$data->status = false;
			$data->message = "Veuillez selectionner des produits et leur quantité pour passer la commande !";
		}
	}else{
		$data->status = false;
		$data->message = "Erreur lors de la validation de la commande, veuillez recommencer !";
	}
	echo json_encode($data);
}




if ($action == "annulerCommande") {
	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
	if (count($datas) > 0) {
		$employe = $datas[0];
		$employe->actualise();
		if ($employe->checkPassword($password)) {
			$datas = COMMANDE::findBy(["id ="=>$id]);
			if (count($datas) == 1) {
				$commande = $datas[0];
				$data = $commande->annuler();
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'opération! Veuillez recommencer";
			}
		}else{
			$data->status = false;
			$data->message = "Votre mot de passe ne correspond pas !";
		}
	}else{
		$data->status = false;
		$data->message = "Vous ne pouvez pas effectué cette opération !";
	}
	echo json_encode($data);
}




if ($action == "livraisonCommande") {
	$params = PARAMS::findLastId();
	if (getSession("commande-encours") != null) {
		$datas = GROUPECOMMANDE::findBy(["id ="=>getSession("commande-encours")]);
		if (count($datas) > 0) {
			$groupecommande = $datas[0];
			$groupecommande->actualise();


			$listeproduits = explode(",", $listeproduits);
			if (count($listeproduits) > 0) {
				$tests = $listeproduits;
				foreach ($tests as $key => $value) {
					$lot = explode("-", $value);
					$id = $lot[0];
					$qte = $lot[1];
					$surplus = $lot[2];
					$perte = end($lot);
					$produit = PRODUIT::findBy(["id ="=>$id])[0];
					$stock = $produit->stock(PARAMS::DATE_DEFAULT, dateAjoute(1), getSession("agence_connecte_id"));
					if ($qte >= 0 && $groupecommande->reste($produit->id) >= $qte && $qte <= $stock && (($qte + $surplus + $perte) <= $stock)) {
						unset($tests[$key]);
					}
				}
				if (count($tests) == 0) {
					$livraison = new LIVRAISON();
					if ($vehicule_id <= VEHICULE::TRICYCLE) {
						$_POST["chauffeur_id"] = 0;
					}
					$livraison->hydrater($_POST);
					$livraison->groupecommande_id = $groupecommande->id;
					$data = $livraison->enregistre();
					if ($data->status) {
						$montant = 0;

						foreach ($listeproduits as $key => $value) {
							$lot = explode("-", $value);
							$id = $lot[0];
							$qte = $lot[1];
							$surplus = $lot[2];
							$perte = end($lot);

							if ($vehicule_id > VEHICULE::TRICYCLE) {
								$paye = $produit->coutProduction("livraison", $qte);
								if (isset($chargement) && $chargement == "on") {
									$montant += $paye / 2;
								}

								if (isset($dechargement) && $dechargement == "on") {
									$montant += $paye / 2;
								}
							}
							
							$lignelivraison = new LIGNELIVRAISON;
							$lignelivraison->livraison_id = $livraison->id;
							$lignelivraison->produit_id = $id;
							$lignelivraison->quantite = $qte;
							$lignelivraison->surplus = $surplus;
							$lignelivraison->enregistre();

							$laperte = new PERTEPRODUIT;
							$laperte->typeperte_id = TYPEPERTE::CHARGEMENT;
							$laperte->produit_id = $id;
							$laperte->quantite = $perte;
							$laperte->comment = "Perte lors du chargement pour la livraison N°$livraison->reference";
							$laperte->enregistre();
						}

						$production = PRODUCTION::today();
						$production->montant_livraison += $montant;
						$production->save();

//////////////////////////////////////////

						$data = $livraison->save();
						$data->setUrl("fiches", "master", "bonlivraison", $data->lastid);				
					}	
				}else{
					$data->status = false;
					$data->message = "Veuillez à bien vérifier les quantités des différents produits à livrer, certaines sont incorrectes !";
				}
			}else{
				$data->status = false;
				$data->message = "Veuillez selectionner des produits et leur quantité pour passer la commande !";
			}
		}else{
			$data->status = false;
			$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
		}
	}else{
		$data->status = false;
		$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
	}
	echo json_encode($data);
}




if ($action == "validerChangement") {
	if (getSession("commande-encours") != null) {
		$datas = GROUPECOMMANDE::findBy(["id ="=>getSession("commande-encours")]);
		if (count($datas) > 0) {
			$grcom = $datas[0];
			$datas = $grcom->fourni("commande");
			$com = end($datas);
			$grcom->etat_id = ETAT::VALIDEE;
			$grcom->save();

			$groupecommande = new GROUPECOMMANDE();
			$groupecommande->hydrater($_POST);
			$groupecommande->enregistre();

			$produits = explode(",", $tableau);
			if (count($produits) > 0) {

				$commande = new COMMANDE();
				$commande->hydrater($_POST);
				$commande->zonelivraison_id = $com->zonelivraison_id;
				$commande->lieu = $com->lieu;
				$commande->datelivraison = $com->datelivraison;
				if ($commande->datelivraison < dateAjoute()) {
					$commande->datelivraison = dateAjoute();
				}
				$commande->groupecommande_id = $groupecommande->getId();
				$commande->setId(null);
				$data = $commande->enregistre();

				$changement = new TRANSFERTSTOCK();
				$changement->hydrater($_POST);
				$changement->groupecommande_id = $grcom->getId();
				$changement->groupecommande_id_new = $groupecommande->getId();
				$data = $changement->enregistre();

				if ($data->status) {
					foreach ($produits as $key => $value) {
						$lot = explode("-", $value);
						$id = $lot[0];
						$qte = end($lot);

						$datas = PRODUIT::findBy(["id="=>$id]);
						$reste = $grcom->reste($id);
						if (count($datas) > 0 && ($qte > 0 || $reste > 0)) {
							$produit = $datas[0];
							$lignecommande = new LIGNECOMMANDE;
							$lignecommande->commande_id = $commande->getId();
							$lignecommande->produit_id = $id;
							$lignecommande->quantite = $qte;
							$lignecommande->enregistre();

							$lignechangement = new LIGNETRANSFERTSTOCK;
							$lignechangement->transfertstock_id = $changement->getId();
							$lignechangement->produit_id = $id;
							$lignechangement->quantite_avant = $reste;
							$lignechangement->quantite_apres = $qte;
							$lignechangement->enregistre();
						}

					}				
				}	
			}else{
				$data->status = false;
				$data->message = "Veuillez selectionner des produits et leur quantité pour passer la commande !";
			}
		}else{
			$data->status = false;
			$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
		}
	}else{
		$data->status = false;
		$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
	}
	echo json_encode($data);
}




if ($action == "validerProgrammation") {
	if ($datelivraison >= dateAjoute()) {
		if (getSession("commande-encours") != null) {
			$datas = GROUPECOMMANDE::findBy(["id ="=>getSession("commande-encours")]);
			if (count($datas) > 0) {
				$groupecommande = $datas[0];

				$produits = explode(",", $tableau);
				if (count($produits) > 0) {
					$tests = $produits;
					foreach ($tests as $key => $value) {
						$lot = explode("-", $value);
						$id = $lot[0];
						$qte = end($lot);
						if ($groupecommande->reste($id) >= $qte) {
							unset($tests[$key]);
						}
					}
					if (count($tests) == 0) {
						$livraison = new VENTE();
						$livraison->hydrater($_POST);
						$livraison->groupecommande_id = $groupecommande->id;
						$livraison->etat_id = ETAT::PARTIEL;
						$data = $livraison->save();
						if ($data->status) {
							foreach ($produits as $key => $value) {
								$lot = explode("-", $value);
								$id = $lot[0];
								$qte = end($lot);

								$datas = PRODUIT::findBy(["id="=>$id]);
								if (count($datas) > 0) {
									$produit = $datas[0];

									$lignecommande = new LIGNEDEVENTE;
									$lignecommande->livraison_id = $livraison->id;
									$lignecommande->produit_id = $id;
									$lignecommande->quantite = $qte;
									$lignecommande->enregistre();
								}

							}

							$data->setUrl("fiches", "master", "bonlivraison", $data->lastid);				
						}	
					}else{
						$data->status = false;
						$data->message = "Veuillez à bien vérifier les quantités des différents produits à livrer, certaines sont incorrectes !";
					}
				}else{
					$data->status = false;
					$data->message = "Veuillez selectionner des produits et leur quantité pour passer la commande !";
				}
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
			}
		}else{
			$data->status = false;
			$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
		}
	}else{
		$data->status = false;
		$data->message = "Veuillez vérifier la date de programmation de la livraison !";
	}
	echo json_encode($data);
}



if ($action == "fichecommande") {
	$rooter = new ROOTER;
	$params = PARAMS::findLastId();
	$datas = GROUPECOMMANDE::findBy(["id ="=> $id]);
	if (count($datas) == 1) {
		session('commande-encours', $id);
		$groupecommande = $datas[0];
		$groupecommande->actualise();

		$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
		$employe = $datas[0];

		$datas = $groupecommande->toutesLesLignes();
		include("../../../../../composants/assets/modals/modal-groupecommande.php");
	}
}


if ($action == "modalcommande") {
	$rooter = new ROOTER;
	$params = PARAMS::findLastId();
	session("commande-encours", $id);
	include("../../../../../composants/assets/modals/modal-newcommande.php");
}



if ($action == "newlivraison") {
	$rooter = new ROOTER;
	$params = PARAMS::findLastId();
	$datas = GROUPECOMMANDE::findBy(["id ="=> $id]);
	if (count($datas) == 1) {
		session('commande-encours', $id);
		$groupecommande = $datas[0];
		$groupecommande->actualise();
		$groupecommande->fourni("commande", ["etat_id !="=>ETAT::ANNULEE]);
		include("../../../../../composants/assets/modals/modal-newlivraison.php");
	}
}


if ($action == "changement") {
	$rooter = new ROOTER;
	$params = PARAMS::findLastId();
	$datas = GROUPECOMMANDE::findBy(["id ="=> $id]);
	if (count($datas) == 1) {
		session('commande-encours', $id);
		$groupecommande = $datas[0];
		$groupecommande->actualise();
		$groupecommande->fourni("commande", ["etat_id !="=>ETAT::ANNULEE]);
		include("../../../../../composants/assets/modals/modal-changement.php");
	}
}




if ($action == "acompte") {
	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
	if (count($datas) > 0) {
		$employe = $datas[0];
		$employe->actualise();
		if ($employe->checkPassword($password)) {
			$datas = CLIENT::findBy(["id=" => $client_id]);
			if (count($datas) > 0) {
				$client = $datas[0];
				$data = $client->crediter(intval($montant), $_POST);
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'opération, veuillez recommencer !";
			}
		}else{
			$data->status = false;
			$data->message = "Votre mot de passe ne correspond pas !";
		}
	}else{
		$data->status = false;
		$data->message = "Vous ne pouvez pas effectué cette opération !";
	}
	echo json_encode($data);
}



if ($action == "reglerCommande") {
	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
	if (count($datas) > 0) {
		$employe = $datas[0];
		$employe->actualise();
		if ($employe->checkPassword($password)) {
			$datas = COMMANDE::findBy(["id=" => $commande_id]);
			if (count($datas) > 0) {
				$commande = $datas[0];
				$data = $client->recouvrir(intval($montant), $_POST);
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'opération, veuillez recommencer !";
			}
		}else{
			$data->status = false;
			$data->message = "Votre mot de passe ne correspond pas !";
		}
	}else{
		$data->status = false;
		$data->message = "Vous ne pouvez pas effectué cette opération !";
	}
	echo json_encode($data);
}


// if ($action == "dette") {
// 	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
// 	if (count($datas) > 0) {
// 		$employe = $datas[0];
// 		$employe->actualise();
// 		if ($employe->checkPassword($password)) {
// 			$datas = CLIENT::findBy(["id=" => $client_id]);
// 			if (count($datas) > 0) {
// 				$client = $datas[0];
// 				$data = $client->reglerDette(intval($montant), $_POST);
// 			}else{
// 				$data->status = false;
// 				$data->message = "Une erreur s'est produite lors de l'opération, veuillez recommencer !";
// 			}
// 		}else{
// 			$data->status = false;
// 			$data->message = "Votre mot de passe ne correspond pas !";
// 		}
// 	}else{
// 		$data->status = false;
// 		$data->message = "Vous ne pouvez pas effectué cette opération !";
// 	}
// 	echo json_encode($data);
// }


if ($action == "reglerToutesDettes") {
	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
	if (count($datas) > 0) {
		$employe = $datas[0];
		$employe->actualise();
		if ($employe->checkPassword($password)) {
			$datas = CLIENT::findBy(["id=" => $id]);
			if (count($datas) > 0) {
				$client = $datas[0];
				if ($client->acompte > 0) {
					foreach ($client->fourni("groupecommande", ["etat_id !="=>ETAT::ANNULEE]) as $key => $groupe) {
						foreach ($groupe->fourni("commande", ["etat_id !="=>ETAT::ANNULEE]) as $key => $commande) {
							$data = $commande->reglementDeCommande();
						}		
					}
				}else{
					$data->status = false;
					$data->message = "L'acompte du client est de 0F. Veuillez le crediter pour effectuer cette opération !!";
				}
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'opération, veuillez recommencer !";
			}
		}else{
			$data->status = false;
			$data->message = "Votre mot de passe ne correspond pas !";
		}
	}else{
		$data->status = false;
		$data->message = "Vous ne pouvez pas effectué cette opération !";
	}
	echo json_encode($data);
}


if ($action == "rembourser") {
	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
	if (count($datas) > 0) {
		$employe = $datas[0];
		$employe->actualise();
		if ($employe->checkPassword($password)) {
			$datas = CLIENT::findBy(["id=" => $client_id]);
			if (count($datas) > 0) {
				$client = $datas[0];
				$data = $client->rembourser(intval($montant), $_POST);
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'opération, veuillez recommencer !";
			}
		}else{
			$data->status = false;
			$data->message = "Votre mot de passe ne correspond pas !";
		}
	}else{
		$data->status = false;
		$data->message = "Vous ne pouvez pas effectué cette opération !";
	}
	echo json_encode($data);
}


if ($action == "annuler") {
	$datas = MISSION::findBy(["id ="=> $id]);
	if (count($datas) == 1) {
		$mission = $datas[0];
		$data = $mission->annuler();
	}else{
		$data->status = false;
		$data->message = "Une erreur s'est produite pendant le processus, veuillez recommencer !";
	}	
	echo json_encode($data);
}