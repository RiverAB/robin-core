<?php
include __DIR__ . '/filters.php';

$app->router->group(['prefix' => $app->config->get('admin.url_prefix', 'admin'), 'before' => 'robin_setup'], function($router) use($app) {

    /**
     * Public routes
     * ------------------------------------------------------------------------
     */
    $router->get('/', function() use($app) {
        if (!$app->user->loggedIn()) {
            return $app->redirect($app->router->route('robin.login'));
        } else {
            return $app->redirect($app->router->route('robin.dashboard'));
        }
    });

    $router->get(['/login', 'robin.login'],
        ['RobinCms\Core\Controllers\AuthController', 'showLogin']
    );

    $router->post(['/login', 'robin.login.do'],
        ['RobinCms\Core\Controllers\AuthController', 'doLogin']
    );


    /**
     * Private routes
     * ------------------------------------------------------------------------
     */
    $router->group(['before' => 'robin_auth'], function($router) use($app) {


        $router->get(['/logout', 'robin.logout'],
            ['RobinCms\Core\Controllers\AuthController', 'doLogout']
        );

        $router->get(['/dashboard', 'robin.dashboard'],
            ['RobinCms\Core\Controllers\DashboardController', 'showDashboard']
        );

        $router->post(['/locale/set', 'robin.locale.set'],
            ['RobinCms\Core\Controllers\DashboardController', 'setLocale']
        );


        # SECTIONS
        # ---------------------------
        $router->get(['/section/edit/{key:[a-zA-Z0-9\_\-]{2,}}', 'robin.section.edit'],
            ['RobinCms\Core\Controllers\SectionController', 'showSectionEdit']
        );

        $router->post(['/section/update', 'robin.section.update'],
            ['RobinCms\Core\Controllers\SectionController', 'updateSection']
        );

        $router->post(['/section/preview', 'robin.section.preview'], function() use($app) {
            $key  = arr_val($_POST, 'info.key');
            $data = arr_val($_POST, 'data', []);

            $app->sections->overrideContent($key, $data);

            return $app->make('App\Controllers\FrontController')->showHome();
        });


        # SETTINGS
        # ---------------------------
        $router->get(['/settings/edit/{key:[a-zA-Z0-9\_\-]{2,}}', 'robin.settings.edit'],
            ['RobinCms\Core\Controllers\SettingsController', 'showSettingsEdit']
        );

        $router->post(['/settings/update', 'robin.settings.update'],
            ['RobinCms\Core\Controllers\SettingsController', 'updateSettings']
        );


        # LISTS
        # ---------------------------
        $router->get(['/lists/{key:[a-zA-Z0-9\_\-]{2,}}', 'robin.list'],
            ['RobinCms\Core\Controllers\ListController', 'showList']
        );

        $router->get(['/lists/{key:[a-zA-Z0-9\_\-]{2,}}/add', 'robin.list.add'],
            ['RobinCms\Core\Controllers\ListController', 'addListItem']
        );

        $router->get(['/lists/{key:[a-zA-Z0-9\_\-]{2,}}/edit/{index:[a-zA-Z0-9\_\-]{2,}}', 'robin.list-item.edit'],
            ['RobinCms\Core\Controllers\ListController', 'showListItemEdit']
        );

        $router->post(['/lists/{key:[a-zA-Z0-9\_\-]{2,}}/item/{index:[a-zA-Z0-9\_\-]{2,}}/update', 'robin.list-item.update'],
            ['RobinCms\Core\Controllers\ListController', 'updateListItem']
        );

        $router->post(['/lists/{key:[a-zA-Z0-9\_\-]{2,}}/item/{index:[a-zA-Z0-9\_\-]{2,}}/delete', 'robin.list-item.delete'],
            ['RobinCms\Core\Controllers\ListController', 'deleteListItem']
        );

        $router->post(['/lists/{key:[a-zA-Z0-9\_\-]{2,}}/update-items-order', 'robin.list.update-items-order'],
            ['RobinCms\Core\Controllers\ListController', 'updateItemsOrder']
        );


        # USER
        # ---------------------------
        $router->get(['/you', 'robin.user.edit'],
            ['RobinCms\Core\Controllers\UserController', 'showUserEdit']
        );

        $router->post(['/you', 'robin.user.update'],
            ['RobinCms\Core\Controllers\UserController', 'updateUser']
        );


        # FILES
        # ---------------------------
        $router->get(['/files', 'robin.files'],
            ['RobinCms\Core\Controllers\FilesController', 'showFiles']
        );

        $router->get(['/files/get-files', 'robin.files.get-files'],
            ['RobinCms\Core\Controllers\FilesController', 'getFiles']
        );

        $router->get(['/files/browser', 'robin.files.browser'],
            ['RobinCms\Core\Controllers\FilesController', 'showFileBrowser']
        );

        $router->post(['/files/upload', 'robin.files.upload'],
            ['RobinCms\Core\Controllers\FilesController', 'upload']
        );

        $router->post(['/files/delete', 'robin.files.delete'],
            ['RobinCms\Core\Controllers\FilesController', 'delete']
        );

    });

});

if (!$app->user->isUserCreated()) {

    // Only show these routes if a user hasn't been registered yet.
    $app->router->group(['prefix' => $app->config->get('admin.url_prefix', 'admin')], function($router) use($app) {

        $router->get(['/register/admin', 'robin.register_admin'],
            ['RobinCms\Core\Controllers\UserController', 'showRegisterAdmin']
        );

        $router->post(['/register/admin', 'robin.register_admin.do'],
            ['RobinCms\Core\Controllers\UserController', 'registerAdmin']
        );

    });

}


$app->router->notFound(function() use($app) {
    return "404 - Page not found";
    return $app->view->render('@robin/pages/404.tpl');
});

$app->router->methodNotAllowed(function(){
    return "405 - Method not allowed";
});
