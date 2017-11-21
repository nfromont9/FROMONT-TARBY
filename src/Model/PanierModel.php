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

    public function insertProduit($produit) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('panier')
            ->values([
                'id' => '?',
                'quantite' => '?',
                'prix' => '?',
                'dateAjoutPanier' => '?',
                'user_id' => '?',
                'produit_id' => '?',
                'commande_id' => '?'
            ])

            ->setParameter(0, $produit['id'])
            ->setParameter(1, $produit['quantite'])
            ->setParameter(2, $produit['prix'])
            ->setParameter(3, $produit['dateAjoutPanier'])
            ->setParameter(4, $produit['user_id'])
            ->setParameter(5, $produit['produit_id'])
            ->setParameter(6, $produit['commande_id']);
        return $queryBuilder->execute();
    }

    function getProduit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('quantite', 'prix', 'dateAjoutPanier', 'user_id', 'produit_id', 'commande_id')
            ->from('panier')
            ->where('id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function deleteProduit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('panier')
            ->where('id = :id')
            ->setParameter('id',(int)$id);
        return $queryBuilder->execute();
    }

}