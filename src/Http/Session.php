<?php namespace RobinCms\Core\Http;

class Session
{

    /**
     * Available flash values
     * @var array
     */
    protected $flash = [];


    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();

            // Let's generate a new cool ID instead of PHPSESSID
            // if this is the initialization of the session
            if (!$this->has('session_timestamp')) {
                session_regenerate_id(true);
                $this->set('session_timestamp', time());
            }
        }

        // Cash the flash data and remove it from the session
        $this->flash = $this->get('__FLASH__', []);
        $this->forget('__FLASH__');
    }


    /**
     * Get a session value
     * 
     * @param  string   $key
     * @param  string   $default    Returned if key doesn't exist
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }


    /**
     * Set a session value
     * 
     * @param string    $key
     * @param mixed     $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }


    /**
     * Check if a session key exists
     * 
     * @param  string   $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }


    /**
     * Delete a session key/value
     * 
     * @param  string   $key
     */
    public function forget($key)
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }


    /**
     * Set a session flash value
     * 
     * @param string    $key
     * @param mixed     $value
     */
    public function setFlash($key, $value)
    {
        $currentFlash = $this->get('__FLASH__', []);
        $currentFlash[$key] = $value;
        $this->set('__FLASH__', $currentFlash);
    }


    /**
     * Get a session flash value
     * 
     * @param  string   $key
     * @param  mixed    $default
     * @return mixed
     */
    public function getFlash($key, $default = null)
    {
        return array_key_exists($key, $this->flash)
            ? $this->flash[$key]
            : $default;
    }


    /**
     * Destroy all session keys/values, remove the session cookie 
     * and regenerate the session id
     */
    public function destroy()
    {
        // Clear all data in the current session
        $_SESSION = [];

        // Delete the session cookie, if it exixts
        if (isset($_COOKIE[session_name()])) { 

            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                1, 
                $params['path'], 
                $params['domain'], 
                $params['secure'], 
                isset($params['httponly'])
            );

        }

        // Destroy the session container
        session_destroy();
    }

}