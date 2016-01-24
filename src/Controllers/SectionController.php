<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class SectionController extends Controller
{

    public function showSectionEdit($sectionKey)
    {
        $section = $this->sections->getSection($sectionKey);

        if (!$section) {
            $this->session->setFlash('error', 'There is no section having the key ' . $sectionKey);
            return $this->redirect($this->router->route('robin.dashboard'));
        }
        $data = [
            'section'      => $section,
            'current_menu' => 'section_' . $sectionKey,
        ];

        return $this->view->render('@robin/section-edit.tpl', $data);
    }

    public function updateSection()
    {
        $csrf_token = arr_val($_POST, 'csrf_token');
        $response   = $this->jsonResponse();
        $errors     = [];

        if (!$this->csrf->validateToken($csrf_token, 'update-section-content')) {
            // Invalid CSRF-token. Let's stop this madness. Return an error!
            $errors[] = 'Seems like the page have timed out. Please try again.';
        }
        
        $locale     = arr_val($_POST, 'locale');
        $sectionKey = arr_val($_POST, 'info.key');
        $fields     = arr_val($_POST, 'data');


        if (!$this->locales->exists($locale)) {
            $errors[] = "Invalid locale"; 
        }

        if (!$this->sections->hasSection($sectionKey)) {
            $errors[] = "Invalid section key";
        }

        if (!$errors) {
            $updated = $this->sections->updateContent($sectionKey, $fields);
            if (is_string($updated)) {
                $errors[] = $updated;
            } else if ($updated !== true) {
                $errors[] = "Something went wrong. Content not updated";
            }
        }

        if ($errors) {
            $response->setErrors($errors);
        } else {
            $response->setMessage('Section content updated!');
        }

        return $response;
    }

}
