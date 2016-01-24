<?php namespace RobinCms\Core\Content;

class Locales
{
    /**
     * Current locale
     * @var string
     */
    protected $current = 'en';
   
    /**
     * Content instance
     * @var Content
     */
    protected $content;


    /**
     * Create new instance
     * 
     * @param Content $content    Content instance
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }


    /**
     * Set the current locale
     * @param string $slug  Locale slug
     */
    public function setLocale($slug)
    {
        if ($this->exists($slug)) {
            $this->current = $slug;
        }
    }


    /**
     * Get list of available locales
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->content->locales();
    }


    /**
     * Get the current locale
     * 
     * @return string $slug
     */
    public function getCurrent()
    {
        return $this->current;
    }


    /**
     * Get the name for a locale
     *
     * @param  string       $slug   If null, current locale
     * @return string|null  $name
     */
    public function getName($slug = null)
    {
        $slug = $slug?: $this->current;

        return $this->content->locales($slug . '.name');
    }


    /**
     * Check if a slug exists
     *
     * @param  string   $slug
     * @return boolean
     */
    public function exists($slug)
    {
        return is_array($this->content->locales($slug));
    }

}