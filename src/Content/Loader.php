<?php namespace RobinCms\Core\Content;

use DirectoryIterator;
use RobinCms\Core\Exceptions\DataAccessException;

class Loader
{
    protected $storagePath;

    /**
     * Create new instance of the Loader class
     * 
     * @param string $storagePath Full base path for data storage
     */
    public function __construct($storagePath)
    {
        $this->storagePath = rtrim($storagePath, '/\\');
        $this->paths = [
            'content'   => $storagePath . '/content',
            'settings'  => $storagePath . '/content',
            'meta'      => $storagePath . '/meta',
            'user'      => $storagePath . '/content/user.json',
            'locales'   => $storagePath . '/content/locales.json',
        ];
    }


    /**
     * Get all meta data for a specific type
     * 
     * @param  string $type sections|settings|lists
     * @return array
     */
    public function getMeta($type)
    {
        return $this->load($this->paths['meta'] . '/' . $type . '.json');
    }


    /**
     * Get the user data
     * 
     * @return [type] [description]
     */
    public function getUser()
    {
        return $this->load($this->paths['user']);
    }


    /**
     * Save user data
     * 
     * @param  array $content User data
     * @return boolean
     */
    public function saveUser($content)
    {
        $saved = $this->save($this->paths['user'], $content);
        unset($content);
        return $saved;
    }


    /**
     * Get all available locales
     * 
     * @return array
     */
    public function getLocales()
    {
        return $this->load($this->paths['locales']);
    }


    /**
     * Get all content (sections and lists) for a specific locale
     * @param  string $locale Locale slug
     * @return array
     */
    public function getContent($locale)
    {
        return $this->load($this->paths['content'] . '/' . $locale . '.json');
    }


    /**
     * Save content (sections and lists) for a locale
     * 
     * @param  string $locale  Locale slug
     * @param  array  $content Content data
     * @return boolean
     */
    public function saveContent($locale, $content)
    {
        $saved = $this->save($this->paths['content'] . '/' . $locale . '.json', $content);
        unset($content);
        return $saved;
    }


    /**
     * Get settings
     * 
     * @return array
     */
    public function getSettings()
    {
        return $this->load($this->paths['settings'] . '/settings.json');
    }


    /**
     * Save settings
     * 
     * @param  array $content
     * @return boolean
     */
    public function saveSettings($content)
    {
        $saved = $this->save($this->paths['settings'] . '/settings.json', $content);
        unset($content);
        return $saved;
    }


    /**
     * Load a json file and return decoded data
     * 
     * @param  string $file
     * @return array
     */
    protected function load($file)
    {
        $content = [];
        
        if (is_file($file)) {
            $data    = json_decode(file_get_contents($file), true, 512);
            $content = is_array($data)? $data : [];
        }
    
        return $content;
    }


    /**
     * Save data as json encoded data
     * 
     * @param  string $file
     * @param  array  $content
     * @return boolean
     */
    protected function save($file, $content)
    {
        if (!is_writable(pathinfo($file, PATHINFO_DIRNAME))) {
            throw new DataAccessException("Not saved. The storage folder " . pathinfo($file, PATHINFO_DIRNAME) . " is not writable");
        }

        if (is_file($file) && !is_writable($file)) {
            throw new DataAccessException("Not saved. The storage file " . $file . " is not writable");
        }

        $saved = file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT)) !== 0;
        unset($content);
        return $saved;
    }

}