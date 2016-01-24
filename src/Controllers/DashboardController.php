<?php namespace RobinCms\Core\Controllers;

use RobinCms\Core\Controller;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $data = [
            'current_menu' => 'dashboard'
        ];

        return $this->view->render('@robin/dashboard.tpl', $data);
    }

    public function setLocale()
    {
        $response = $this->jsonResponse();

        $locale = arr_val($_POST, 'locale');

        if (!$this->locales->exists($locale)) {
            return $response->setSuccess(false)
                ->setErrors('Invalid locale slug');
        }

        $this->session->setFlash('locale_changed', true);
        $this->session->set('robin.current_locale', $locale);
        return $response;
    }

}
