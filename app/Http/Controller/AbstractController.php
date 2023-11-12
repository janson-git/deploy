<?php

namespace App\Http\Controller;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class AbstractController
{
    /** @var \Admin\App */
    protected $app;

    /** @var \Admin\View */
    protected $view;

    /** @var \Slim\Http\Request */
    protected $request;

    /** @var \Slim\Http\Response */
    protected $response;

    protected $container;

    public function __construct(
        Container $container,
        Request $request,
        Response $response
    ) {
        $this->container = $container;

        $this->app = \Admin\App::getInstance();
        $this->view = $this->app->getContainer()->get('view');

        $this->request = $request;
        $this->response = $response;
    }
    
    public function p($name, $default = null)
    {
        return $this->request->getParam($name, $default);
    }
    
    public function setTitle($title)
    {
        $this->view->setHeader($title);
    }
    
    public function setSubTitle($subTitle)
    {
        $this->view->setTitle($subTitle);
    }
}
