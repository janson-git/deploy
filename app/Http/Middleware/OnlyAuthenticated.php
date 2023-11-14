<?php

namespace App\Http\Middleware;

use Admin\App;
use Service\Auth\AuthInterface;
use Service\Data;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class OnlyAuthenticated
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Callable $next
     * @return mixed|Response
     */
    public function __invoke(
        Request $request,
        Response $response,
        $next
    ) {
        $auth = $this->getAuth();

        $data = (new Data(App::DATA_USERS))->readCached();

        if(!$data && empty($data)){
            $auth->setToken(\User\Auth::USER_ANONIM_TOKEN);
        } else {
            $auth->setToken($request->getCookieParam('tkn'));
        }

        $auth->loadUser();
        $auth->setUser($auth->getUser());

        if (!$auth->isAuthenticated()) {
            return $response->withRedirect('/auth/login');
        }

        return $next($request, $response);
    }

    private function getAuth(): AuthInterface
    {
        return $this->container->get('auth');
    }
}
