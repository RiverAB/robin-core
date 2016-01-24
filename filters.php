<?php

/**
 * Check if the user is logged in to the CMS
 * ----------------------------------------------------------------------------
 */
$app->router->filter('robin_auth', function() use($app) {
    
    if (!$app->user->loggedIn()) {

        if ($app->isAjaxRequest()) {

            $app->session->setFlash('error', 'Your session has timed out.');
            return $app->jsonResponse(null, false, ['User is not logged in'], 401);

        }
        
        $app->session->setFlash('error', 'You need to be logged in to access this page');
        return $app->redirect($app->router->route('robin.login'));
    }

});


$app->router->filter('robin_setup', function() use($app) {

    $app->locales->setLocale($app->session->get('robin.current_locale', 'en'));
    $app->view->addGlobal('current_locale', $app->locales->getCurrent());

    if (!$app->user->isUserCreated()) {
        return $app->redirect($app->router->route('robin.register_admin'));
    }

});
