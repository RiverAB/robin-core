<?php namespace RobinCms\Core;

abstract class ServiceProvider
{
    protected $container;

    public function __construct(App $app)
    {
        $this->container = $app;
    }

    abstract public function register();

    public function boot() {}

    public function aliases(array $aliases)
    {
        foreach($aliases as $alias => $class) {
            $this->container->alias($class, $alias);
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->container, $method], $args);
    }

    public function __get($key)
    {
        if ($this->container->isAlias($key)) {
            return $this->container->{$key};
        }
    }

}