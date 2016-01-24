<?php namespace RobinCms\Core\Content;

use RobinCms\Core\Exceptions\DataAccessException;

class Content
{
    protected $loader;
    protected $meta;
    protected $content = [];
    protected $settings;
    protected $user;
    protected $locales;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;

        $this->meta = [
            'sections' => $this->loader->getMeta('sections'),
            'lists'    => $this->loader->getMeta('lists'),
            'settings' => $this->loader->getMeta('settings'),
        ];

        $this->user    = $this->loader->getUser();
        $this->locales = $this->loader->getLocales();
    }


    /**
     * Get a meta value for sections or lists
     * 
     * @param  string  $type     Type of meta (sections/lists)
     * @param  string  $key      If null, return all for the type
     * @param  mixed   $fallback Returned if key not found
     * @return mixed
     */
    public function meta($type, $key = null, $fallback = null)
    {
        if (!array_key_exists($type, $this->meta)) {
            return $fallback;
        }

        if (is_null($key)) {
            return $this->meta[$type];
        }

        return arr_val($this->meta[$type], $key, $fallback);
    }


    /**
     * Get locales
     * 
     * @return array
     */
    public function locales($key = null)
    {
        return is_null($key)
            ? $this->locales
            : arr_val($this->locales, $key);
    }


    /**
     * Get user
     * 
     * @return array
     */
    public function user()
    {
        return $this->user;
    }


    /**
     * Update user
     *
     * @param  array  $data
     * @return boolean
     */
    public function updateUser($data)
    {
        $this->user = array_merge($this->user, $data);

        try {
            $saved = $this->loader->saveUser($this->user);
        } catch(DataAccessException $e) {
            return $e->getMessage();
        }
        
        return $saved === true? true : "User not saved. Unknown error occurred";
    }


    /**
     * Get a content value
     * 
     * @param  string $locale
     * @param  string $key
     * @param  mixed $fallback
     * @return mixed
     */
    public function content($locale, $key, $fallback = null)
    {
        $this->loadContent($locale);
        return arr_val($this->content[$locale], $key, $fallback);
    }


    /**
     * Check if a content item exists
     * 
     * @param  string $locale
     * @param  string $key
     * @return boolean
     */
    public function hasContent($locale, $key)
    {
        $this->loadContent($locale);
        return !is_null(arr_val($this->content[$locale], $key));
    }


    /**
     * Update a content value
     * 
     * @param  string $locale
     * @param  string $key    Key to update (dot notation)
     * @param  mixed  $data
     * @return boolean
     */
    public function updateContent($locale, $key, $data)
    {
        $this->loadContent($locale);
        arr_val_set($this->content[$locale], $key, $data);

        try {
            $saved = $this->loader->saveContent($locale, $this->content[$locale]);    
        } catch(DataAccessException $e) {
            return $e->getMessage();
        }
        
        return $saved === true? true : "Content not saved. Unknown error occurred";


    }


    /**
     * Override section content for the current request only
     * 
     * @param  string $locale
     * @param  string $key    Key to override (dot notation)
     * @param  mixed  $data
     * @return boolean
     */
    public function overrideContent($locale, $key, $data)
    {
        $this->loadContent($locale);
        arr_val_set($this->content[$locale], $key, $data);
    }


    /**
     * Unset a content key
     * 
     * @param  string $locale
     * @param  string $key
     * @return void
     */
    public function unsetContent($locale, $key)
    {
        $this->loadContent($locale);
        if (!$this->hasContent($locale, $key)) {
            return false;
        }

        arr_val_unset($this->content[$locale], $key);
        
        if ($this->hasContent($locale, $key)) {
            return false;
        }

        return $this->loader->saveContent($locale, $this->content[$locale]);
    }


    /**
     * Load locale based content
     * 
     * @param  string $locale
     * @return void
     */
    protected function loadContent($locale)
    {
        if (!array_key_exists($locale, $this->content)) {
            $this->content[$locale] = $this->loader->getContent($locale);
        }
    }

    /**
     * Get a settings value
     * 
     * @param  string $key
     * @param  mixed  $fallback
     * @return mixed
     */
    public function settings($key, $fallback = null)
    {
        $this->loadSettings();
        return arr_val($this->settings, $key, $fallback);
    }


    /**
     * Check if a settings item exists
     * 
     * @param  string $key
     * @return boolean
     */
    public function hasSetting($key)
    {
        $this->loadSettings();
        return !is_null(arr_val($this->settings, $key));
    }


    /**
     * Update a settings values
     * 
     * @param  string $key    Key to update (dot notation)
     * @param  mixed  $data
     * @return boolean
     */
    public function updateSettings($key, $data)
    {
        $this->loadSettings();
        arr_val_set($this->settings, $key, $data);

        try {
            $saved = $this->loader->saveSettings($this->settings);    
        } catch(DataAccessException $e) {
            return $e->getMessage();
        }
        
        return $saved === true? true : "Settings not saved. Unknown error occurred";
    }


    /**
     * Unset a settings key
     * 
     * @param  string $key
     * @return void
     */
    public function unsetSettings($key)
    {
        $this->loadSettings();
        if (!$this->hasSetting($key)) {
            return false;
        }

        arr_val_unset($this->settings, $key);
        
        if ($this->hasSetting($key)) {
            return false;
        }

        return $this->loader->saveSettings($this->settings);
    }


    /**
     * Load settings
     * 
     * @return void
     */
    protected function loadSettings()
    {
        if (is_null($this->settings)) {
            $this->settings = $this->loader->getSettings();
        }
    }

}
