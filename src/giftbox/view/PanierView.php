<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 03/01/2017
 * Time: 17:44
 */

namespace giftbox\view;


use giftbox\models\Cagnotte;
use giftbox\models\Coffret;
use giftbox\models\CoffretContenu;
use giftbox\models\Prestation;

class PanierView
{

	private $data;
	private $app;

	public function __construct($app = null, $array = null)
	{
		$this->app = $app;
		$this->data = $array;
	}

	private function panier($recap = null) {
		$total = 0;
		$html = '<table>';
		$html .= '<caption>Article(s) : ' . (isset($_SESSION['panier']) ? $_SESSION['panier']['qua'] : '0') . '</caption>';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th>Nom</th>';
		$html .= '<th>Quantité</th>';
		$html .= '<th>Prix</th>';
		if (is_null($recap)) {
			$html .= '<th>Actions</th>';
		}
		$html .= '</tr>';
		$html .= '</thead>';
		if (isset($_SESSION['panier']) && $_SESSION['panier']['qua'] > 0) {
			$html .= '<tbody>';
			$uri = $this->app->request->getRootUri();
			foreach ($_SESSION['panier']['article'] as $article => $a) {
				$html .= '<tr>';
				$html .= '<td><a href="' . $this->app->urlFor('prestation', ['id' => $a['id']]) . '">' . $article . '</a></td>';
				$html .= '<td>' . $a['qua'] . '</td>';
				$html .= '<td>' . $a['prix'] . ' &euro;</td>';
				if (is_null($recap)) {
					$html .= '<td>';
					$html .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $a['id']]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
					$html .= '<a href="' . $this->app->urlFor('supprimer', ['id' => $a['id']]) . '"><img src="' . $uri . '/web/img/trash.png" width="32" alt="Supprimer"></a>';
					$html .= '</td>';
				}
				$html .= '</tr>';
				$total = $total + $a['prix'];
			}
			$html .= '</tbody>';
		} else {
			$html .= '<tr class="alert alert-info"><td colspan="4">Panier vide</td></tr>';
		}
		$html .= '<tfoot>';
		$html .= '<tr><td colspan="' . (is_null($recap) ? 3 : 2) . '" style="text-align: right">Total:</td><td>' . $total . ' &euro;</td></tr>';
		$html .= '</tfoot>';
		$html .= '<table>';
		if ((isset($_SESSION['panier']) && is_null($recap)) || (isset($_SESSION['panier']) && $_SESSION['panier']['qua'] == 0)) {
			$html .= '<p><a href="' . $this->app->urlFor('informations') . '">Sauvegarder le coffret</a></p>';
		}
		return $html;
	}

	private function add() {
		$prestationId = $this->data[0];
		$prestation = Prestation::where('id', '=', $prestationId)->first();
		if (!empty($prestation)) {
			if (!isset($_SESSION['panier'])) {
				$_SESSION['panier'] = array(
					'qua' => 1,
					'article' => array()
				);
				$_SESSION['panier']['article'][$prestation->nom] = array(
					'id' => $prestation->id,
					'qua' => 1,
					'prix' => $prestation->prix
				);
			} else {
				$_SESSION['panier']['qua'] = ($_SESSION['panier']['qua'] + 1);
				if (isset($_SESSION['panier']['article'][$prestation->nom])) {
					$_SESSION['panier']['article'][$prestation->nom]['qua'] = ($_SESSION['panier']['article'][$prestation->nom]['qua'] + 1);
					$_SESSION['panier']['article'][$prestation->nom]['prix'] = ($_SESSION['panier']['article'][$prestation->nom]['prix'] + $prestation->prix);
				} else {
					$_SESSION['panier']['article'][$prestation->nom] = array(
						'id' => $prestation->id,
						'qua' => 1,
						'prix' => $prestation->prix
					);
				}
			}
            $this->app->flash('success', 'Prestation ajoutée du panier');
            $this->app->response->redirect($this->app->urlFor('panier'), 200);
		} else {
            $this->app->flash('error', 'Impossible de trouver la prestation');
            $this->app->response->redirect($this->app->urlFor('panier'), 200);
		}
		return null;
	}

	public function remove(){
		$prestation = Prestation::where('id', '=', $this->data[0])->first();
		if ($prestation != null) {
            if (isset($_SESSION['panier']['article'][$prestation->nom])) {
                $_SESSION['panier']['qua'] = ($_SESSION['panier']['qua'] - 1);
                if ($_SESSION['panier']['article'][$prestation->nom]['qua'] == 1) {
                    unset($_SESSION['panier']['article'][$prestation->nom]);
                } else {
                    $_SESSION['panier']['article'][$prestation->nom]['qua'] = ($_SESSION['panier']['article'][$prestation->nom]['qua'] - 1);
                    $_SESSION['panier']['article'][$prestation->nom]['prix'] = ($_SESSION['panier']['article'][$prestation->nom]['prix'] - $prestation->prix);
                }
                $this->app->flash('success', 'Prestation supprimée du panier');
                $this->app->response->redirect($this->app->urlFor('panier'), 200);
            } else {
                $this->app->flash('success', 'Whoops ! Des erreurs ont été rencontrées');
                $this->app->response->redirect($this->app->urlFor('panier'), 200);
            }
        } else {
            $this->app->flash('info', 'Impossible de supprimer le prestation du panier.');
            $this->app->response->redirect($this->app->urlFor('panier'), 200);
        }
		return null;
	}

	private function informations() {
        if (isset($_SESSION['panier'])) {
            if (count($_SESSION['panier']['article']) >= 2) {
                $formulaire = '<form id="formulaire" action="' . $this->app->urlFor('validation') . '" method="post">';
                $formulaire .= '<label for="nom">Nom : *</label>';
                $formulaire .= '<input type="text" name="nom"id="nom" placeholder="Nom" required>';
                $formulaire .= '<label for="prenom">Prénom : *</label>';
                $formulaire .= '<input type="text" name="prenom" id="prenom" placeholder="Prénom" required>';
                $formulaire .= '<label for="email">Email : *</label>';
                $formulaire .= '<input type="email" name="email" id="email" placeholder="Email" required>';
                $formulaire .= '<label for="message">Message : *</label>';
                $formulaire .= '<textarea name="message" id="message" cols="50" rows="5" required></textarea>';
                $formulaire .= '<label for="password">Mot de passe : </label>';
                $formulaire .= '<input type="password" name="password" id="password" placeholder="Mot de passe">';
                $formulaire .= '<label for="password_repeat">Mot de passe (Vérif.) : </label>';
                $formulaire .= '<input type="password" name="password_repeat" id="password_repeat" placeholder="Mot de passe (Vérif.)">';
                $formulaire .= '<label for="paiement">Mode de paiement : *</label>';
                $formulaire .= '<select name="paiement">';
                $formulaire .= '<option value="classique">Classique</value>';
                $formulaire .= '<option value="cagnotte">Cagnotte</value>';
                $formulaire .= '</select>';
                $formulaire .= '<button>Valider</button>';
                $formulaire .= '</form>';
                $formulaire .= '<p>* Champs obligatoires.</p>';
                return $formulaire;
            } else {
                $this->app->flash('info', 'Il vous faut au moins une prestation de deux catégories différentes.');
                $this->app->response->redirect($this->app->urlFor('panier'), 200);
            }
        } else {
            $this->app->flash('info', 'Votre panier est vide !');
            $this->app->response->redirect($this->app->urlFor('panier'), 200);
        }
	}

	private function validation() {
		$errors = array();
		$contenu = '';
		$errorsMessage = '';
		$data = $this->app->request->post();
        $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
        $password_repeat = filter_var($data['password_repeat'], FILTER_SANITIZE_STRING);
        unset($data['password']);
        unset($data['password_repeat']);
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

            if (!empty($password)) {
                if (strcmp($password, $password_repeat) != 0) {
                    $errors[] = 'Les mots de passes ne correspondent pas.';
                }
            }
        } else {
            $this->app->flash('error', 'Erreur dans le formulaire');
            $this->app->response->redirect($this->app->urlFor('informations'), 200);
        }

		if (!empty($errors)) {
            $errorsMessage .= '<ul>';
            $errorsMessage .= 'Whoops, des erreurs ont été rencontrées :';
			foreach ($errors as $error) {
                $errorsMessage .= '<li>' . $error . '</li>';
			}
            $errorsMessage .= '</ul>';
            $this->app->flash('error', $errorsMessage);
            $this->app->response->redirect($this->app->urlFor('informations'), 200);
		} else {
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
            } else {
                $data['password'] = '';
            }
			$data['url'] = uniqid();
			$data['urlGestion'] = uniqid();
			$data['statut'] = 'payé';
            $data['montant'] = 0;

			foreach ($_SESSION['panier']['article'] as $article => $a) {
				$data['montant'] += ($a['qua'] * $a['prix']);
			}
			$contenu .= '<p><u>Mode de paiement :</u> ' . $data['paiement'] . '</p>';
			$contenu .= $this->render('recapitulatif');
			$_SESSION['coffret'] = $data;
            if (strcmp($data['paiement'], 'cagnotte') == 0) {
                $contenu .= '<p><a href="' . $this->app->urlFor('cagnotte.creation') . '">Sauvegarder le coffret</a></p>';
            } else {
                $contenu .= '<p><a href="' . $this->app->urlFor('paiement.form') . '">Sauvegarder le coffret</a></p>';
            }
		}
		return $contenu;
	}

	private function cagnotteCreation() {
        if (isset($_SESSION['coffret'])) {
            $coffret_id = $this->creerCoffret('cagnotte');
            $cagnotte = array(
                'coffret_id' => $coffret_id,
                'montant' => 0,
                'urlContribution' => uniqid(),
                'urlGestion' => uniqid(),
                'cloture' => 0
            );
            Cagnotte::create($cagnotte);

            $urlCoffret = 'URL Cagnotte : http://' . $_SERVER['HTTP_HOST']. $this->app->urlFor('cagnotte.participation', ['url' => $cagnotte['urlContribution']]);
            $gestion = '';
            if (isset($_SESSION['coffret']['password'])) {
                if ($_SESSION['coffret']['password'] != '') {
                    $gestion = '<p>URL de gestion de la cagnotte : http://'. $_SERVER['HTTP_HOST'] . $this->app->urlFor('cagnotte.gestion', ['url' => $cagnotte['urlGestion']]) . '</p>';
                }
            }
            $this->app->flash('success', '<p>Coffret sauvegardé avec succès</p><p>' . $urlCoffret . '</p>' . $gestion);
            $this->app->response->redirect($this->app->urlFor('index'), 200);
        } else {
            $this->app->response->redirect($this->app->urlFor('index'), 200);
        }
    }

    private function paiementForm() {
        if (isset($_SESSION['coffret'])) {
            $formulaire = '<form id="paiementForm" action="' . $this->app->urlFor('paiement.validation') . '" method="post">';
            $formulaire .= '<label for="code">Code (max : 12) : *</label>';
            $formulaire .= '<input type="text" name="code" id="code" placeholder="Code" maxlength="12" required>';
            $formulaire .= '<label for="date">Date de validté (MM/AA) (max : 5) : *</label>';
            $formulaire .= '<input type="text" name="date" id="date" placeholder="AA/MM" maxlength="5" required>';
            $formulaire .= '<label for="codesecu">Code de sécurité (max : 3) : *</label>';
            $formulaire .= '<input type="text" name="codesecu" id="codesecu" placeholder="111" maxlength="3" required>';
            $formulaire .= '<button>Valider</button>';
            $formulaire .= '</form>';
            $formulaire .= '<p>* Champs obligatoires.</p>';
            return $formulaire;
        } else {
            $this->app->response->redirect($this->app->urlFor('index'), 200);
        }
    }

	private function paiementValidation() {
	    if (isset($_SESSION['coffret'])) {
            $errors = array();
            $contenu = '';
            $errorsMessage = '';
            $data = $this->app->request->post();
            if (!is_null($data)) {
                foreach ($data as $k => $v) {
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
                    $this->app->flash('error', $errorsMessage);
                    $this->app->response->redirect($this->app->urlFor('paiement.form'), 200);
                } else {
                    $this->creerCoffret();
                    $urlCoffret = 'URL Coffret : http://' . $_SERVER['HTTP_HOST']. $this->app->urlFor('coffret', ['url' => $coffret->url]);
                    $gestion = '';
                    if (isset($_SESSION['coffret']['password'])) {
                        if ($_SESSION['coffret']['password'] != '') {
                            $gestion = '<p>URL Coffret gestion : http://'. $_SERVER['HTTP_HOST'] . $this->app->urlFor('coffret_ges', ['url' => $coffret->urlGestion]) . '</p>';
                        }
                    }
                    $this->app->flash('success', '<p>Coffret sauvegardé avec succès</p><p>' . $urlCoffret . '</p>' . $gestion);
                    $this->app->response->redirect($this->app->urlFor('index'), 200);
                }
            } else {
                $this->app->flash('error', 'Erreur dans le formulaire');
                $this->app->response->redirect($this->app->urlFor('paiement.form'), 200);
            }
        } else {
            $this->app->response->redirect($this->app->urlFor('index'), 200);
        }
	    return null;
    }

    private function creerCoffret($paiement = null) {
        if (!is_null($paiement)) {
            if (strcmp($paiement, 'cagnotte') == 0) {
                $_SESSION['coffret']['url'] = '';
                $_SESSION['coffret']['statut'] = 'impayé';
            }
        }
        $coffret = Coffret::create($_SESSION['coffret']);
        $coffret_id = $coffret->id;
        foreach ($_SESSION['panier']['article'] as $article => $a) {
            CoffretContenu::create(
                array(
                    'coffret_id' => $coffret_id,
                    'prestation_id' => $a['id'],
                    'qua' => $a['qua'],
                )
            );
        }
        unset($_SESSION['panier']);
        unset($_SESSION['coffret']);
        return $coffret_id;
    }

	public function render($v) {
		switch ($v) {
			case 'panier':
			default:
				$content = $this->panier();
				break;

			case 'add':
				$content = $this->add();
				break;

			case 'remove':
				$content = $this->remove();
				break;

			case 'infos':
				$content = $this->informations();
				break;

			case 'validation':
				$content = $this->validation();
				break;

            case 'recapitulatif':
                $content = $this->panier('recap');
                break;

            case 'cagnotteCreation':
                $content = $this->cagnotteCreation();
                break;

            case 'paiementForm':
                $content = $this->paiementForm();
                break;

            case 'paiementValidation':
                $content = $this->paiementValidation();
                break;

			case 'save':
				$content = $this->save();
				break;
		}
		return $content;
	}

}