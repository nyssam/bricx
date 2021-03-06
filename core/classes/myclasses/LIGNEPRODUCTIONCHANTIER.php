<?php
namespace Home;
use Native\RESPONSE;
use Native\EMAIL;
/**
 * 
 */
class LIGNEPRODUCTIONCHANTIER extends TABLE
{
	public static $tableName = __CLASS__;
	public static $namespace = __NAMESPACE__;

	public $productionchantier_id;
	public $produit_id;
	public $quantite = 0;
	public $perte = 0;



	public function enregistre(){
		$data = new RESPONSE;
		$datas = PRODUCTIONCHANTIER::findBy(["id ="=>$this->productionchantier_id]);
		if (count($datas) == 1) {
			$datas = PRODUIT::findBy(["id ="=>$this->produit_id]);
			if (count($datas) == 1) {
				if ($this->quantite >= 0) {
					$data = $this->save();
				}else{
					$data->status = false;
					$data->message = "La quantité entrée n'est pas correcte !";
				}
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
			}
		}else{
			$data->status = false;
			$data->message = "Une erreur s'est produite lors de l'operation, veuillez recommencer !";
		}
		return $data;
	}




	public function sentenseCreate(){
		
	}


	public function sentenseUpdate(){
	}


	public function sentenseDelete(){
	}

}



?>