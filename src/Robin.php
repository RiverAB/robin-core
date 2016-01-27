<?php namespace RobinCms\Core;

class Robin
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    
    /**
     * Get a csrf token
     * 
     * @param  string $name Token name
     * @return string
     */
    public function csrf_token($name = null)
    {
        return $this->app->csrf->getToken($name);
    }

    
    /**
     * Get the uri to the current public theme folder
     * 
     * @param  string   $file
     * @return string
     */
    public function theme_path($file = null)
    {
        return $this->static_path('themes/' . $this->app->config->get('theme') . '/' . $file);
    }


    /**
     * Get the uri to the current public admin theme folder
     * 
     * @param  string   $file
     * @return string
     */
    public function admin_theme_path($file = null)
    {
        return $this->static_path('admin/' . $this->app->config->get('admin.theme') . '/' . $file);
    }


    /**
     * Get the uri to the static folder
     * 
     * @param  string   $file
     * @return string
     */
    public function static_path($file = null)
    {
        $file   = trim($file, '/');
        return rtrim('/static/' . $file);
    }


    /**
     * Get the URI for a named route
     * 
     * @param  string $name
     * @param  array  $params
     * @return string
     */
    public function route($name, array $params = array())
    {
        return $this->app->router->route($name, $params);
    }


    /**
     * Get list of sections
     * 
     * @return array
     */
    public function sections()
    {
        return $this->app->sections->getSections();
    }


    /**
     * Get a section content value
     * 
     * @param  string $key          Format: "section_key.field_key"
     * @param  mixed  $fallback     Returned if key doesn't exist
     * @return mixed
     */
    public function content($key, $fallback = null)
    {
        return $this->app->sections->get($key, $fallback);
    }


    /**
     * Get a list of settings groups
     * 
     * @return array
     */
    public function setting_groups()
    {
        return $this->app->settings->getGroups();
    }


    /**
     * Get a setting group value
     * 
     * @param  string $key          Format: "setting_group_key.field_key"
     * @param  mixed  $fallback     Returned if key doesn't exist
     * @return mixed
     */
    public function setting($key, $fallback = null)
    {
        return $this->app->settings->get($key, $fallback);
    }


    /**
     * Get a list of all lists
     * 
     * @return array
     */
    public function lists()
    {
        return $this->app->lists->getLists();
    }


    /**
     * Get list of items in a list
     * 
     * @param  string  $key     List key
     * @return array
     */
    public function list_items($key)
    {
        return $this->app->lists->getItems($key);
    }


    /**
     * Get a list item value
     *
     * @param  string  $key     Format: "list_key.item_key.field_key"
     * @return array
     */
    public function list_item_content($key, $fallback = null)
    {
        return $this->app->lists->get($key, $fallback);
    }


    /**
     * Get list of all locales
     * 
     * @return array
     */
    public function locales()
    {
        return $this->app->locales->getAll();
    }


    /**
     * Get list of all modules
     * 
     * @return array
     */
    public function modules()
    {
        return $this->app->getModules();
    }

}
