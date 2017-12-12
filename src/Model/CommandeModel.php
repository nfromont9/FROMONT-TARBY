<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\DBAL\Connection;

class CommandeModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function createCommande($user_id){
        $conn=$this->db;

        $conn->beginTransaction();
        $requestSQL = $conn->prepare('select sum(prix*quantite) as prix from paniers WHERE user_id = :idUser');
        $requestSQL->execute(['idUser'=>$user_id]);
        $prix = $requestSQL->fetch()['prix'];
        $conn->commit();

        $conn->beginTransaction();
        $requestSQL = $conn->prepare('insert into commandes(user_id,prix,etat_id)
            values (?,?,?)');
        $requestSQL->execute([$user_id,$prix,1]);
        $lastinsertid=$conn->lastInsertId();
        $requestSQL=$conn->prepare('update paniers set commande_id=? where user_id=?
            and commande_id is null');
        $requestSQL->execute([$lastinsertid,$user_id]);
        $conn->commit();
    }

    public function getAllCommandes() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id', 'user_id', 'prix', 'date_achat', 'etat_id')
            ->from('commandes')
            ->addOrderBy('id', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function getAllCommandesByUID($uid) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.id', 'c.user_id', 'c.prix', 'c.date_achat', 'c.etat_id', 'e.libelle')
            ->from('commandes', 'c')
            ->innerJoin('c', 'etats', 'e', 'c.etat_id=e.id')
            ->where('user_id = :uid')
            ->setParameter('uid',$uid)
            ->addOrderBy('date_achat', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }


}