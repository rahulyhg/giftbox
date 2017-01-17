<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 06/01/2017
 * Time: 15:35
 */

namespace giftbox\view;

use giftbox\models\Cagnotte;
use giftbox\models\Coffret;

class CagnotteView
{
	private $data;
	private $app;

	public function __construct($app = null, $array = null)
	{
		$this->data = $array;
		$this->app = $app;
	}

	private function participerForm() {
		$formulaire = '';
		$cagnotte = Cagnotte::where('urlContribution', '=', $this->data);
		if (!is_null($cagnotte)) {
			$formulaire .= '<form action="' . $this->app->urlFor('cagnotte.participation', ['url' => $this->data]) . '" method="post">';
			$formulaire .= '<label for="montant">Montant :</label>';
			$formulaire .= '<input type="text" name="montant" id="montant" placeholder="0.00€">';
			$formulaire .= '<button name="participer" value="Participer">Participer</button>';
			$formulaire .= '</form>';
		} else {
			$this->app->flash('danger', 'Impossible de trouver la cagnotte !');
			$this->app->response->redirect($this->app->urlFor('index'), 200);
		}
		return $formulaire;
	}

	private function participer() {
		$post = $this->app->request->post();
		if (!empty($post)) {
			if (!empty($post['montant'])) {
				$cagnotte = Cagnotte::where('urlContribution', '=', $this->data)->first();
				if (!is_null($cagnotte)) {
					if ($cagnotte->cloture == 0) {
						$montant = filter_var($post['montant'], FILTER_SANITIZE_NUMBER_FLOAT);
						$total = ($cagnotte->montant + $montant);
						$cagnotte->update(array('montant' => $total));
						$this->app->flash('success', 'Merci d\'avoir participé à la cagnotte !');
						$this->app->response->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]), 200);
					}
				}
			} else {
				$this->app->flash('danger', 'Veuillez préciser un montant d\'au moins 1€ !');
				$this->app->response->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]), 200);
			}
		}
		return null;
	}

	private function cloturerCagnotte(){
		if (isset($_SESSION['cagnotte_edit'])) {
			$cagnotte = Cagnotte::where('urlGestion', '=', $this->data)->first();
			if (!is_null($cagnotte)) {
				$coffret = Coffret::where('id', '=', $cagnotte->coffret_id)->first();
				if ($cagnotte->montant >= $coffret->montant) {
					if ($cagnotte->cloture == 0) {
						$cagnotte->update(array('cloture' => 1));
						$this->app->flash('success', 'Cagnotte cloturée avec succès !');
						$this->app->response->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]), 200);
						$coffret->update(array('url' => uniqid()));
					} else {
						$this->app->flash('danger', 'La cagnotte est déjà cloturée !');
						$this->app->response->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]), 200);
					}
				} else {
					$this->app->flash('info', 'Le montant de la cagnotte (' . $cagnotte->montant . '&euro;) est inférieur au montant du coffret (' . $coffret->montant . '&euro;) !');
						$this->app->response->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]), 200);
				}
			} else {
				$this->app->flash('danger', 'Impossible de trouver la cagnotte !');
				$this->app->response->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]), 200);
			}
		} else {
			$this->app->response->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]), 200);
		}
		return null;
	}

	private function connexionForm() {
		$formulaire = '<form action="' . $this->app->urlFor('cagnotte.connexion', ['url' => $this->data]) . '" method="post">';
		$formulaire .= '<label for="password">Mot de passe du coffret :</label>';
		$formulaire .= '<input type="password" name="password" id="password" required>';
		$formulaire .= '<button name="Se connecter" value="Se Connecter">Se connecter</button>';
		$formulaire .= '</form>';
		return $formulaire;
	}

	private function connexion(){
		if (!isset($_SESSION['cagnotte_edit'])) {
			$post = $this->app->request->post();
			if(!empty($post['password'])){
				$password = filter_var($post['password'], FILTER_SANITIZE_STRING);
				$cagnotte = Cagnotte::where('urlGestion', '=', $this->data)->first();
				$coffret = Coffret::where('id', '=', $cagnotte->coffret_id)->first();
				if(password_verify($password, $coffret->password)){
					$_SESSION['cagnotte_edit'] = "allowed";
					$this->app->response->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]), 200);
				}
			} else {
				$this->app->flash('danger', 'Impossible de vous connecter');
				$this->app->response->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]), 200);
			}
		} else {
			$this->app->response->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]), 200);
		}
		return null;
	}

	private function gestion() {
		$content = '';
		if (!isset($_SESSION['cagnotte_edit'])) {
			$this->app->response->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]), 200);
		} else {
			$cagnotte = Cagnotte::where('urlGestion', '=', $this->data)->first();
			$coffret = Coffret::where('id', '=', $cagnotte->coffret_id)->first();
			$content .= '<div class="container">';
			$content .= '<div class="row">';
			$content .= '<div class="<div class="col-md-12">';
			$content .= '<div class="<div class="col-md-12">';
			$content .= '<div class="progress">';
			$percent = round((($cagnotte->montant * 100) / $coffret->montant), 0);
			$content .= '<div class="progress-bar" role="progressbar" aria-valuenow="' . $percent . '" aria-valuemin="0" aria-valuemax="' . $coffret->montant . '" style="width: ' . $percent . '%;">';
			$content .= $percent . '%';
			$content .= '</div>';
			$content .= '</div>';
			$content .= '<p>' . $cagnotte->montant . '&euro; / ' . $coffret->montant . '&euro; (' . $percent . '%)</p>';
			if ($cagnotte->cloture == 0) {
				if ($cagnotte->montant >= $coffret->montant) {
					$content .= '<p><a onclick="return confirm(\'Voulez vous cloturer la cagnotte ?\')" href="' . $this->app->urlFor('cagnotte.cloturer', ['url' => $this->data]) . '" class="btn btn-primary">Cloturer la cagnotte</a></p>';
				} else {
					$content .= '<p><button class="btn btn-default" disabled>Cloturer la cagnotte</button></p>';
				}
			} else {
				$content .= '<p class="alert alert-info">Cette cagnotte est cloturée</p>';
			}
			$content .= '</div>';
			$content .= '</div>';
			$content .= '</div>';
		}
		return $content;
	}

	public function render($aff){
		switch ($aff){
			case 'gestion':
				$content = $this->gestion();
				break;

			case 'participerForm':
				$content = $this->participerForm();
				break;

			case 'participer':
				$content = $this->participer();
				break;

			case 'cloturer':
				$content = $this->cloturerCagnotte();
				break;

			case 'connexionForm':
				$content = $this->connexionForm();
				break;

			case 'connexion':
				$content = $this->connexion();
				break;
		}
		return $content;
	}
}