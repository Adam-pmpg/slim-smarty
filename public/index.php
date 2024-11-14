<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/', function ($request, $response) {
    $smarty = new \Smarty();
    $smarty->setTemplateDir(__DIR__ . '/../templates');
    $smarty->setCompileDir(__DIR__ . '/templates_c');
    $response->getBody()->write($smarty->fetch('index.tpl'));
    return $response;
});

// Nowy endpoint GET /hello/{name}
$app->get('/hello/{name}', function ($request, $response, $args) {
    $smarty = new \Smarty();
    $smarty->setTemplateDir(__DIR__ . '/../templates');
    $smarty->setCompileDir(__DIR__ . '/templates_c');

    // Przypisanie wartości {name} do zmiennej Smarty
    $name = htmlspecialchars($args['name']); // Dla bezpieczeństwa
    $smarty->assign('name', $name);

    // Renderowanie szablonu hello.tpl
    $response->getBody()->write($smarty->fetch('hello.tpl'));
    return $response;
});

$app->run();