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
            ->select('pa.id', 'pr.nom', 'pa.quantite', 'pa.prix', 'pa.dateAjoutPanier', 'pr.photo')
            ->from('paniers', 'pa')
            ->innerJoin('pa','produits', 'pr', 'pa.produit_id=pr.id')
            ->addOrderBy('pa.id', 'ASC');
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
            ->from('paniers')
            ->where('id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function deleteProduit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('paniers')
            ->where('id = :id')
            ->setParameter('id',(int)$id);
        return $queryBuilder->execute();
    }

    public function updateProduit($id, $quantite) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('paniers')
            ->set('quantite', $quantite)
            ->where('produit_id = :id')
            ->setParameter('id',$id);
        return $queryBuilder->execute();
    }

    public function insertExistingProduit($id, $n, $quantite){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('paniers')
            ->set('quantite', $n+$quantite)
            ->where('produit_id = :id')
            ->setParameter('id',$id);
        return $queryBuilder->execute();

    }

    public function countProduit($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('quantite as n')
            ->from('paniers')
            ->where('produit_id = :id')
            ->setParameter('id',$id);
        return $queryBuilder->execute()->fetch();
    }

    public function getPrixPanier(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('sum(prix) as prixTotal')
            ->from('paniers');
        return $queryBuilder->execute()->fetch();
    }
}