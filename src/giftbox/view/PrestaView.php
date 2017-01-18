<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 28/12/16
 * Time: 15:50
 */

namespace giftbox\view;

use giftbox\models\Categorie;
use giftbox\models\Note;
use giftbox\models\Prestation;

class PrestaView
{
	
	private $data;
	private $app;

	public function __construct($app = null, $array = null)
	{
		$this->app = $app;
		$this->data = $array;

	}

	private function listePrestations(){
		$uri = $this->app->request->getRootUri();
		$order = $this->data[1];
		$contenu = '<div class="page-header">';
		$contenu .= '<h1>Prestations</h1>';
		$contenu .= '</div>';
		$contenu .= '<div class="form-group">';
		$contenu .= '<label for="trix">Trix :</label>';
		$contenu .= '<select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" value="">';
		
		if (isset($this->data[2])) {
			$contenu .= '<option value="' . $this->app->urlFor('categories.order', ['categorie' => $this->data[1], 'order' => 'asc']) . '" ' . (($this->data[2] == 'asc') ? 'selected' : '') . '>Prix croissant</option>';
			$contenu .= '<option value="' . $this->app->urlFor('categories.order', ['categorie' => $this->data[1], 'order' => 'desc']) . '" ' . (($this->data[2] == 'desc') ? 'selected' : '') . '>Prix décroissant</option>';
		} else {
			$contenu .= '<option value="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '" ' . (($order == 'asc') ? 'selected' : '') . '>Prix croissant</option>';
			$contenu .= '<option value="' . $this->app->urlFor('prestations', ['order' => 'desc']) . '" ' . (($order == 'desc') ? 'selected' : '') . '>Prix décroissant</option>';
		}

		$contenu .= '</select>';
		$contenu .= '</div>';
		foreach ($this->data[0] as $d){
			$notes = Note::where('prestationId', '=', $d->id)->get(array('note'));
			$notesTotal = 0;
			$moyenne = 0;
			if ($d->votes > 0) {
				foreach ($notes as $note => $n) {
					$notesTotal = ($notesTotal + $n->note);
				}
				$moyenne = round(($notesTotal / $d->votes), 2) . ' / 5';
			} else {
				$moyenne = 'Pas de note(s)';
			}
			$categorie = $d->categorie()->first()->nom;
			$contenu .= '<div class="col-sm-6 col-md-4">';
			$contenu .= '<div class="thumbnail">';
			$contenu .= '<img src="' . $uri . '/web/img/' . $d->img . '" alt="' . $d->nom . '">';
			$contenu .= '<div class="caption">';
			$contenu .= '<h4>';
			$contenu .= '<a href="' . $this->app->urlFor('prestation', ['id' => $d->id]) . '">' . $d->nom . '</a>';
			$contenu .= '&nbsp;<span class="label label-primary">' . $moyenne . '</span>';
			$contenu .= '</h4>';
			$contenu .= '<h5><a href="' . $this->app->urlFor('categories.order', ['categorie' => $d->cat_id, 'order' => 'asc']) . '"><span class="label label-default">' . $categorie . '</span></a></h5>';
			$contenu .= '<h5>' . $d->descr . '</h5>';
			$contenu .= '<p><a href="' . $this->app->urlFor('ajouter', ['id' => $d->id]) . '" title="Ajouter au panier" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ' . $d->prix . ' &euro;</a></p>';
			$contenu .= '<p><div class="notation">';
			$contenu .= '<div class="stars">';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 5]) . '" title="5 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 4]) . '" title="4 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 3]) . '" title="3 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 2]) . '" title="2 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 1]) . '" title="1 &eacute;toile">&star;</a>';
			$contenu .= '</div>';
			$contenu .= '</div></p>';
			$contenu .= '</div>';
			$contenu .= '</div>';
			$contenu .= '</div>';
		}

		return $contenu;
	}

	private function prestation(){
		$uri = $this->app->request->getRootUri();
		$notes = Note::where('prestationId', '=', $this->data[0]->id)->get(array('note'));
		$moyenne = 0;
		$notesTotal = 0;
		if ($this->data[0]->votes > 0) {
			foreach ($notes as $note => $n) {
				$notesTotal = ($notesTotal + $n->note);
			}
			$moyenne = round(($notesTotal / $this->data[0]->votes), 2) . ' / 5';
		} else {
			$moyenne = 'Pas de note(s)';
		}

		$categorie = Categorie::where('id', '=', $this->data[0]->cat_id)->first();
		$contenu = '<div class="page-header">';
		$contenu .= '<h1>' . $this->data[0]->nom . ' <a href="' . $this->app->urlFor('categories.order', ['categorie' => $this->data[0]->cat_id, 'order' => 'asc']) . '"><span class="label label-default">' . $categorie->nom . '</span></a></h1>';
		$contenu .= '</div>';
		$contenu .= '<p><a href="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '" class="btn btn-primary">&larr; Liste des prestations</a></p>';
		$contenu .= '<div class="thumbnail">';
		$contenu .= '<img src="' . $uri . '/web/img/' . $this->data[0]->img . '" alt="' . $this->data[0]->nom . '">';
		$contenu .= '</div>';
		$contenu .= '<p>' . $this->data[0]->descr . '</p>';
		$contenu .= '<p><u>Note</u> : <span class="label label-warning">' . $moyenne . '</span></p>';
		$contenu .= '<p><a href="' . $this->app->urlFor('ajouter', ['id' => $this->data[0]->id]) . '" title="Ajouter au panier" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ' . $this->data[0]->prix . ' &euro;</a></p>';
		$contenu .= '<p><div class="notation">';
		$contenu .= '<div class="stars">';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 5]) . '" title="5 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 4]) . '" title="4 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 3]) . '" title="3 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 2]) . '" title="2 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 1]) . '" title="1 &eacute;toile">&star;</a>';
		$contenu .= '</div>';
		$contenu .= '</div></p>';
		return $contenu;
	}

	private function note() {
		$pid = $this->data[0];
		$note = $this->data[1];

		Note::create(array('prestationId' => $pid, 'note' => $note));
		$prestation = Prestation::where('id', '=', $pid)->first();
		$prestation->votes = ($prestation->votes + 1);
		$prestation->save();

		$this->app->flash('success', 'Merci d\'avoir not&eacute; cette prestation.');
		$this->app->redirect($this->app->urlFor('index'));
		return null;
	}

	private function index() {
		$uri = $this->app->request->getRootUri();
		$contenu = '<div class="page-header">';
		$contenu .= '<h1>Accueil</h1>';
		$contenu .= '</div>';
		$categories = Categorie::all();
		$prestations = array();
		foreach ($categories as $categorie => $c) {
			$prestation = Prestation::where('cat_id', '=', $c->id)->get()->sortByDesc('votes')->first();
			$notes = Note::where('prestationId', '=', $prestation->id)->get(array('note'));
			$notesTotal = 0;
			foreach ($notes as $note => $n) {
				$notesTotal = ($notesTotal + $n->note);
			}
			$moyenne = round(($notesTotal / $prestation->votes), 2);
			$prestations[] = array('prestation' => $prestation, 'moyenne' => $moyenne);
			$notesTotal = 0;
		}
		usort($prestations, function($a, $b) { return ($a['moyenne'] < $b['moyenne']); });
		foreach ($prestations as $prestation => $p) {
			$uri = $this->app->request->getRootUri();
			$categorie = $p['prestation']->categorie()->first()->nom;

			$contenu .= '<div class="col-sm-6 col-md-4">';
			$contenu .= '<div class="thumbnail">';
			$contenu .= '<img src="' . $uri . '/web/img/' . $p['prestation']->img . '" alt="' . $p['prestation']->nom . '">';
			$contenu .= '<div class="caption">';
			$contenu .= '<h4>';
			$contenu .= '<a href="' . $this->app->urlFor('prestation', ['id' => $p['prestation']->id]) . '">' . $p['prestation']->nom . '</a>';
			$contenu .= '&nbsp;<span class="label label-primary">' . $p['moyenne'] . ' / 5</span>';
			$contenu .= '</h4>';
			$contenu .= '<h5><a href="' . $this->app->urlFor('categories.order', ['categorie' => $p['prestation']->cat_id, 'order' => 'asc']) . '"><span class="label label-default">' . $categorie . '</span></a></h5>';
			$contenu .= '<h5>' . $p['prestation']->descr . '</h5>';
			$contenu .= '<p><a href="' . $this->app->urlFor('ajouter', ['id' => $p['prestation']->id]) . '" title="Ajouter au panier" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ' . $p['prestation']->prix . ' &euro;</a></p>';
			$contenu .= '<p><div class="notation">';
			$contenu .= '<div class="stars">';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 5]) . '" title="5 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 4]) . '" title="4 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 3]) . '" title="3 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 2]) . '" title="2 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 1]) . '" title="1 &eacute;toile">&star;</a>';
			$contenu .= '</div>';
			$contenu .= '</div></p>';
			$contenu .= '</div>';
			$contenu .= '</div>';
			$contenu .= '</div>';
		}
		return $contenu;
	}

	public function render($aff){

		switch ($aff){
			case 1:
				$content = $this->listePrestations();
				break;
			case 2:
				$content = $this->prestation();
				break;
			case 'note':
				$content = $this->note();
				break;
			case 'index':
				$content = $this->index();
				break;
			default:
				$content = "contenu inexistant";
				break;
		}

		return $content;
	}

}