<?php

namespace Core;

class View {
	protected $blocks = array();

	private $layout = '_templates/index.php';

	public $vars = array();

	public function render($filename) {		
		foreach($this->vars as $arr) {
			foreach($arr as $k => $v) {
				${$k} = $v;
			}
		}

		if($this->layout === "_blank") {
			require VIEWS_PATH . $filename . ".php";
			return;
		}

		$this->start("content");
		require VIEWS_PATH . $filename . ".php";
		$this->end("content");

		require VIEWS_PATH . $this->layout;
		
	}

	public function setLayout($layout) {
		if($layout === "_blank") {
			$this->layout = "_blank";
			return;
		}
		$this->layout = "_templates/" . $layout . ".php";
	}

	public function start($block_name) {
		$this->blocks[$block_name] = "";
		
		ob_start();
	}
	
	public function end($block_name) {
		$this->blocks[$block_name] = ob_get_clean();
	}

	public function fetch($block_name) {
		if(isset($this->blocks[$block_name])) {
			$block_contents = $this->blocks[$block_name];
			unset($this->blocks[$block_name]);
		} else {
			$block_contents = "";
		}
		
		return $block_contents;
	}
	
}
