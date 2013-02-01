<?php
require '_libs/Slim/Slim.php';
require '_libs/Data/DB.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('debug' => true));
$log = $app->getLog();
$db = new \Data\DB('mysql:host=localhost;dbname=things', 'things', 'mythings');

// GET routes
$app->get('/', function () {
    
});

$app->get('/things', function() use ($app, $db){
    $sql = <<<SQL
SELECT 
    t.*, 
    u.username
FROM
    things t,
    users u
WHERE
    t.creator = u.id AND 
    t.visible = 1;
SQL;
    
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($db->run($sql)->fetchAll());
});

$app->get('/:user/', function($user) use ($app, $db) {
    $sql = <<<SQL
SELECT 
    t.*, 
    u.username
FROM
    things t,
    users u
WHERE
    t.creator = u.id AND 
    t.visible = 1 AND
    username = :username;
SQL;

    $app->response()->header("Content-Type", "application/json");
    echo json_encode($db->run($sql, array('username' => $user))->fetchAll());
});

//POST Routes
$app->post('/things', function() use ($app, $db){
        $sql = <<<SQL
INSERT INTO 
    things
VALUES (NULL, :text, NOW(), NOW(), 1, :creator)
SQL;

    $app->response()->header("Content-Type", "application/json");
    $thing = $app->request()->post();
    $thing['creator'] = 1;
    $db->run($sql, $thing)->getLastInsertId();
});

$app->run();
