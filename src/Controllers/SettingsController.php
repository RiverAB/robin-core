<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class SettingsController extends Controller
{

    public function showSettingsEdit($groupKey)
    {
        $group = $this->settings->getGroup($groupKey);

        if (!$group) {
            $this->session->setFlash('error', 'There is no settings group having the key ' . $groupKey);
            return $this->redirect($this->router->route('robin.dashboard'));
        }
        $data = [
            'settings'      => $group,
            'current_menu' => 'settings_' . $groupKey,
        ];

        return $this->view->render('@robin/settings-edit.tpl', $data);
    }

    public function updateSettings()
    {
        $csrf_token = arr_val($_POST, 'csrf_token');
        $response   = $this->jsonResponse();
        $errors     = [];

        if (!$this->csrf->validateToken($csrf_token, 'update-settings-content')) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            $errors[] = 'Seems like the page have timed out. Please try again.';
        }
        
        $groupKey = arr_val($_POST, 'info.key');
        $fields   = arr_val($_POST, 'data');

        if (!$this->settings->keyExists($groupKey)) {
            $errors[] = "Invalid settings group key";
        }

        if (!$errors) {
            $updated = $this->settings->updateSettings($groupKey, $fields);
            if (is_string($updated)) {
                $errors[] = $updated;
            } else if ($updated !== true) {
                $errors[] = "Something went wrong. Content not updated";
            }
        }

        if ($errors) {
            $response->setErrors($errors);
        } else {
            $response->setMessage('Settings content updated!');
        }

        return $response;
    }

}
