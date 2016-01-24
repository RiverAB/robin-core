<?php namespace RobinCms\Core\Content;

class Settings
{
    /**
     * Content cache
     * @var Content
     */
    protected $content;
    

    /**
     * Create new instance of the Settings object
     * 
     * @param Content $content   Content instance
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }


    /**
     * Get a stored settings value
     * 
     * @param  string $key     {settings_key}.{field_key}
     * @param  mxied  $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        if (strpos($key, ".") === false) {
            return $fallback;
        }

        return $this->content->settings($key, $fallback);
    }


    /**
     * Update settings
     * 
     * @param  string $groupKey
     * @param  array  $data
     * @return boolean
     */
    public function updateSettings($key, array $data)
    {   
        return $this->content->updateSettings($key, $data);
    }


    /**
     * Get list of all setting groups
     * 
     * @return array
     */
    public function getGroups()
    {
        return $this->content->meta('settings', null, []);
    }


    /**
     * Get details for a settings group
     * 
     * @return array
     */
    public function getGroup($key)
    {
        $key = strtolower($key);
        return $this->content->meta('settings', $key, []);
    }


    /**
     * Get list of fields for a settings group
     * 
     * @return array
     */
    public function getGroupFields($key)
    {
        $key = strtolower($key);
        $key = $key . '.fields';
        return $this->content->meta('settings', $key, []);
    }


    /**
     * Check if a settings item exist
     *
     * @param  string   $key
     * @return boolean
     */
    public function keyExists($key)
    {
        $key = strtolower($key);
        return !is_null($this->content->meta('settings', $key));
    }


    /**
     * Check if a group has a specific field
     *
     * @param  string   $groupKey
     * @param  string   $fieldKey
     * @return boolean
     */
    public function hasField($groupKey, $fieldKey)
    {
        $groupKey   = strtolower($groupKey);
        $key        = $groupKey . '.fields.' . $fieldKey;
        return is_array($this->content->meta('settings', $key));
    }

}
