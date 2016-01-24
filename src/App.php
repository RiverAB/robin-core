<?php namespace RobinCms\Core;

use Closure;
use Illuminate\Container\Container;
use RobinCms\Core\Http\JsonResponse;
use RobinCms\Core\Http\Redirect;

class App extends Container
{
    /**
     * List of application paths
     * @var array
     */
    protected $paths     = [];

    /**
     * List of registered service providers
     * @var array
     */
    protected $providers = [];

    /**
     * Debug status
     * @var boolean
     */
    protected $debug = false;

    /**
     * Modules
     * @var array
     */
    protected $modules = [];


    /**
     * @param array $paths  List of named application paths (name => path)
     */
    public function __construct(array $paths = array())
    {
        $this->setPaths($paths);

        // Let's add this container to it self so we can inject it
        // using dependency injection.
        $app = $this;
        $this->singleton('RobinCms\Core\App', function() use($app) {
            return $app;
        });
    }


    public function registerModule(Module $module)
    {
        $this->modules[$module->getKey()] = $module;
    }


    public function getModules()
    {
        return $this->modules;
    }


    /**
     * Set application paths
     * 
     * @param array $paths  List of named application paths (name => path)
     */
    public function setPaths(array $paths = array())
    {
        $this->paths = array_merge($this->paths, $paths);
    }

    
    /**
     * Get a named path
     * 
     * @param  string $name     
     * @param  string $fallback     Returned if path name doesn't exist
     * @return string
     */
    public function path($name, $fallback = null)
    {
        return array_key_exists($name, $this->paths)
            ? $this->paths[$name]
            : $fallback;
    }


    /**
     * Register a service provider to the container
     * 
     * @param  string $className Full class name incl. namespace
     * @return self
     */
    public function serviceProvider($className)
    {
        if (!array_key_exists($className, $this->providers)) {

            $this->providers[$className] = $this->make($className, [$this]);
            if (!$this->providers[$className] instanceof ServiceProvider) {
                throw new Exception("Service providers must inherit RobinCms\Core\ServiceProvider");
            }
            
            $this->providers[$className]->register();            
        }

        return $this;
    }


    public function setAdminRoutes(Closure $callback)
    {
        $app    = $this;
        $prefix = $this->config->get('admin.url_prefix');

        $this->router->group(['prefix' => $prefix, 'before' => ['robin_auth', 'robin_setup']],
            function($router) use($app, $callback) {

                $callback($app);

        });
    }

    /**
     * Register multiple service providers
     * 
     * @param  array    $classNames     List of class names incl. namespace
     * @return self
     */
    public function serviceProviders(array $classNames)
    {
        foreach($classNames as $className) {
            $this->serviceProvider($className);
        }

        return $this;
    }


    /**
     * Create a new Redirect instance
     * 
     * @param  string  $url
     * @param  integer $statusCode
     * @return Redirect
     */
    public function redirect($url, $statusCode = 302)
    {
        return new Redirect($url, $statusCode);
    }


    /**
     * Turn debugging on or off
     * 
     * @param  boolean $status
     * @return self
     */
    public function debug($status)
    {
        $this->debug = (boolean) $status;
        return $this;
    }


    /**
     * Check if debug is on
     * 
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }


    /**
     * Check if the request is an ajax request
     * 
     * @return boolean
     */
    public function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }


    /**
     * Json encode the body and set the header as json
     * 
     * @return string
     */
    public function jsonResponse($body = null, $success = true, array $errors = array(), $code = 200)
    {
        return new JsonResponse($body, $success, $errors, $code);
    }


    /**
     * Start the application
     *
     * @return void
     */
    public function start()
    {
        $method = isset($_SERVER['REQUEST_METHOD'])? $_SERVER['REQUEST_METHOD'] : null;
        $uri    = isset($_SERVER['REQUEST_URI'])? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : null;

        $response = $this->router->dispatch($method, $uri, $this);

        if ($response instanceof JsonResponse) {
            header('Content-Type: application/json');

            if ($response->getCode() != 200) {
                http_response_code($response->getCode());
            }

            $response = $response->getJson();
        }

        if (is_string($response)) {
            echo $response;
            exit;
        }

        if ($response instanceof Redirect) {
            $response->send();
        }

        return $response;
    }

}