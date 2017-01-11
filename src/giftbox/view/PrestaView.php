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
		$contenu = '<a href="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '">croissant</a>&nbsp;&nbsp;';
		$contenu .= '<a href="' . $this->app->urlFor('prestations', ['order' => 'desc']) . '">decroissant</a>';
		foreach ($this->data as $d){
			$notes = Note::where('prestationId', '=', $d->id)->get(array('note'));
			$notesTotal = 0;
			$moyenne = 0;
			if ($d->votes > 0) {
				foreach ($notes as $note => $n) {
					$notesTotal = ($notesTotal + $n->note);
				}
				$moyenne = round(($notesTotal / $d->votes), 2) . '/5';
			} else {
				$moyenne = 'Pas de note(s)';
			}
			$categorie = $d->categorie()->first()->nom;
			$contenu .= '<h2><u>Prestation</u> : ' . $d->nom . '</h2>';
			$contenu .= '<p><img class="prestaImg" src="' . $uri . '/web/img/' . $d->img . '"></p>';
			$contenu .= '<h3><u>Categorie</u> : <a href="' . $this->app->urlFor('categories.order', ['categorie'=>$d['cat_id'],'order' => 'asc']) . '">' . $categorie . '</a></h3>';
			$contenu .= '<p>' . $d->descr . '</p>';
			$contenu .= '<p><u>Prix</u> : ' . $d->prix . '</p>';
			$contenu .= '<p><u>Note</u> : ' . $moyenne . '</p>';
			$contenu .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $d->id]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
			$contenu .= '<p><u>Noter :</u></p>';
			$contenu .= '<div class="notation">';
			$contenu .= '<div class="stars">';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 5]) . '" title="5 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 4]) . '" title="4 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 3]) . '" title="3 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 2]) . '" title="2 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $d->id, 'note' => 1]) . '" title="1 &eacute;toile">&star;</a>';
			$contenu .= '</div>';
			$contenu .= '</div>';
			$contenu .= '<p><a href="' . $this->app->urlFor('prestation', ['id' => $d->id]) . '">Voir plus &rarr;</a></p>';
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
			$moyenne = round(($notesTotal / $this->data[0]->votes), 2) . '/5';
		} else {
			$moyenne = 'Pas de note(s)';
		}
		$contenu = '<h2><u>Prestation</u> : ' . $this->data[0]->nom . '</h2>';
		$contenu .= '<p>' . $this->data[0]->descr . '</p>';
		$contenu .= '<p><u>Prix</u> : ' . $this->data[0]->prix . '</p>';
		$contenu .= '<p><u>Note</u> : ' . $moyenne . '</p>';
		$contenu .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $this->data[0]->id]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
		$contenu .= '<p><u>Noter :</u></p>';
		$contenu .= '<div class="notation">';
		$contenu .= '<div class="stars">';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 5]) . '" title="5 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 4]) . '" title="4 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 3]) . '" title="3 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 2]) . '" title="2 &eacute;toiles">&star;</a>';
		$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $this->data[0]->id, 'note' => 1]) . '" title="1 &eacute;toile">&star;</a>';
		$contenu .= '</div>';
		$contenu .= '</div>';
		$contenu .= '<p><a href="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '">Liste des prestations</a></p>';
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
        $this->app->response->redirect($this->app->urlFor('index'), 200);
	}

	private function index() {
		$uri = $this->app->request->getRootUri();
		$contenu = '<h1>Accueil</h1>';
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
			$contenu .= '<hr />';
			$contenu .= '<h2><u>Prestation</u> : ' . $p['prestation']->nom . '</h2>';
			$contenu .= '<p>' . $p['prestation']->descr . '</p>';
			$contenu .= '<p><u>Prix</u> : ' . $p['prestation']->prix . '</p>';
			$contenu .= '<p><u>Note</u> : ' . $p['moyenne'] . '/5</p>';
			$contenu .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $p['prestation']->id]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
			$contenu .= '<p><u>Noter :</u></p>';
			$contenu .= '<div class="notation">';
			$contenu .= '<div class="stars">';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 5]) . '" title="5 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 4]) . '" title="4 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 3]) . '" title="3 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 2]) . '" title="2 &eacute;toiles">&star;</a>';
			$contenu .= '<a href="' . $this->app->urlFor('notation', ['id' => $p['prestation']->id, 'note' => 1]) . '" title="1 &eacute;toile">&star;</a>';
			$contenu .= '</div>';
			$contenu .= '</div>';
			$contenu .= '<p><a href="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '">Liste des prestations</a></p>';
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