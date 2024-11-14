<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$templateDir = __DIR__ . '/../templates';
$compileDir = __DIR__ . '/templates_c';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

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

// Nowy endpoint /test
$app->get('/test', function ($request, $response) use ($templateDir, $compileDir) {
    $smarty = new \Smarty();
    $smarty->setTemplateDir(__DIR__ . '/../templates');
    $smarty->setCompileDir(__DIR__ . '/templates_c');

    // Przykładowe dane, które można przekazać do szablonu test.tpl
    $smarty->assign('message', 'To jest testowy endpoint /test.');

    // Renderowanie szablonu test.tpl
    $response->getBody()->write($smarty->fetch('test.tpl'));
    return $response;
});

// Nowy endpoint POST /update-video
$app->post('/upload-video', function ($request, $response) use ($templateDir, $compileDir) {
    $smarty = new \Smarty();
    $smarty->setTemplateDir($templateDir);
    $smarty->setCompileDir($compileDir);

    // Pobranie danych POST z requestu
    $data = $request->getParsedBody();
    $chunkIndex = htmlspecialchars($data['chunkIndex'] ?? '');  // Dla bezpieczeństwa użyj htmlspecialchars
    $totalChunks = htmlspecialchars($data['totalChunks'] ?? '');

    // Przypisanie danych do szablonu Smarty
    $smarty->assign('chunkIndex', $chunkIndex);
    $smarty->assign('totalChunks', $totalChunks);

    // Renderowanie szablonu update-video.tpl z przekazanymi danymi
    $response->getBody()->write($smarty->fetch('update-video.tpl'));
    return $response;
});

$app->run();