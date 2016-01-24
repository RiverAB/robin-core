<?php namespace RobinCms\Core;

class Module
{
    protected $name;
    protected $key;
    protected $showInMenu = false;
    protected $menuLabel;
    protected $menuRoute;

    public function __construct($name, $key)
    {
        $this->name = $name;
        $this->key  = $key;
    }


    /**
     * Set if the module should be shown in the admin menu or not
     *
     * @param  boolean $show
     * @return Self
     */
    public function setShowInMenu($show)
    {
        $this->showInMenu = $show;
        return $this;
    }


    /**
     * Set the menu label for the admin menu
     * 
     * @param  string $label
     * @return Self
     */
    public function setMenuLabel($label)
    {
        $this->menuLabel = $label;
        return $this;
    }


    /**
     * Set the route name for the admin menu
     * 
     * @param  string $url
     * @return Self
     */
    public function setMenuRoute($route)
    {
        $this->menuRoute = $route;
        return $this;
    }


    /**
     * Get the module name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->showInMenu;
    }


    /**
     * Get the module key
     * 
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * Check if the module should be shown in the admin menu or not
     * 
     * @return boolean
     */
    public function showInMenu()
    {
        return $this->showInMenu;
    }


    /**
     * Get the menu label
     * 
     * @return string|null
     */
    public function getMenuLabel()
    {
        return $this->menuLabel;
    }


    /**
     * Get the menu route name
     * 
     * @return string
     */
    public function getMenuRoute()
    {
        return $this->menuRoute;
    }

}