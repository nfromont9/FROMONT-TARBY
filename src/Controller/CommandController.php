<?php
namespace App\Controller;

use App\Model\CommandeModel;
use App\Model\ProduitModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use App\Model\PanierModel;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class CommandController implements ControllerProviderInterface{

    private $commandeModel;

    public function index(Application $app) {
        return $this->getAllCommandes($app);
    }

    public function showCommandes(Application $app) {
        $this->commandeModel = new CommandeModel($app);

        $commandes = $this->commandeModel->getAllCommandes();

        return $app["twig"]->render("backOff/Commande/showCommandes.html.twig", ['data'=>$commandes]);
    }

    public function validCommande(Application $app, $id) {
        $this->commandeModel = new CommandeModel($app);
        $commandes = $this->commandeModel->validCommande($id);
        return $app["twig"]->render("backOff/Commande/showCommandes.html.twig", ['data'=>$commandes]);
    }

    public function deleteCommande(Application $app, $id) {
        $this->commandeModel = new CommandeModel($app);
        $commandes = $this->commandeModel->deleteCommande($id);
        return $app["twig"]->render("backOff/Commande/showCommandes.html.twig", ['data'=>$commandes]);
    }

    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\commandController::index')->bind('commande.index');

        $controllers->get('/showCo', 'App\Controller\commandController::showCommandes')->bind('commande.showCommandes');
        $controllers->post('/validCo', 'App\Controller\commandController::validCommande')->bind('commande.validCommande');
        $controllers->get('/deleteCo', 'App\Controller\commandController::deleteCommande')->bind('commande.deleteCommande');


        return $controllers;
    }
}
