<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class PanierModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getPanier() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id', 'p.prix', 'p.prix', 'p.dateAjoutPanier', 'p.user_id', 'p.produit_id', 'p.commande_id')
            ->from('paniers', 'p')
            ->addOrderBy('p.id', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}