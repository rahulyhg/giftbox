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
		$contenu = '<div class="page-header">';
		$contenu .= '<h1>Participer</h1>';
		$contenu .= '</div>';
		$cagnotte = Cagnotte::where('urlContribution', '=', $this->data);
		if (!is_null($cagnotte)) {
			$contenu .= '<form action="' . $this->app->urlFor('cagnotte.participation', ['url' => $this->data]) . '" method="post">';
			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="montant">Montant :</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-euro" aria-hidden="true"></span></div>';
			$contenu .= '<input type="text" class="form-control" name="montant" id="montant" placeholder="0.00€">';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="code">Code (max : 16) : *</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span></div>';
			$contenu .= '<input type="text" class="form-control" name="code" id="code" placeholder="Code" maxlength="16" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="date">Date de validté (MM/AA) (max : 5) : *</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div>';
			$contenu .= '<input type="text" class="form-control" name="date" id="date" placeholder="AA/MM" maxlength="5" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="codesecu">Code de sécurité (max : 3) : *</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></div>';
			$contenu .= '<input type="text" class="form-control" name="codesecu" id="codesecu" placeholder="111" maxlength="3" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<button class="btn btn-primary" name="participer" value="Participer">Participer</button>';
			$contenu .= '</form>';
		} else {
			$this->app->flash('danger', 'Impossible de trouver la cagnotte !');
			$this->app->redirect($this->app->urlFor('index'));
		}
		return $contenu;
	}

	private function participer() {
		$post = $this->app->request->post();
		$errors = array();
		$errorsMessage = '';
		if (!is_null($post)) {
			foreach ($post as $k => $v) {
				if (!empty($v)) {
					$data[$k] = filter_var($v, FILTER_SANITIZE_STRING);
					if ($k === 'date') {
						if (!preg_match("/[0-9]{2}\/[0-9]{2}/", $v)) {
							$errors[] = 'Date de validté incorrecte.';
						}
					}
				} else {
					$errors[] = ucfirst($k) .' incorrect.';
				}
			}

			if (!empty($errors)) {
				$errorsMessage .= '<ul>';
				$errorsMessage .= 'Whoops, des erreurs ont été rencontrées :';
				foreach ($errors as $error) {
					$errorsMessage .= '<li>' . $error . '</li>';
				}
				$errorsMessage .= '</ul>';
				$this->app->flash('danger', $errorsMessage);
				$this->app->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]));
			} else {
				$cagnotte = Cagnotte::where('urlContribution', '=', $this->data)->first();
				if (!is_null($cagnotte)) {
					if ($cagnotte->cloture == 0) {
						$montant = filter_var($post['montant'], FILTER_SANITIZE_NUMBER_FLOAT);
						$total = ($cagnotte->montant + $montant);
						$cagnotte->update(array('montant' => $total));
						$this->app->flash('success', 'Merci d\'avoir participé à la cagnotte !');
						$this->app->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]));
					} else {
						$this->app->flash('info', 'Vous ne pouvez par participer à cette cagnotte car elle est cloturée !');
						$this->app->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]));
					}
				} else {
					$this->app->flash('danger', 'Impossible de participer à cette cagnotte');
						$this->app->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]));
				}
			}
		} else {
			$this->app->redirect($this->app->urlFor('cagnotte.participationForm', ['url' => $this->data]));
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
						$this->app->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]));
						$coffret->update(array('url' => uniqid(), 'statut' => 'payé'));
					} else {
						$this->app->flash('danger', 'La cagnotte est déjà cloturée !');
						$this->app->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]));
					}
				} else {
					$this->app->flash('info', 'Le montant de la cagnotte (' . $cagnotte->montant . '&euro;) est inférieur au montant du coffret (' . $coffret->montant . '&euro;) !');
						$this->app->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]));
				}
			} else {
				$this->app->flash('danger', 'Impossible de trouver la cagnotte !');
				$this->app->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]));
			}
		} else {
			$this->app->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]));
		}
		return null;
	}

	private function connexionForm() {
		$contenu = '<div class="page-header">';
		$contenu .= '<h1>Connexion</h1>';
		$contenu .= '</div>';
		$contenu .= '<form action="' . $this->app->urlFor('cagnotte.connexion', ['url' => $this->data]) . '" method="post">';
		$contenu .= '<div class="form-group">';
		$contenu .= '<label for="password">Mot de passe du coffret :</label>';
		$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div>';
		$contenu .= '<input type="password" class="form-control" name="password" id="password" required>';
		$contenu .= '</div>';
		$contenu .= '</div>';
		$contenu .= '<button name="Se connecter" class="btn btn-primary" value="Se Connecter">Se connecter</button>';
		$contenu .= '</form>';
		return $contenu;
	}

	private function connexion(){
		if (!isset($_SESSION['cagnotte_edit'])) {
			$post = $this->app->request->post();
			if(!empty($post['password'])){
				$cagnotte = Cagnotte::where('urlGestion', '=', $this->data)->first();
				$coffret = Coffret::where('id', '=', $cagnotte->coffret_id)->first();
				if (!is_null($coffret)) {
					$password = filter_var($post['password'], FILTER_SANITIZE_STRING);
					if(password_verify($password, $coffret->password)){
						$_SESSION['cagnotte_edit'] = "allowed";
						$this->app->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]));
					} else {
						$this->app->flash('danger', 'Mot de passe incorrect');
						$this->app->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]));
					}
				} else {
					$this->app->flash('danger', 'Impossible de trouver la cagnote');
					$this->app->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]));
				}
			} else {
				$this->app->flash('danger', 'Impossible de vous connecter');
				$this->app->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]));
			}
		} else {
			$this->app->redirect($this->app->urlFor('cagnotte.gestion', ['url' => $this->data]));
		}
		return null;
	}

	private function deconnexion(){
		if (isset($_SESSION['cagnotte_edit'])) {
			unset($_SESSION['cagnotte_edit']);
			$this->app->flash('success', 'Vous avez été déconnecté');
			$this->app->redirect($this->app->urlFor('cagnotte.connexionForm'));
		} else {
			$this->app->redirect($this->app->urlFor('cagnotte.connexionForm'));
		}
		return null;
	}

	private function gestion() {
		$content = '';
		if (!isset($_SESSION['cagnotte_edit'])) {
			$this->app->redirect($this->app->urlFor('cagnotte.connexionForm', ['url' => $this->data]));
		} else {
			$cagnotte = Cagnotte::where('urlGestion', '=', $this->data)->first();
			$coffret = Coffret::where('id', '=', $cagnotte->coffret_id)->first();
			$content .= '<label>Progression :</label>';
			$content .= '<div class="progress">';
			$percent = round((($cagnotte->montant * 100) / $coffret->montant), 0);
			$content .= '<div class="progress-bar" role="progressbar" aria-valuenow="' . $percent . '" aria-valuemin="0" aria-valuemax="' . $cagnotte->montant . '" style="width: ' . $percent . '%;">';
			$content .= $percent . '%';
			$content .= '</div>';
			$content .= '</div>';
			$content .= '<p><u>Progression</u> : ' . $cagnotte->montant . '&euro; / ' . $coffret->montant . '&euro; (' . $percent . '%)</p>';
			if ($cagnotte->cloture == 0) {
				if ($cagnotte->montant >= $coffret->montant) {
					$content .= '<p><a onclick="return confirm(\'Voulez vous cloturer la cagnotte ?\')" href="' . $this->app->urlFor('cagnotte.cloturer', ['url' => $this->data]) . '" class="btn btn-primary">Cloturer la cagnotte</a>';
					$content.= '&nbsp;&nbsp;<a class="btn btn-success" href="'.$this->app->urlFor('cagnotte.deconnexion').'">deconnexion</a></p>';
				} else {
					$content .= '<p><button class="btn btn-default" disabled>Cloturer la cagnotte</button>';
					$content.= '&nbsp;&nbsp;<a class="btn btn-success" href="'.$this->app->urlFor('cagnotte.deconnexion').'">deconnexion</a></p>';
				}
			} else {
				$content .= '<p class="alert alert-info">Cette cagnotte est cloturée';
				$content.= '&nbsp;&nbsp;<a class="btn btn-success" href="'.$this->app->urlFor('cagnotte.deconnexion').'">deconnexion</a></p>';
			}
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

			case 'deconnexion':
				$content = $this->deconnexion();
				break;
		}
		return $content;
	}
}