<?php
namespace Home;
use Native\RESPONSE;

/**
 * 
 */
class LIGNEUSEPRODUIT extends TABLE
{
	
	
	public static $tableName = __CLASS__;
	public static $namespace = __NAMESPACE__;


	public $useproduit_id;
	public $produit_id;
	public $quantite;
	public $perte = 0;


	public function enregistre(){
		$data = new RESPONSE;
		$datas = USEPRODUIT::findBy(["id ="=>$this->useproduit_id]);
		if (count($datas) == 1) {
			$datas = PRODUIT::findBy(["id ="=>$this->produit_id]);
			if (count($datas) == 1) {
				if ($this->quantite >= 0) {
					$data = $this->save();
				}				
			}else{
				$data->status = false;
				$data->message = "Une erreur s'est produite lors de la mise en boutique du produit !";
			}			
		}else{
			$data->status = false;
			$data->message = "Une erreur s'est produite lors de la mise en boutique du produit !";
		}
		return $data;
	}




	public function sentenseCreate(){}
	public function sentenseUpdate(){}
	public function sentenseDelete(){}
}

?>