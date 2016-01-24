<?php namespace RobinCms\Core\Http;

use Illuminate\Container\Container;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Phroute\Phroute\RouteCollector;

class Router extends RouteCollector
{
    protected $errorCallbacks = [];


    /**
     * @param $name
     * @param array $args
     * @return string
     */
    public function route($name, array $args = null)
    {
        $route = parent::route($name, $args);
        return is_string($route)? '/'. ltrim($route, "/") : $route;
    }


    /**
     * Set 404 callback
     * 
     * @param  Callable $callback
     * @return void
     */
    public function notFound(Callable $callback)
    {
        $this->errorCallbacks[404] = $callback;
    }


    /**
     * Set 405 callback
     * 
     * @param  Callable $callback
     * @return void
     */
    public function methodNotAllowed(Callable $callback)
    {
        $this->errorCallbacks[405] = $callback;
    }


    /**
     * Trigger a registered error callback
     * 
     * @param  integer  $httpCode
     * @return string|null
     */
    public function triggerError($httpCode)
    {
        http_response_code($httpCode);

        if (array_key_exists($httpCode, $this->errorCallbacks)) {
            return call_user_func_array($this->errorCallbacks[$httpCode], []);
        }

        return null;
    }


    /**
     * Dispatch the router
     * 
     * @param  string         $requestMethod
     * @param  string         $requestUri
     * @param  Container|null $container
     * @return string
     */
    public function dispatch($requestMethod, $requestUri, Container $container = null)
    {
        $resolver     = new RouterResolver($container);
        $responseCode = 200;
        $response     = null;
        try {
    
            $response = (new Dispatcher($this->getData(), $resolver))
                ->dispatch($requestMethod, $requestUri);
    
        } catch(HttpRouteNotFoundException $e) {

            $responseCode = 404;

        } catch(HttpMethodNotAllowedException $e) {

            $responseCode = 405;
        }

        if ($responseCode != 200) {

            if (array_key_exists($responseCode, $this->errorCallbacks)) {
                return $this->triggerError($responseCode);
            }
        }

        return $response;
    }
}