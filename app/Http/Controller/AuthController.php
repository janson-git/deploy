<?php

namespace App\Http\Controller;

use Service\Data;

class AuthController extends AbstractController
{
    /**
     * @var Data
     */
    protected $userData;

    /**
     * @var Data
     */
    protected $sessionsData;
    
    public function login()
    {
        $this->userData = new Data('user');
        $this->sessionsData = new Data('sessions');

        $this->setTitle(__('login'));

        if ($this->app->getRequest()->isPost()) {
            $status = false;
            $user = [];

            $key = $this->p('login');
            $userPassword = md5($this->p('password'));

            $users = $this->userData->readCached();

            if (isset($users[$key]) && $users[$key][\User\Auth::USER_PASS] == $userPassword) {
                $status = true;
                $user[$key] = $users[$key];
            }

            $response = $this->app->getResponse();

            if ($status === true && !empty($user)) {
                $token = $this->createToken($key);
                $sessions = $this->sessionsData->setData([$token => $key] + $this->sessionsData->read());
                $sessions->write();

                $response = $this->app->getCookiesPipe()->addCookie($response, 'tkn', $token);
                $this->app->stopAndRedirectWithResponse('/projects', $response);
            } else {
                $this->app->stopAndRedirectTo('/auth/login');
            }
        }

        return $this->view->render('auth/loginForm.blade.php');
    }

    public function logout()
    {
        $this->userData = new Data('user');
        $this->sessionsData = new Data('sessions');

        $token = $this->app->getRequest()->getCookieParam('tkn');

        $session = $this->sessionsData->read();
        if (isset($session[$token])) {
            unset($session[$token]);
        }
        $sessions = $this->sessionsData->setData($session);
        $sessions->write();

        // delete token cookie
        $response = $this->app->getCookiesPipe()
            ->deleteCookie($this->app->getResponse(), 'tkn');

        $this->app->stopAndRedirectWithResponse('/auth/login', $response);
    }

    public function register()
    {
        $this->userData = new Data('user');
        $this->sessionsData = new Data('sessions');

        $this->setTitle(__('registration'));

        if ($this->app->getRequest()->isPost()){
            // обработка данных юзера, валидация, добавление  в scope users
            $status = false;

            $key = $this->p('login');
            $userName = ($this->p('name')) ? $this->p('name') : '';
            $userPassword1 = md5($this->p('password1'));
            $userPassword2 = md5($this->p('password2'));

            //Проверка существования пользователя
            $users = $this->userData->readCached();

            if (isset($users[$key])) {
                $status  = true;
            }

            $id = $this->createToken($key);

            //Создание пользователя
            if($status === false && $userPassword1 === $userPassword2) {
                $user[$key][\User\Auth::USER_NAME] = $userName;
                $user[$key][\User\Auth::USER_PASS] = $userPassword1;
                $user[$key][\User\Auth::USER_ID] = $id;
                $user[$key][\User\Auth::USER_LOGIN] = $key;
                $userNew = $this->userData->setData($user + $this->userData->read());
                $userNew->write();
            }

            $response = $this->app->getResponse();

            //загрузка юзера и установка куков
            $token = $this->createToken($key);
            $sessions = $this->sessionsData->setData([$token => $key] + $this->sessionsData->read());
            $sessions->write();

            $response = $this->app->getCookiesPipe()->addCookie($response, 'tkn', $token);
            return $response->withRedirect('/projects');
        }

        return $this->view->render('auth/registerForm.blade.php');
    }

    protected function createToken(string $name): string
    {
        return md5(microtime() . $name);
    }
}
