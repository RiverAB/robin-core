<?php namespace RobinCms\Core;

class RobinServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->setPaths([
            'core'        => realpath(__DIR__ . '/../'),
            'admin_views' => realpath(__DIR__ . '/../views'),
        ]);


        /**
         * Settings and Content
         * --------------------------------------------------------------------
         */

        $this->singleton('RobinCms\Core\Content\Loader', function($c) {
            return new Content\Loader($c->config->get('storage.path'));
        });

        $this->singleton('RobinCms\Core\Content\Sections', function($c) {
            return new Content\Sections(
                $c->locales->getCurrent(),
                $c->make('RobinCms\Core\Content\Content')
            );
        });

        $this->singleton('RobinCms\Core\Content\Lists', function($c) {
            return new Content\Lists(
                $c->locales->getCurrent(),
                $c->make('RobinCms\Core\Content\Content')
            );
        });

        $this->singleton('RobinCms\Core\FileBrowser', function($c) {
            return new FileBrowser(
                $c->config->get('uploads.path'),
                $c->path('public')
            );
        });

        /**
         * Core libraries
         * --------------------------------------------------------------------
         */
        $this->singleton('RobinCms\Core\Robin');
        $this->singleton('RobinCms\Core\Content\Content');
        $this->singleton('RobinCms\Core\Content\Locales');
        $this->singleton('RobinCms\Core\Content\User');
        $this->singleton('RobinCms\Core\Content\Settings');
        $this->singleton('RobinCms\Core\Http\Router');
        $this->singleton('RobinCms\Core\Http\Session');
        $this->singleton('RobinCms\Core\Security\Csrf');


        /**
         * Set aliases
         * --------------------------------------------------------------------
         */
        $this->aliases([
            'content'        => 'RobinCms\Core\Content\Content',
            'settings'       => 'RobinCms\Core\Content\Settings',
            'locales'        => 'RobinCms\Core\Content\Locales',
            'lists'          => 'RobinCms\Core\Content\Lists',
            'sections'       => 'RobinCms\Core\Content\Sections',
            'router'         => 'RobinCms\Core\Http\Router',
            'session'        => 'RobinCms\Core\Http\Session',
            'csrf'           => 'RobinCms\Core\Security\Csrf',
            'user'           => 'RobinCms\Core\Content\User',
            'files'          => 'RobinCms\Core\FileBrowser',
            'view'           => 'Twig_Environment',
        ]);

        Controller::setApp($this->container);

        $this->twigSetup();

    }

    protected function twigSetup()
    {
        $this->container->singleton('Twig_Environment', function($app) {
            
            $theme = $app->config->get('theme', 'default');
            $views = rtrim($app->path('views'), "/\\") . '/' . $theme;

            $adminTheme = $app->config->get('admin.theme', 'default');
            $adminViews = rtrim($app->path('admin_views'), "/\\") . '/' . $adminTheme;

            $loader = new \Twig_Loader_Filesystem($views);
            $loader->addPath($adminViews, 'robin');

            $twig   = new \Twig_Environment($loader,[
                'debug' => $app->isDebug()
            ]);

            $twig->addExtension(new \Twig_Extension_Debug());

            $twig->addFunction(new \Twig_SimpleFunction('in_array', function($needle, $haystack) use($app) {
                return is_array($haystack) && in_array($needle, $haystack);
            }));

            $twig->addFunction(new \Twig_SimpleFunction('human_filesize', function($bytes, $decimals = 2) use($app) {
                $sz     = 'BKMGTP';
                $factor = floor((strlen($bytes) - 1) / 3);
                return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'b';
            }));

            $twig->addFunction(new \Twig_SimpleFunction('get_file_type', function($filename) use($app) {
                return get_file_type($filename);
            }));

            $twig->addGlobal('robin',       $app->make('RobinCms\Core\Robin'));
            $twig->addGlobal('error',       $app->session->getFlash('error'));
            $twig->addGlobal('success',     $app->session->getFlash('success'));
            $twig->addGlobal('got_message', ($app->session->getFlash('success') || $app->session->getFlash('error')));

            $twig->addGlobal('current_menu', '');
            $twig->addGlobal('default_locale', $app->config->get('default_locale'));

            return $twig;
        });
        $this->container->alias('Twig_Environment', 'view');

    }

}