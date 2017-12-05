<?php
namespace App\Controller;

use App\Model\ProduitModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Model\PanierModel;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class PanierController implements ControllerProviderInterface
{
    private $panierModel;
    private $produitModel;


    public function index(Application $app) {
        return $this->showProduits($app);
    }

    public function showProduits(Application $app) {
        $this->panierModel = new PanierModel($app);
        $produits = $this->panierModel->getPanier();
        return $app["twig"]->render('frontOff/Panier/showPanier.html.twig',['data'=>$produits]);
    }

    public function addProduit(Application $app) {
        if (isset($_POST['id']) && isset($_POST['quantite'])) {
            $id = htmlspecialchars($_POST['id']);
            $quantite = htmlspecialchars($_POST['quantite']);
            if (is_numeric($quantite)) $quantite = intval($quantite);

            $this->produitModel = new ProduitModel($app);

            $produit = $this->produitModel->getProduit($id);
            $produit['quantite'] = $quantite;
            $nbdispo = $produit['dispo']+$produit['stock'];

            if (!preg_match("/^[1-9][0-9]{0,}$/", $produit['quantite'])) $erreurs['quantiteAdd']='Veuillez saisir un chiffre correct';
            if ($quantite>$nbdispo) $erreurs['tooManyItems'] = 'Erreur : vous avez saisi une quantité supérieure à la quantité disponible';

            if(!empty($erreurs)) {
                return $this->erreurForm($app, $erreurs);
            } else {
                $this->panierModel = new PanierModel($app);
                $produit['user_id'] = $app['session']->get('user_id');
                $produit['produit_id'] = $id;
                $produit['commande_id'] = null;
                $n = $this->panierModel->countProduit($id);
                if($n['n'] == 0){
                    $this->panierModel->insertProduit($produit);
                }
                else {
                    $this->panierModel->insertExistingProduit($id, $n['n'], $quantite);
                }
                return $app->redirect($app["url_generator"]->generate("accueil"));
            }

        }
        else
            return $app->abort(404, 'error Pb data form Add');
    }

    public function deleteProduit(Application $app) {
        if (isset($_POST['id']) && isset($_POST['quantite'])) {
            $id = htmlspecialchars($_POST['id']);
            $quantite = htmlspecialchars($_POST['quantite']);

            $this->panierModel = new PanierModel($app);
            $produit = $this->panierModel->getProduit($id);

            if (!preg_match("/^[1-9][0-9]{0,}$/", $quantite)) $erreurs['quantiteDel']='Veuillez saisir un chiffre correct';

            if (!empty($erreurs)) {
                return $this->erreurForm($app, $erreurs);
            } else if ($produit['quantite'] <= $quantite) {
                $this->panierModel->deleteProduit($id);
                return $app->redirect($app["url_generator"]->generate("accueil"));
            } else {
                $quantite = $produit['quantite']-$quantite;
                $this->panierModel->updateProduit($id, $quantite);
                return $app->redirect($app["url_generator"]->generate("accueil"));
            }
        }
        else
            return $app->abort(404, 'error Pb data form delete');
    }

    public function erreurForm(Application $app, $erreurs) {
        $this->produitModel = new ProduitModel($app);
        $this->panierModel = new PanierModel($app);
        $produits = $this->produitModel->getAllProduits();
        $panier = $this->panierModel->getPanier();

        return $app["twig"]->render('frontOff/frontOFFICE.html.twig',
            ['panier'=>$panier ,'produits'=>$produits,'erreurs'=>$erreurs]);
    }

    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\PanierController::index')->bind('panier.index');
        $controllers->get('/showP', 'App\Controller\PanierController::showProduits')->bind('panier.showProduits');
        $controllers->get('/addP', 'App\Controller\PanierController::addProduit')->bind('panier.addProduit');
        $controllers->get('/delP', 'App\Controller\PanierController::deleteProduit')->bind('panier.deleteProduit');

        return $controllers;
    }
}
