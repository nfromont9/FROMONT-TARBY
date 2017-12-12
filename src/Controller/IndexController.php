<?php
namespace App\Controller;
use App\Model\TypeProduitModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\PanierModel;
use App\Model\ProduitModel;
class IndexController implements ControllerProviderInterface
{
    private $produitModel;
    private $panierModel;
    private $typeProduitModel;

    public function index(Application $app)
    {
        $this->panierModel = new PanierModel($app);
        $this->produitModel= new ProduitModel($app);
        $this->typeProduitModel = new TypeProduitModel($app);


        $produits = $this->produitModel->getAllProduits();
        $panier = $this->panierModel->getPanier();
        $typeProduits = $this->typeProduitModel->getAllTypeProduits();

        $tpi = $app['session']->get('type_produit_id');
        if (!isset($tpi) || is_null($tpi) || !is_numeric($tpi)) {
            $app['session']->set('type_produit_id', -1);
        }

        if ($app['session']->get('roles') == 'ROLE_CLIENT')
             return $app["twig"]->render("frontOff/frontOFFICE.html.twig", ['produits'=>$produits, 'panier'=>$panier, 'type_produits'=>$typeProduits]);
            //return $app->redirect($app["url_generator"]->generate("Panier.index", ['produits'=>$produits, 'panier'=>$panier, 'type_produits'=>$typeProduits]));
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