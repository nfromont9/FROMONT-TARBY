<?php
namespace App\Controller;

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


    public function index(Application $app) {
        return $this->showProduits($app);
    }

    public function showProduits(Application $app) {
        $this->panierModel = new PanierModel($app);
        $produits = $this->panierModel->getPanier();
        return $app["twig"]->render('frontOff/Panier/showPanier.html.twig',['data'=>$produits]);
    }



    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\PanierController::index')->bind('panier.index');
        $controllers->get('/showP', 'App\Controller\PanierController::showProduits')->bind('panier.showProduits');
        $controllers->get('/addP', 'App\Controller\PanierController::addProduit')->bind('panier.addProduit');

        return $controllers;
    }
}
