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

            $this->produitModel = new ProduitModel($app);

            $produit = $this->produitModel->getProduit($id);
            $produit['quantite'] = $quantite;

            $produits = $this->produitModel->getAllProduits();

            if (!preg_match("/^[0-9]{1,}$/", $produit['quantite'])) $erreurs['quantite']='Veuillez saisir un chiffre correct';

            if(!empty($erreurs))
            {
                // return $app["twig"]->render('frontOff/frontOFFICE.html.twig',['produits'=>$produits,'erreurs'=>$erreurs]);
                return $app->redirect($app["url_generator"]->generate("accueil", ['produits'=>$produits,'erreurs'=>$erreurs]));
            }
            else
            {
                $produit['user_id'] = $app['session']->get('user_id');
                $produit['produit_id'] = $id;
                $produit['commande_id'] = 1;

                $this->panierModel = new PanierModel($app);
                $this->panierModel->insertProduit($produit);
                return $app->redirect($app["url_generator"]->generate("accueil"));
            }

        }
        else
            return $app->abort(404, 'error Pb data form Add');
    }

    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\PanierController::index')->bind('panier.index');
        $controllers->get('/showP', 'App\Controller\PanierController::showProduits')->bind('panier.showProduits');
        $controllers->get('/addP', 'App\Controller\PanierController::addProduit')->bind('panier.addProduit');

        return $controllers;
    }
}
