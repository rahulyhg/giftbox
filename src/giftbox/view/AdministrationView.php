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
			$this->app->redirect($this->app->urlFor('administration.prestations'));
		} else {
			$contenu = '<div class="page-header">';
			$contenu .= '<h1>Administration</h1>';
			$contenu .= '</div>';
			$contenu .= '<form action="' . $this->app->urlFor('administration.connexion') . '" method="post">';
			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="email">Email :</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>';
			$contenu .= '<input type="email" class="form-control" name="email" id="email" placeholder="john.doe@email.fr" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';
			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="password">Mot de passe :</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div>';
			$contenu .= '<input type="password" class="form-control" name="password" id="password" placeholder="mot de passe" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';
			$contenu .= '<button type="submit" class="btn btn-primary" name="seconnecter" value="Se Connecter">Se connecter</button>';
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
			$this->app->flash('danger', 'Erreur dans le formulaire');
			$this->app->redirect($this->app->urlFor('administration'));
		}
		
		if (!empty($errors)) {
			$errorsMessage = '<ul>';
			$errorsMessage .= 'Whoops, des erreurs ont été rencontrées :';
			foreach ($errors as $error) {
				$errorsMessage .= '<li>' . $error . '</li>';
			}
			$errorsMessage .= '</ul>';
			$this->app->flash('danger', $errorsMessage);
			$this->app->redirect($this->app->urlFor('informations'));
		} else {
			$administrateur = Administrateur::where('email', '=', $data['email'])->first();
			if (!is_null($administrateur)) {
				if (password_verify($data['password'], $administrateur->password)) {
					$_SESSION['admin'] = $administrateur->id;
					$this->app->flash('success', 'Vous êtes maintenant connecté');
					$this->app->redirect($this->app->urlFor('administration.prestations'));
				} else {
					$this->app->flash('danger', 'Mot de passe incorrect');
					$this->app->redirect($this->app->urlFor('administration'));
				}
			} else {
				$this->app->flash('danger', 'Impossible de trouver l\'utilisateur');
				$this->app->redirect($this->app->urlFor('administration'));
			}
		}
		return null;
	}

	private function deconnexion() {
		if (isset($_SESSION['admin'])) {
			unset($_SESSION['admin']);
			$this->app->flash('success', 'Vous êtes maintenant déconnecté');
			$this->app->redirect($this->app->urlFor('index'));
		}
	}

	private function prestations() {
		if (isset($_SESSION['admin'])) {
			$prestations = Prestation::all();
			$contenu = '<p><a href="' . $this->app->urlFor('prestation.ajouter') . '" class="btn btn-primary">Ajouter une prestation</a></p>';
			$contenu .= '<table class="table table-bordered table-striped">';
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
				$contenu .= '<td><span class="label label-' . (($p->visible == 1) ? 'success' : 'info') . '">' . (($p->visible == 1) ? 'Visible' : 'Masquée') . '</span></td>';
				$contenu .= '<td>';
				$contenu .= '<a class="btn btn-danger" onclick="return confirm(\'Voulez vous supprimer cette prestation ?\')" href="' . $this->app->urlFor('prestation.supprimer', ['id' => $p->id]) . '">Supprimer</a>';
				if ($p->visible == 0) {
					$contenu .= '&nbsp;<a class="btn btn-warning" onclick="return confirm(\'Voulez vous afficher cette prestation ?\')" href="' . $this->app->urlFor('prestation.afficher', ['id' => $p->id]) . '">Afficher</a>';
				} else {
					$contenu .= '&nbsp;<a class="btn btn-warning" onclick="return confirm(\'Voulez vous masquer cette prestation ?\')" href="' . $this->app->urlFor('prestation.cacher', ['id' => $p->id]) . '">Masquer</a>';
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
		} else {
			$this->app->flash('danger', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->redirect($this->app->urlFor('index'));
		}
		return $contenu;
	}

	private function supprimer() {
		if (isset($_SESSION['admin'])) {
			$prestation = Prestation::where('id', '=', $this->data[0])->first();
			if (!is_null($prestation)) {
				Prestation::destroy($prestation->id);
				$this->app->flash('success', 'Prestation supprimée avec succès');
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			} else {
				$this->app->flash('info', 'Prestation introuvable');
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			}
		} else {
			$this->app->flash('danger', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->redirect($this->app->urlFor('index'));
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
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			} else {
				$this->app->flash('info', 'Prestation introuvable');
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			}
		} else {
			$this->app->flash('danger', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->redirect($this->app->urlFor('index'));
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
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			} else {
				$this->app->flash('info', 'Prestation introuvable');
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			}
		} else {
			$this->app->flash('danger', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->redirect($this->app->urlFor('index'));
		}
		return null;
	}

	private function ajouter() {
		$contenu = '';
		if (isset($_SESSION['admin'])) {
			$categories = Categorie::all();
			$contenu = '<div class="page-header">';
			$contenu .= '<h1>Ajouter une prestation</h1>';
			$contenu .= '</div>';
			$contenu .= '<form action="' . $this->app->urlFor('administration.prestation.ajouter') . '" method="post" enctype="multipart/form-data">';
			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="nom">Nom :</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div>';
			$contenu .= '<input type="text" class="form-control" placeholder="Place de concert" name="nom" id="nom" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="descr">Description :</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div>';
			$contenu .= '<textarea name="descr" id="message" placeholder="Place de concert du groupe XXXX" class="form-control" rows="3" required></textarea>';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="prix">Prix :</label>';
			$contenu .= '<div class="input-group">';
			$contenu .= '<div class="input-group-addon"><span class="glyphicon glyphicon-euro" aria-hidden="true"></span></div>';
			$contenu .= '<input type="text" class="form-control" placeholder="25.99" name="prix" id="prix" required>';
			$contenu .= '</div>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="cat_id">Catégorie :</label>';
			$contenu .= '<select name="cat_id" class="form-control">';
			foreach ($categories as $categorie => $c) {
				$contenu .= '<option value="' . $c->id . '">' . $c->nom . '</value>';
			}
			$contenu .= '</select>';
			$contenu .= '</div>';

			$contenu .= '<div class="form-group">';
			$contenu .= '<label for="img">Image :</label>';
			$contenu .= '<input type="file" name="img" id="img" accept="image/*" required>';
			$contenu .= '<p class="help-block">Image de présentation de la prestation</p>';
			$contenu .= '</div>';

			$contenu .= '<div class="checkbox">';
			$contenu .= '<label>';
			$contenu .= '<input type="checkbox" name="visible" id="visible" value="visible" checked>';
			$contenu .= 'Visible sur le site ?';
			$contenu .= '</label>';
			$contenu .= '</div>';

			$contenu .= '<button value="Ajouter" class="btn btn-primary">Ajouter</button>';
			$contenu .= '</form>';
		} else {
			$this->app->flash('danger', 'Vous n\'avez pas l\'autorisation pour faire cette action !');
			$this->app->redirect($this->app->urlFor('index'));
		}
		return $contenu;
	}

	private function ajouterPrestation() {
		if (isset($_SESSION['admin'])) {
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
				$this->app->flash('danger', 'Erreur dans le formulaire');
				$this->app->redirect($this->app->urlFor('administration'));
			}
			
			if (!empty($errors)) {
				$errorsMessage = '<ul>';
				$errorsMessage .= 'Whoops, des erreurs ont été rencontrées :';
				foreach ($errors as $error) {
					$errorsMessage .= '<li>' . $error . '</li>';
				}
				$errorsMessage .= '</ul>';
				$this->app->flash('danger', $errorsMessage);
				$this->app->redirect($this->app->urlFor('informations'));
			} else {
				$data['votes'] = 0;
				if (!isset($data['visible'])) {
					$data['visible'] = 0;
				} else {
					$data['visible'] = 1;
				}
				if (isset($_FILES['img'])) {
					$uri = $this->app->request->getRootUri();
					move_uploaded_file($_FILES['img']['tmp_name'], 'web/img/' . $_FILES['img']['name']);
					$data['img'] = $_FILES['img']['name'];
				} else {
					$data['img'] = 'noImage.png';
				}
				Prestation::create($data);
				$this->app->flash('success', 'Prestation ajoutée avec succès.');
				$this->app->redirect($this->app->urlFor('administration.prestations'));
			}
		} else {
			$this->app->flash('danger', 'Vous n\'avez pas l\'autorisation de faire cette action !');
			$this->app->redirect($this->app->urlFor('index'));
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

			case 'ajouter':
				$content = $this->ajouter();
				break;

			case 'ajouterPrestation':
				$content = $this->ajouterPrestation();
				break;

			case 'index':
			default:
				$content = $this->index();
				break;
		}

		return $content;
	}

}