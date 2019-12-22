<?php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$isDevMode = false;

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'TeeHee0414@Lee',
    'dbname'   => 'content',
    'charset'   => 'utf8',
);

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
$qb = $entityManager->createQueryBuilder();