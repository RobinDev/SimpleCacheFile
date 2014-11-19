<?php
namespace rOpenDev\Cache;

/**
 * Simple Cache File Librairy
 * Make it easy to set data in cache
 * PSR-2 Coding Style, PSR-4 Autoloading
 *
 * @author     Robin <contact@robin-d.fr> http://www.robin-d.fr/
 * @link       https://github.com/RobinDev/SimpleCacheFile
 * @since      File available since Release 2014.11.18
 */
class SimpleCacheFile
{
    /**
     * Contain class instances
     * @var array
     */
    private static $instance = [];

    /**
     * Prefix your cache files
     * @var string
     */
    protected $prefix;

    /**
     * Folder where your cache files are stored
     * @var string
     */
    protected $folder;

    /**
     * Constructor
     *
     * @param string $folder Folder containing cache files. Default /tmp
     * @param string $prefix Prefix for the cache files. Default empty.
     */
    public function __construct($folder = '/tmp', $prefix = '')
    {
        $this->setCacheFolder($folder);
        $this->setPrefix($prefix);
    }

    /**
     * Instanciator
     *
     * @param string $folder Folder containing cache files. Default /tmp
     * @param string $prefix Prefix for the cache files. Default empty.
     *
     * return self
     */
    public static function instance($folder = '/tmp', $prefix = '')
    {
        $class = get_called_class();
        $instanceKey = '_'.$class.$folder.$prefix;

        if (isset(self::$instance[$instanceKey])) {
            return self::$instance[$instanceKey];
        }

        self::$instance[$instanceKey] = new  $class($folder, $prefix);

        return self::$instance[$instanceKey];
    }

    /**
     * Chainable prefix setter
     *
     * @param string $prefix
     *
     * @return self
     */
    protected function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the cache folder (chainable folder setter)
     *
     * @param string $folder
     *
     * @return self
     */
    protected function setCacheFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get cache file path
     *
     * @param string $key
     *
     * @return string
     */
    public function getCacheFilePath($key)
    {
        return $this->folder.'/'.$this->prefix.sha1($key);
    }

    /**
     * Return your cache data else create and return data
     *
     * @param string $key         String wich permit to identify your cache file
     * @param int    $maxAge      Time the cache is valid. Default 86400 (1 day).
     * @param mixed  $dataToCache It can be a function wich generate data to cache or a variable
     *
     * @return mixed Response from your $userFunction (or it cache)
     */
    public function getElseCreate($key, $maxAge = '86400', $dataToCache)
    {
        $cacheFile = $this->getCacheFilePath($key);

        if ($this->isCacheFileValid($cacheFile, $maxAge)) {
            return unserialize(file_get_contents($cacheFile));
        }

        $data = is_callable($dataToCache) ? call_user_func($dataToCache) : $dataToCache;

        file_put_contents($cacheFile, serialize($data));

        return $data;
    }

    /**
     * Cache is valid ?
     *
     * @param string $key    String wich permit to identify your cache file
     * @param int    $maxAge Time the cache is valid. Default 86400 (1 day).
     *
     * @return bool
     */
    public function isCacheValid($key, $maxAge)
    {
        $cacheFile = $this->getCacheFilePath($key);

        return $this->isCacheFileValid($cacheFile, $maxAge);
    }

    /**
     * Cache File is valid ?
     *
     * @param string $cacheFile Cache file path
     * @param int    $maxAge    Time the cache is valid. Default 86400 (1 day).
     *
     * @return bool
     */
    protected function isCacheFileValid($cacheFile, $maxAge)
    {
        $expire = time() - $maxAge;

        return !file_exists($cacheFile) || filemtime($cacheFile) <= $expire ? false : true;
    }

    /**
     * Delete all cache files with the $prefix
     *
     * @throws \Exception If the prefix is empty
     *
     * @return \rOpenDev\cache\Maintener
     */
    public function getMaintener()
    {
        return new Maintener($this->folder, $this->prefix);
    }
}
