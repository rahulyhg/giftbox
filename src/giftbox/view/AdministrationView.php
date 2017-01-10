<?php

namespace giftbox\view;

use giftbox\models\Prestation;
use giftbox\models\Categorie;
use giftbox\models\Administrateur;
use giftbox\models\Note;

class AdministrationView {

	private $data;
	private $app;

	public function __construct($app = null, $data = null) {
		$this->app = $app;
		$this->data = $data;
	}

	private function index() {
		$contenu = '';
		if (isset($_SESSION['admin'])) {
			$this->app->response->redirect($this->app->urlFor('administration.prestations'), 200);
		} else {
			$contenu = '<form action="' . $this->app->urlFor('administration.connexion') . '" method="post">';
			$contenu .= '<label for="email">Email :</label>';
			$contenu .= '<input type="email" name="email" id="email" required>';
			$contenu .= '<label for="password">Mot de passe :</label>';
			$contenu .= '<input type="password" name="password" id="password" required>';
			$contenu .= '<button name="Se connecter" value="Se Connecter">Se connecter</button>';
			$contenu .= '</form>';
		}
		return $contenu;
	}

	private function connexion() {
		$errors = array();
		$contenu = '';
		$data = $this->app->request->post();
		if (!is_null($data)) {
			foreach ($data as $k => $v) {
				if (!empty($v)) {
					$data[$k] = filter_var($v, FILTER_SANITIZE_STRING);
					if ($k === 'email') {
						if(!filter_var($data[$k], FILTER_VALIDATE_EMAIL)) {
							$errors[] = 'Email incorrect.';
						}
					}
				} else {
					$errors[] = ucfirst($k) .' incorrect.';
				}
			}
		} else {
			$this->app->flash('error', 'Erreur dans le formulaire');
			$this->app->response->redirect($this->app->urlFor('administration'), 200);
		}
		
		if (!empty($errors)) {
			$errorsMessage = '<ul>';
			$errorsMessage .= 'Whoops, des erreurs ont été rencontrées :';
			foreach ($errors as $error) {
				$errorsMessage .= '<li>' . $error . '</li>';
			}
			$errorsMessage .= '</ul>';
			$this->app->flash('error', $errorsMessage);
			$this->app->response->redirect($this->app->urlFor('informations'), 200);
		} else {
			$administrateur = Administrateur::where('email', '=', $data['email'])->first();
			if (!is_null($administrateur)) {
				if (password_verify($data['password'], $administrateur->password)) {
					$_SESSION['admin'] = $administrateur->id;
					var_dump($_SESSION);
					$this->app->flash('success', 'Vous êtes maintenant connecté');
					$this->app->response->redirect($this->app->urlFor('administration.prestations'), 200);
				} else {
					$this->app->flash('error', 'Mot de passe incorrect');
					$this->app->response->redirect($this->app->urlFor('administration'), 200);
				}
			} else {
				$this->app->flash('error', 'Impossible de trouver l\'utilisateur');
				$this->app->response->redirect($this->app->urlFor('administration'), 200);
			}
		}
		return null;
	}

	private function deconnexion() {
		if (isset($_SESSION['admin'])) {
			unset($_SESSION['admin']);
			$this->app->flash('success', 'Vous êtes maintenant déconnecté');
			$this->app->response->redirect($this->app->urlFor('index'), 200);
		}
	}

