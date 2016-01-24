<?php namespace RobinCms\Core;

class Config
{
    protected $files = [];
    protected $conf  = [];


    /**
     * Create a new instance
     * 
     * @param  array    $files  Array of absolut paths to files that should be loaded upon instantiation
     */
    public function __construct(array $files = array())
    {
        if ($files) {
            $this->load($files);
        }
    }


    /**
     * Get a value from the loaded config files
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @param  mixed    $default    Returned if key is not found
     * @return mixed    Value|$default
     */
    public function get($key = null, $default = null)
    {
        if (!$key) {
            return $default;
        }
        
        // If we have a direct match, return it.
        // This makes it possible to have keys containing dots
        if (array_key_exists($key, $this->conf)) {
            return $this->conf[$key];
        }

        $conf  =& $this->conf;

        foreach(explode('.', $key) as $segment) {
            
            if (!array_key_exists($segment, $conf)) {
                return $default;
            }

            $conf =& $conf[$segment];
        
        }

        return $conf;
    }


    /**
     * Set a new or overwrite an existing value
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @param  mixed    $value      Value to set
     * @return mixed    $value
     */
    public function set($key, $value)
    {
        $conf =& $this->conf;

        $segments = explode('.', $key);

        while (count($segments) > 1)
        {
            $segment = array_shift($segments);
            if ( ! isset($conf[$segment]) || ! is_array($conf[$segment])) {
                $conf[$segment] = array();
            }
            $conf =& $conf[$segment];
        }

        return $conf[array_shift($segments)] = $value;
    }


    /**
     * Check if a key exists in the loaded config files
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @return boolean
     */
    public function exists($key)
    {
        $conf  =& $this->conf;

        foreach(explode('.', $key) as $segment) {
            
            if (!array_key_exists($segment, $conf)) {
                return false;
            }

            $conf =& $conf[$segment];
        
        }

        return true;
    }


    /**
     * Load one or more config files
     * 
     * @param  string|array  $files         Absolute paths to the config files
     * @param  boolean       $forceReload   If true, the file will be re-read if it already has been loaded
     * @return void
     */
    public function load($files, $forceReload = false)
    {
        if (!is_array($files)) {
            // Make it an array so we can use the same code
            $files = array($files);
        }

        foreach($files as $file) {

            if ((array_key_exists($file, $this->files) && !$forceReload) 
                || !is_file($file)) {
                // It's already loaded, or doesn't exist, so let's skip it
                continue;
            }

            $conf = include $file;

            if (is_array($conf)) {
                // We're only interested if it is an array
                $this->conf         = array_replace_recursive($this->conf, $conf);
                $this->files[$file] = true;
            }
        }
    }


    /**
     * Check if a config file has been loaded
     * 
     * @param  string   $file   Absolute path to the config file
     * @return boolean
     */
    public function isLoaded($file)
    {
        return array_key_exists($file, $this->files);
    }
}