<?php namespace RobinCms\Core\Security;

use RobinCms\Core\Http\Session;

class Csrf
{

    /**
     * Session instance
     * @var RobinCMS\Core\Session
     */
    protected $session;

    /**
     * The default token name if user omit the name from the requests
     * @var string
     */
    protected $defaultName = 'default';

    /**
     * Collection that holds all tokens from the session
     * @var array
     */
    protected $tokens      = [];

    /**
     * Key name for the session with the token collection
     * @var string
     */
    protected $key         = 'csrf_tokens';


    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->tokens  = $session->get($this->key, []);
    }


    /**
     * Get a CSRF token
     * 
     * @param  string   $name   If omitted, the default name will be used
     * @return string
     */
    public function getToken($name = null)
    {
        $hName = $this->hashName($name);
        $token = isset($this->tokens[$hName]) ? $this->tokens[$hName]: null;
        
        return $token?: $this->regenerateToken($name);
    }


    /**
     * Get html markup for a hidden input CSRF field
     * 
     * @param  string   $name   If omitted, the default name will be used
     * @return string   Html markup
     */
    public function getTokenField($name = null)
    {
        $token = $this->getToken($name);
        return '<input type="hidden" name="csrftoken" value="' . $token . '" />';
    }


    /**
     * Validate a token
     * 
     * @param  string   $userToken  The token to validate
     * @param  string   $name       If omitted, the default name will be used
     * @return bool
     */
    public function validateToken($userToken, $name = null)
    {
        $token = $this->getToken($name);
        return !is_null($userToken) && $token === $userToken;
    }

    
    /**
     * Regenerate a CSRF token
     * 
     * @param  string   $name   If omitted, the default token will be regenerated
     */
    public function regenerateToken($name = null)
    {
        $name                 = $this->hashName($name);
        $this->tokens[$name]  = base64_encode(openssl_random_pseudo_bytes(64));
        $this->session->set($this->key,  $this->tokens);

        return $this->tokens[$name];
    }


    /**
     * Reset/delete all tokens
     */
    public function resetAll()
    {
        $this->tokens = [];
        $this->session->forget($this->key);
    }


    /**
     * Normalize and MD5 hash the name (this is not for security reasons
     * but rather to remove weird characters in the name)
     * 
     * @param  string   $name   If omitted, the default token will be regenerated
     * @return string
     */
    protected function hashName($name = null)
    {
        $name = strtolower($name);
        return md5($name?: $this->defaultName);
    }
}