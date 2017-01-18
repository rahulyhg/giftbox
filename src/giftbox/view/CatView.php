<?php

namespace giftbox\view;


use giftbox\models\Prestation;

class CatView
{
	private $data, $order;
	private $app;

	public function __construct($app = null, $array, $ord = null)
	{
		$this->app = $app;
		$this->data = $array;
		$this->order = $ord;
	}

	private function listeCategories(){
		$contenu = '<div class="page-header">';
		$contenu .= '<h1>Cat&eacute;gories</h1>';
		$contenu .= '</div>';
		$contenu .= '<div class="btn-group btn-group-justified" role="group">';
		foreach ($this->data as $d){
			$contenu .= '<a type="button" class="btn btn-default" href="' . $this->app->urlFor('categories.order', ['categorie' => $d->id, 'order' => 'asc']) . '">';
			$contenu .= '<h4>' . $d->nom . '</h4>';
			$contenu .= '</a>';
		}
		$contenu .= '</div>';
		return $contenu;
	}
	private function categoriePrest(){
		$categorie = $this->data[0];
		if ($this->order == "desc") {
			$prestations = new PrestaView($this->app, $categorie->prestations()->get()->sortByDesc('prix'));
		} else {
			$prestations = new PrestaView($this->app, $categorie->prestations()->get()->sortBy('prix'));
		}
		$contenu = $prestations->render(1);
		return $contenu;
	}

	public function render($aff){
		switch ($aff){
			case 2:
				$content = $this->categoriePrest();
				break;
				
			case 1:
			default:
				$content = $this->listeCategories();
				break;
		}

		return $content;
	}
}