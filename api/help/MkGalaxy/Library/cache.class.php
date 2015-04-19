<?php
class Library_cache
{
    protected $cache;
    public function __construct($opts=array())
    {
        $this->setOptions($opts);
    }

    public function getOptions()
    {
        return $this->cache;
    }

    public function setOptions($opts=array())
    {
      $lifetime = !empty($opts['lifetime']) ? $opts['lifetime'] : 3600;
      $cache_dir = !empty($opts['cache_dir']) ? $opts['cache_dir'] : SITE_DIR.'/cache/Zend_cache';
        $frontendOptions = array(
           'lifetime' => $lifetime, // cache lifetime of unlimited
           'automatic_serialization' => true
        );
         
        $backendOptions = array(
            'cache_dir' => $cache_dir // Directory where to put the cache files
        );
        // getting a Zend_Cache_Core object
        $this->cache = Zend_Cache::factory('Core',
                                     'File',
                                     $frontendOptions,
                                     $backendOptions);
        return $this->cache;
    }

    public function setCache($key, $value)
    {
        $this->cache->save($key, $value);
    }

    public function getCache($key)
    {
        $result = $this->cache->load($key);
        return $result;
    }
}