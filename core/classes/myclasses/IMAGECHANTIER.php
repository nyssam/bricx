<?php
namespace Home;
use Native\RESPONSE;
use Native\FICHIER;

/**
 * 
 */
class IMAGECHANTIER extends TABLE
{
	
	public static $tableName = __CLASS__;
	public static $namespace = __NAMESPACE__;

	public $chantier_id;
	public $name;
	public $image;


	public function enregistre(){
		$data = new RESPONSE;
		if ($this->name != "") {
			$this->chantier_id = getSession("chantier_connecte_id");
			$data = $this->save();
			if ($data->status) {
				$this->uploading($this->files);
			}
		}else{
			$data->status = false;
			$data->message = "Veuillez renseigner le nom du produit !";
		}
		return $data;
	}


	public function uploading(Array $files){
		//les proprites d'images;
		$tab = ["image"];
		if (is_array($files) && count($files) > 0) {
			$i = 0;
			foreach ($files as $key => $file) {
				if ($file["tmp_name"] != "") {
					$image = new FICHIER();
					$image->hydrater($file);
					if ($image->is_image()) {
						$a = substr(uniqid(), 5);
						$result = $image->upload("images", "imageschantiers", $a);
						$name = $tab[$i];
						$this->$name = $result->filename;
						$this->save();
					}
				}	
				$i++;			
			}			
		}
	}

	public function sentenseCreate(){
		$this->sentense = "enregistrement d'un nouveau produit ".$this->name();
	}
	public function sentenseUpdate(){
		$this->sentense = "Modification des informations du produit ".$this->name();
	}
	public function sentenseDelete(){
		$this->sentense = "Suppression du produit ".$this->name();
	}

}

?>