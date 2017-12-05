<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
use Symfony\Component\Validator\Constraints\Date;

class CommandeModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

}