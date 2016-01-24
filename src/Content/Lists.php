<?php namespace RobinCms\Core\Content;

class Lists
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
     * @param Content $loader    Content instance
     */
    public function __construct($locale, Content $content)
    {
        $this->locale  = $locale;
        $this->content = $content;
    }


    /**
     * Get all lists
     * 
     * @return array
     */
    public function getLists()
    {
        return $this->content->meta('lists');
    }


    /**
     * Get a list details
     * 
     * @return array
     */
    public function getList($key)
    {
        $key = strtolower($key);
        return $this->content->meta('lists', $key);
    }


    /**
     * Get list of fields for a list
     * 
     * @return array
     */
    public function getListFields($key)
    {
        $key = strtolower($key);
        return $this->content->meta('lists', $key . '.fields', []);
    }


    /**
     * Check if a list exists
     *
     * @param  string   $key
     * @return boolean
     */
    public function hasList($key)
    {
        $key = strtolower($key);
        return !is_null($this->content->meta('lists', $key));
    }


    /**
     * Check if a list has a specific field
     *
     * @param  string   $listKey
     * @param  string   $fieldKey
     * @return boolean
     */
    public function hasField($listKey, $fieldKey)
    {
        $listKey = strtolower($listKey);
        $key     = $listKey . '.fields.' . $fieldKey;
        return is_array($this->content->meta('lists', $key));
    }


    /**
     * Get list items
     * 
     * @param  string $key  List key
     * @return mixed
     */
    public function getItems($key)
    {
        return $this->content->content($this->locale, 'lists.' . $key, []);
    }


    /**
     * Get a stored content value
     *
     * @param  string $listKey
     * @param  string $key
     * @param  mxied  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->content->content($this->locale, 'lists.' . $key);
    }


    /**
     * Check if a list item exists
     *
     * @param  string $listKey
     * @param  string $itemKey
     * @return boolean
     */
    public function hasItem($listKey, $itemKey)
    {
        $key = 'lists.' . $listKey . '.' . $itemKey;
        return $this->content->hasContent($this->locale, $key);
    }


    /**
     * Update list item
     * 
     * @param  string $listKey
     * @param  string $itemKey
     * @param  array  $data
     * @return boolean
     */
    public function updateListItem($listKey, $itemKey, array $data)
    {
        $key = 'lists.' . $listKey . '.' . $itemKey;
        return $this->content->updateContent($this->locale, $key, $data);
    }

    /**
     * Delete list item
     * 
     * @param  string $listKey
     * @param  string $itemKey
     * @return boolean
     */
    public function deleteListItem($listKey, $itemKey)
    {
        if (!$this->hasItem($listKey, $itemKey)) {
            return false;
        }

        $key = 'lists.' . $listKey . '.' . $itemKey;
        return $this->content->unsetContent($this->locale, $key);
    }


    /**
     * Update list items order
     * 
     * @param  string $listKey
     * @param  array  $data
     * @return boolean
     */
    public function updateListItemsOrder($listKey, array $order)
    {
        if (!$this->hasList($listKey) || !is_array($order)) {
            return false;
        }


        $newList = [];
        foreach($order as $key) {
            if (!$this->hasItem($listKey, $key)) {
                continue;
            }

            $newList[$key] = $this->get($listKey . '.' . $key);
        }

        $key = 'lists.' . $listKey;

        return $this->content->updateContent($this->locale, $key, $newList);
    }

}
