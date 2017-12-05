<?php
namespace App\Controller;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\PanierModel;
use App\Model\ProduitModel;
class CommandeController implements ControllerProviderInterface
{
    private $produitModel;
    private $panierModel;

    public function connect(Application $app){
        $controllers = $app['controllers_factory'];



        return $controllers;
    }
}