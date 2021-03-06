<?php namespace RobinCms\Core\Http;

use Illuminate\Container\Container;
use Phroute\Phroute\HandlerResolverInterface;

class RouterResolver implements HandlerResolverInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolve($handler)
    {
        if(is_array($handler) and is_string($handler[0])) {
            $handler[0] = $this->container->make($handler[0]);
        }

        return $handler;
    }
}