<?php

namespace Utils;

class Paginator {

	private $_rowsCount;
	private $_repository;

	private $_limit;
	private $_page;

	private $_conditions = array();

	private $_totalPages;
	private $_offset;

	private $sort = false;
	private $sortColumn;
	private $sortOrder;

	public $data;
	public $links;

	private function createLinks() {
		$this->_totalPages = ceil($this->_repository->getLastQueryRowCount() / $this->_limit);

		$url = parse_url($_SERVER["REQUEST_URI"]);

		$url_query = isset($url["query"]) ? $url["query"] : "";

		if($url_query != "") {
			$url_query = preg_replace("/limit=(\d|all)|page=\d/i", "", $url_query);
			$url_query = trim($url_query, "&");
			$url_query = preg_replace("/&{1,}/i", "&", $url_query);
			$url_query .= "&";
		}
				
		$links = array();

		$links[] ='<ul class="pagination">';
		if($this->_page == 1 || ($this->_page > $this->_totalPages && $this->_totalPages == 1)) {
			$links[] = '<li class="disabled"><a href="#"><</a></li>';
		} else {
			$links[] = '<li><a href="?';
			$links[] = $url_query != "" ? $url_query : "";
			$links[] = 'limit=' . $this->_limit . '&page=' . ($this->_page - 1) . '"><</a></li>';
		}

		if($this->_totalPages > 1) {
			for($i = 1; $i <= $this->_totalPages; $i++) {
				$links[] = '<li';
				if($i == $this->_page) {
					$links[] = ' class="active"';
				}
				$links[] = '><a href="?';
				$links[] = $url_query != "" ? $url_query : "";
				$links[] = 'limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></i>';
			}
		} else {
			$links[] = '<li class="active"><a href="#" class="active">1</a></li>';
		}

		if($this->_page >= $this->_totalPages) {
			$links[] = '<li class="disabled"><a href="#">></a></li>';
		} else {
			$links[] = '<li><a href="?';
			$links[] = $url_query != "" ? $url_query : "";
			$links[] = 'limit=' . $this->_limit . '&page=' . ($this->_page + 1) . '">></a></li>';
		}

		$this->links = implode("", $links);
	}

	public function __construct( \Abstracts\ARepository $repository ) {
		$this->_repository = &$repository;
		$this->_rowsCount = $this->_repository->getRowsCount();
	}

	public function setConditions($conditions) {
		$this->_conditions = $conditions;

		$this->_limit = $this->_conditions["limit"] == "all" ? PHP_INT_MAX : $this->_conditions["limit"];
		$this->_page = isset($this->_conditions["page"]) ? $this->_conditions["page"] : 1;

		$this->_offset = $this->_page == 1 ? 0 : ($this->_page - 1) * $this->_limit;

		$this->_repository->setRowsOffset($this->_offset);
		$this->_repository->setRowsLimit($this->_limit);
	}

	public function accessRepository($method, array $args = null) {
		if($args != null) {
			$this->data = call_user_func_array([$this->_repository, $method], $args);
		} else {
			$this->data = call_user_func([$this->_repository, $method]);
		}
		
		foreach($this->data as $key => $value) {
			if(empty($value)) {
				unset($this->data[ $key ]);
			}
		}
		
		$this->createLinks();
		return $this;	
	}
	
}