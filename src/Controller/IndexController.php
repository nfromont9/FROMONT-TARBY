<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0


class IndexController implements ControllerProviderInterface
{
    public function index(Application $app)
    {
        if ($app['session']->get('roles') == 'ROLE_CLIENT')
             return $app["twig"]->render("frontOff/frontOFFICE.html.twig"); 
        // remplacer par une redirection :  return $app->redirect($app["url_generator"]->generate("Panier.index"));
        if ($app['session']->get('roles') == 'ROLE_ADMIN')
            return $app["twig"]->render("backOff/backOFFICE.html.twig");
        // remplacer par une redirection
        
        return $app["twig"]->render("accueil.html.twig");
    }

    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\IndexController::index')->bind('accueil');
        return $index;
    }


}
