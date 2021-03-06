<?php 
namespace Home;
use Faker\Factory;
$faker = Factory::create();


$produits = PRODUIT::isActives();
foreach ($produits as $key => $produit) {
	$produit->actualise();
	$produit->veille = $produit->stock(PARAMS::DATE_DEFAULT, dateAjoute1($date1, -1));
	$produit->production = $produit->production($date1, $date2);
	$produit->livraison = $produit->livraison($date1, $date2);
	$produit->achat = $produit->achat($date1, $date2);
	$produit->stock = $produit->stock(PARAMS::DATE_DEFAULT, $date2);
	$produit->perteLivraison = $produit->perteLivraison($date1, $date2);
	$produit->perteRangement = $produit->perteRangement($date1, $date2);
	$produit->perteAutre = $produit->perteAutre($date1, $date2);
	$produit->perte = $produit->perteLivraison + $produit->perteRangement + $produit->perteAutre;

	foreach (RESSOURCE::getAll() as $key => $ressource) {
		$name = trim($ressource->name());
		$produit->$name = $produit->exigence(($produit->production), $ressource->getId());
		$a = "perte-$name";
		$produit->$a = $produit->exigence(intval($produit->perte), $ressource->getId());
	}
}

$perte = comptage($produits, "perte", "somme");
if ($perte > 0) {
	$pertelivraison = round(((LIVRAISON::perte($date1, $date2) / $perte) * 100),2);
}else{
	$pertelivraison = 0;
}

$productions = PRODUCTION::findBy(["ladate >="=>$date1, "ladate <= "=>$date2]);

$tricycles = LIVRAISON::findBy(["DATE(datelivraison) >="=>$date1, "DATE(datelivraison) <= "=>$date2, "etat_id ="=>ETAT::VALIDEE, "vehicule_id ="=>VEHICULE::TRICYCLE]);


$ressources = RESSOURCE::getAll();
usort($produits, "comparerPerte");


$parfums = $typeproduits = $quantites = [];


$stats = PRODUCTION::stats($date1, $date2);

$title = "BRICX | Rapport de production ";

$lots = [];
?>