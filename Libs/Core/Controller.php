<?php

namespace Core;

class Controller {

	protected $autoRender = true;
	protected $View;
	protected $Model = null;

	private $className = null;

	public function __construct() {
		$this->className = str_replace("Controller", "", substr(strrchr(get_class($this), '\\'), 1));

		$this->View = new View();
		$this->loadModel();
	}

	public function render($view) {
		if($this->autoRender == false) {
			return;
		}

		$this->View->render($this->className . "/" . strtolower($view));
	}

	protected function set(array $args) {
		$this->View->vars[] = $args;
	}

	protected function setLayout($layout) {
		$this->View->setLayout($layout);
	}

	protected function loadModel() {
		if(file_exists(MODELS_PATH . ucfirst($this->className) . "Model.php")) {
			require MODELS_PATH . ucfirst($this->className) . "Model.php";
			$targetModel = 'App\Models\\' . $this->className . "Model";
		} else {
			$targetModel = 'Core\Model';
		}
		
		$this->Model = new $targetModel();
	}

}