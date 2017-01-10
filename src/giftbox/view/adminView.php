<?php
namespace giftbox\view;


class adminView
{
	private $contenu;
	public function __construct($data)
	{
		$this->contenu = $data;
	}
	public function render(){
		ob_start();
		$content = $this->contenu;
		include 'adminCode.php';
		ob_end_flush();
	}
}