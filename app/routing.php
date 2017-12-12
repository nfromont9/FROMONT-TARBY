<?php
//***************************************
// Montage des controleurs sur le routeur
$app->mount("/", new App\Controller\IndexController($app));
$app->mount("/produit", new App\Controller\ProduitController($app));
$app->mount("/panier", new App\Controller\PanierController($app));
$app->mount("/commande", new App\Controller\CommandController($app));
$app->mount("/connexion", new App\Controller\UserController($app));

