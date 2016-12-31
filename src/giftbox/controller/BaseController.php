<?php

namespace giftbox\controller;

class BaseController {

	public $layout = 'layout';
	private $isRendered = false;
	public $vars = array();
    protected $name;

    public function __construct($action, $params = null) {
        $this->$action($params);
        $this->render($action);
    }

    public function render($view) {
		if ($this->isRendered) return false;
		ob_start();
		extract($this->vars);
		if(strpos($view, '/') === 0) {
			require(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR .  strtolower($view) . '.php');
		} else {
			require(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . strtolower($this->name) . DIRECTORY_SEPARATOR . $view . '.php');
		}
		$content = ob_get_clean();
        require(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $this->layout . '.php');
		$this->isRendered = true;
	}

	public function set($key, $value = null) {
		if(is_array($key)) {
			$this->vars += $key;
		} else {
			$this->vars[$key] = $value;
		}
	}

	public function redirect($url) {
        header('Location: ' . BASE_URL . $url);
    }
}
