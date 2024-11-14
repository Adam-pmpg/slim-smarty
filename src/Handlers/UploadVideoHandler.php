<?php

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Smarty;

class UploadVideoHandler
{
    private $templateDir;
    private $compileDir;

    public function __construct($templateDir, $compileDir)
    {
        $this->templateDir = $templateDir;
        $this->compileDir = $compileDir;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($this->templateDir);
        $smarty->setCompileDir($this->compileDir);

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
    }
}
