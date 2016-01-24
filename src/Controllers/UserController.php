<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class UserController extends Controller
{

    public function showUserEdit()
    {
        $data = [
            'user'         => $this->user->getUser(),
            'current_menu' => 'user',
        ];

        return $this->view->render('@robin/user-edit.tpl', $data);
    }

    public function updateUser()
    {
        $csrf_token = arr_val($_POST, 'csrf_token');
        $user       = arr_val($_POST, 'user');
        $response   = $this->jsonResponse();
        $errors     = [];

        if (!$this->csrf->validateToken($csrf_token, 'user_update')) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            $errors[] = 'The session seems to have timed out. Please try again.';
        } else {
            $result = $this->user->update($user);
            if (is_string($result)) {
                $errors[] = $result;
            } else if ($result !== true) {
                $errors[] = "User not saved. An unknown error occurred.";
            }
        }
        
        if ($errors) {
            $response->setErrors($errors);
        } else {
            $response->setMessage("User info updated");
        }

        return $response;
    }

    public function showRegisterAdmin()
    {
        $data = [
            'post_data' => $this->session->getFlash('last_post', []),
        ];
        
        return $this->view->render('@robin/register.tpl', $data);
    }

    public function registerAdmin()
    {

        $user = [
            'username'         => arr_val($_POST, 'username'),
            'email'            => arr_val($_POST, 'email'),
            'password'         => arr_val($_POST, 'password'),
            'confirm_password' => arr_val($_POST, 'confirm_password'),
        ];
    
        $errors     = [];

        if (!$this->csrf->validateToken(arr_val($_POST, 'csrf_token'), 'create-admin')) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            $errors[] = 'Seems like this form has timed out. Please try again.';
        } else {
            $result = $this->user->update($user, $forcePassword = true);
            if (is_array($result)) {
                $errors = $result;
            }
        }
        
        if ($errors) {
            $this->session->setFlash('error', '* ' . implode($errors, '<br />* '));
            $this->session->setFlash('last_post', $user);
            return $this->redirect($this->router->route('robin.register_admin'));
        }

        $this->session->setFlash('success', 'Admin user created! You can now login.');
        return $this->redirect($this->router->route('robin.login'));

    }

}
