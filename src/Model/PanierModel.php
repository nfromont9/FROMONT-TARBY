<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
use Symfony\Component\Validator\Constraints\Date;

class PanierModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getPanier() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id', 'p.quantite', 'p.prix', 'p.prix', 'p.dateAjoutPanier', 'p.user_id', 'p.produit_id', 'p.commande_id')
            ->from('paniers', 'p')
            ->addOrderBy('p.id', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function insertProduit($produit) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('paniers')
            ->values([
                'id' => '?',
                'quantite' => '?',
                'prix' => '?',
                'user_id' => '?',
                'produit_id' => '?',
                'commande_id' => '?'
            ])

            ->setParameter(0, $produit['id'])
            ->setParameter(1, $produit['quantite'])
            ->setParameter(2, $produit['prix'])
            ->setParameter(3, $produit['user_id'])
            ->setParameter(4, $produit['produit_id'])
            ->setParameter(5, $produit['commande_id']);
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