<?php
namespace Home;
use Native\RESPONSE;
use Native\FICHIER;
/**
 * 
 */
class VEHICULE extends TABLE
{
	public static $tableName = __CLASS__;
	public static $namespace = __NAMESPACE__;

	const AUTO        = 1;
	const TRICYCLE = 2;

	public $immatriculation;
	public $disponibilite_id = DISPONIBILITE::LIBRE;
	public $modele;
	public $image = "default.jpg";


	public function enregistre(){
		$data = new RESPONSE;
		if ($this->immatriculation != "" && $this->modele != "") {
			$data = $this->save();
			if ($data->status) {
				$this->uploading($this->files);
			}
		}else{
			$data->status = false;
			$data->message = "Veuillez renseigner tous les champs !";
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
						$result = $image->upload("images", "vehicules", $a);
						$name = $tab[$i];
						$this->$name = $result->filename;
						$this->save();
					}
				}	
				$i++;			
			}			
		}
	}



	public function name(){
		return $this->modele." // ".$this->immatriculation;
	}



////////////////////////////////////////////////////////////////////////////



	public static function ras(){
		return static::findBy(["etatvehicule_id ="=> ETATVEHICULE::RAS]);
	}

	public static function mission(){
		return static::findBy(["etatvehicule_id ="=> ETATVEHICULE::MISSION, 'visibility ='=>1]);
	}

	public static function entretien(){
		return array_merge(static::findBy(["etatvehicule_id ="=> ETATVEHICULE::ENTRETIEN]), static::findBy(["etatvehicule_id ="=> ETATVEHICULE::PANNE]));
	}



////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function sentenseCreate(){
		return $this->sentense = "Enregistrement d'un nouveau véhicule N°$this->id immatriculé $this->immatriculation.";
	}


	public function sentenseUpdate(){
		return $this->sentense = "Modification des infos du véhicule N°$this->id immatriculé $this->immatriculation.";
	}


	public function sentenseDelete(){
		return $this->sentense = "Suppression définitive du véhicule N°$this->id immatriculé $this->immatriculation dans la base de données.";
	}

}
?>