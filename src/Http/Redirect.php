<?php namespace RobinCms\Core\Http;

class Redirect
{
    protected $url;
    protected $httpCode;

    public function __construct($url = null, $httpCode = 302)
    {
        $this->url      = $url;
        $this->httpCode = $httpCode;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    public function send()
    {
        header('location: ' . $this->url, true, $this->httpCode);
        exit;
    }
}