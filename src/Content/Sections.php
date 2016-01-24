<?php namespace RobinCms\Core\Content;

class Sections
{
    /**
     * Content cache
     * @var Content
     */
    protected $content;
    
    /**
     * Current locale
     * @var string
     */
    protected $locale;


    /**
     * Create new instance of the Content object
     * 
     * @param string  $locale    Current locale slug
     * @param Content $content   Content instance
     */
    public function __construct($locale, Content $content)
    {
        $this->locale  = $locale;
        $this->content = $content;
    }


    /**
     * Get a stored content value
     * 
     * @param  string $key     {section_key}.{field_key}
     * @param  mxied  $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        if (strpos($key, ".") === false) {
            return $fallback;
        }

        return $this->content->content($this->locale, 'sections.' . $key, $fallback);
    }


    /**
     * Update content
     * 
     * @param  string $sectionKey
     * @param  array  $data
     * @return boolean
     */
    public function updateContent($key, array $data)
    {   
        return $this->content->updateContent($this->locale, 'sections.' . $key, $data);
        
    }


    /**
     * Override content for the current request only
     * 
     * @param  string $sectionKey
     * @param  array  $data
     */
    public function overrideContent($key, array $data)
    {
        if (empty($key) || empty($data) || !is_array($data)) {
            return;
        }

        $this->content->overrideContent($this->locale, 'sections.' . $key, $data);
    }


    /**
     * Get list of all sections
     * 
     * @return array
     */
    public function getSections()
    {
        return $this->content->meta('sections', null, []);
    }


    /**
     * Get a section details
     * 
     * @return array
     */
    public function getSection($key)
    {
        $key = strtolower($key);
        return $this->content->meta('sections', $key, []);
    }


    /**
     * Get list of fields for a section
     * 
     * @return array
     */
    public function getSectionFields($key)
    {
        $key = strtolower($key);
        $key = $key . '.fields';
        return $this->content->meta('sections', $key, []);
    }


    /**
     * Check if a section exists
     *
     * @param  string   $key
     * @return boolean
     */
    public function hasSection($key)
    {
        $key = strtolower($key);
        return !is_null($this->content->meta('sections', $key));
    }


    /**
     * Check if a section has a specific field
     *
     * @param  string   $sectionKey
     * @param  string   $fieldKey
     * @return boolean
     */
    public function hasField($sectionKey, $fieldKey)
    {
        $sectionKey = strtolower($sectionKey);
        $key        = $sectionKey . '.fields.' . $fieldKey;
        return is_array($this->content->meta('sections', $key));
    }

}
