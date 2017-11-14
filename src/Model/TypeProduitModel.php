<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class TypeProduitModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }


    public function getAllTypeProduits() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id', 'p.libelle')
            ->from('typeProduits', 'p')
            ->addOrderBy('p.libelle', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}