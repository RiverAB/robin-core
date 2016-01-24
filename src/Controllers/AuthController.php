<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class AuthController extends Controller
{

    public function showLogin()
    {
        return $this->view->render('@robin/login.tpl');
    }

    public function doLogin()
    {
        $csrf_token = arr_val($_POST, 'csrf_token');
        $username   = arr_val($_POST, 'username');
        $password   = arr_val($_POST, 'password');

        if (!$this->csrf->validateToken($csrf_token, 'login')) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            $this->session->setFlash('error', 'Something went wrong. Please try again.');
            return $this->redirect($this->router->route('robin.login'));

        }

        if (!$this->user->login($username, $password)) {
            // Username and/or Password
            $this->session->setFlash('error', 'Invalid username and/or password');
            return $this->redirect($this->router->route('robin.login'));
        }

        return $this->redirect($this->router->route('robin.dashboard'));
    }

    public function doLogout()
    {
        $this->user->logout();
        return $this->redirect($this->router->route('robin.login'));
    }

}
