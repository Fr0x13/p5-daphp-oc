<?php

namespace App\lib;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    protected $vue;
    protected $templatesPaths;
    protected $datas;
    protected $httpCode;
    protected $currentSession;
    protected $flash;
    protected $app;

    public function __construct($app, $vue, $templatesPaths = null, $datas, $httpCode = null)
    {
        $this->app = $app;
        $this->vue = $vue;
        $this->templatesPaths = $templatesPaths;
        $this->datas = $datas;
        $this->httpCode = $httpCode;
        $this->currentSession = Authenticator::getSessionInfo();
        $this->flash = Flash::getFlash();
    }

    public function render()
    {
        if (null !== $this->flash) {
            $this->datas = array_merge($this->datas, ['flash' => $this->flash]);
        }

        $config = new Config();

        $this->datas = array_merge($this->datas, ['currentSession' => $this->currentSession, 'basePath' => $config->getBasePath()]);

        $loader = new FilesystemLoader('../Templates/' . $this->app);

        $loader->addPath('../Templates/partials');

        if (null != $this->templatesPaths && is_string($this->templatesPaths)) {
            $loader->addPath($this->templatesPaths);
        } else {
            foreach ($this->templatesPaths as $template) {
                $loader->addPath($template);
            }
        }

        $twig = new Environment($loader, ['cache' => false]);
        $res = $twig->load($this->vue);

        if (null === $this->httpCode) {
            http_response_code(200);
        } else {
            http_response_code($this->httpCode);
        }

        echo $res->render($this->datas);
    }
}