	private function prestations() {
		$prestations = Prestation::all();
		$contenu = '<table>';
		$contenu .= '<caption>Prestations : ' . count($prestations) . '</caption>';
		$contenu .= '<thead>';
		$contenu .= '<tr>';
		$contenu .= '<th>Nom</th>';
		$contenu .= '<th>Prix</th>';
		$contenu .= '<th>Note</th>';
		$contenu .= '<th>Visible</th>';
		$contenu .= '<th>Actions</th>';
		$contenu .= '</tr>';
		$contenu .= '</thead>';
		$contenu .= '<tbody>';
		foreach ($prestations as $prestation => $p) {
			$notes = Note::where('prestationId', '=', $p->id)->get(array('note'));
			$notesTotal = 0;
			$moyenne = 0;
			if ($p->votes > 0) {
				foreach ($notes as $note => $n) {
					$notesTotal = ($notesTotal + $n->note);
				}
				$moyenne = round(($notesTotal / $p->votes), 2) . '/5';
			} else {
				$moyenne = 'Pas de note(s)';
			}
			$contenu .= '<tr>';
			$contenu .= '<td>' . $p->nom . '</td>';
			$contenu .= '<td>' . $p->prix . '</td>';
			$contenu .= '<td>' . $moyenne . '</td>';
			$contenu .= '<td>' . (($p->visible == 1) ? 'Visible' : 'Cachée') . '</td>';
			$contenu .= '<td>';
			$contenu .= '<a href="' . $this->app->urlFor('prestation.supprimer', ['id' => $p->id]) . '">Supprimer</a>';
			if ($p->visible == 0) {
				$contenu .= '&nbsp;|&nbsp;<a href="' . $this->app->urlFor('prestation.afficher', ['id' => $p->id]) . '">Afficher</a>';
			} else {
				$contenu .= '&nbsp;|&nbsp;<a href="' . $this->app->urlFor('prestation.cacher', ['id' => $p->id]) . '">Cacher</a>';
			}
			$contenu .= '</td>';
			$contenu .= '</tr>';
		}
		$contenu .= '</tbody>';
		$contenu .= '<tfoot>';
		$contenu .= '<tr>';
		$contenu .= '<th>Nom</th>';
		$contenu .= '<th>Prix</th>';
		$contenu .= '<th>Note</th>';
		$contenu .= '<th>Visible</th>';
		$contenu .= '<th>Actions</th>';
		$contenu .= '</tr>';
		$contenu .= '</tfoot>';
		$contenu .= '</table>';
		return $contenu;
	}

	private function supprimer() {
		if (isset($_SESSION['admin'])) {
			
		} else {
			$this->app->flash('error', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->response->redirect($this->app->urlFor('index'), 200);
		}
		return null;
	}

	private function cacher() {
		if (isset($_SESSION['admin'])) {
			$prestation = Prestation::where('id', '=', $this->data[0])->first();
			if (!is_null($prestation)) {
				$prestation->visible = 0;
				$prestation->save();
				$this->app->flash('success', 'Prestation cachée avec succès');
				$this->app->response->redirect($this->app->urlFor('administration.prestations'), 200);
			} else {
				$this->app->flash('info', 'Prestation introuvable');
				$this->app->response->redirect($this->app->urlFor('administration.prestations'), 200);
			}
		} else {
			$this->app->flash('error', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->response->redirect($this->app->urlFor('index'), 200);
		}
		return null;
	}

	private function afficher() {
		if (isset($_SESSION['admin'])) {
			$prestation = Prestation::where('id', '=', $this->data[0])->first();
			if (!is_null($prestation)) {
				$prestation->visible = 1;
				$prestation->save();
				$this->app->flash('success', 'Prestation affichée avec succès');
				$this->app->response->redirect($this->app->urlFor('administration.prestations'), 200);
			} else {
				$this->app->flash('info', 'Prestation introuvable');
				$this->app->response->redirect($this->app->urlFor('administration.prestations'), 200);
			}
		} else {
			$this->app->flash('error', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->response->redirect($this->app->urlFor('index'), 200);
		}
		return null;
	}

	public function render($aff) {
		switch($aff) {
			case 'connexion':
				$content = $this->connexion();
				break;

			case 'deconnexion':
				$content = $this->deconnexion();
				break;

			case 'aprestations':
				$content = $this->prestations();
				break;

			case 'supprimer':
				$content = $this->supprimer();
				break;

			case 'cacher':
				$content = $this->cacher();
				break;

			case 'afficher':
				$content = $this->afficher();
				break;

			case 'index':
			default:
				$content = $this->index();
				break;
		}

		return $content;
	}

}