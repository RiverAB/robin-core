<?php namespace RobinCms\Core;

abstract class Controller
{
    protected static $app;

    public static function setApp(App $app)
    {
        if (is_null(static::$app)) {
            static::$app = $app;
        }
    }

    public function __get($param)
    {
        return static::$app->{$param};
    }

    public function redirect($url, $httpCode = 302)
    {
        return static::$app->redirect($url, $httpCode);
    }

    public function isDebug()
    {
        return static::$app->isDebug();
    }

    public function jsonResponse($data = null, $success = true, array $errors = array())
    {
        return static::$app->jsonResponse($data, $success, $errors);
    }
}