<?php 
namespace Home;
require '../../../../../core/root/includes.php';

use Native\RESPONSE;

$data = new RESPONSE;
extract($_POST);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if ($action == "depotproduit") {
	$tests = $listeproduits = explode(",", $listeproduits);
	foreach ($tests as $key => $value) {
		$lot = explode("-", $value);
		$id = $lot[0];
		$qte = end($lot);
		$datas = PRODUIT::findBy(["id ="=> $id]);
		if (count($datas) == 1) {
			$produit = $datas[0];
			if ($produit->enAgence(PARAMS::DATE_DEFAULT, dateAjoute(1), getSession("agence_connecte_id")) >= $qte) {
				unset($tests[$key]);
			}	
		}
	}
	if (count($tests) == 0) {
		$depot = new DEPOTPRODUIT();
		$depot->hydrater($_POST);
		$depot->etat_id = ETAT::ENCOURS;
		$data = $depot->enregistre();
		if ($data->status) {
			foreach ($listeproduits as $key => $value) {
				$lot = explode("-", $value);
				$id = $lot[0];
				$qte = end($lot);
				$datas = PRODUIT::findBy(["id ="=> $id]);
				if (count($datas) == 1) {
					$produit = $datas[0];
					if ($qte > 0) {
						$ligne = new LIGNEDEPOTPRODUIT();
						$ligne->depotproduit_id = $depot->id;
						$ligne->produit_id = $produit->id;
						$ligne->quantite_depart = intval($qte);
						$data = $ligne->enregistre();
					}

				}
			}
		}
	}else{
		$data->status = false;
		$data->message = "Certains des produits sont en quantité insuffisantes pour faire cet envoi !";
	}
	echo json_encode($data);
}




if ($action == "annulerDepotProduit") {
	$datas = EMPLOYE::findBy(["id = "=>getSession("employe_connecte_id")]);
	if (count($datas) > 0) {
		$employe = $datas[0];
		$employe->actualise();
		if ($employe->checkPassword($password)) {
			$datas = DEPOTPRODUIT::findBy(["id ="=>$id]);
			if (count($datas) == 1) {
				$prospection = $datas[0];
				$data = $prospection->annuler();
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

