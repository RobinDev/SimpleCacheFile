<?php
namespace rOpenDev\Cache\Test;

use rOpenDev\Cache\SimpleCacheFile as fCache;

class SimpleCacheFileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test caching a file
     */
    public function testCaching()
    {
        $key = 'test-cache';

        $fCache = fCache::instance('cache');
        $data = $fCache->getElseCreate($key, 3600, [$this, 'anArray']);
        $this->assertTrue(file_exists($fCache->getCacheFilePath($key)));

        $this->assertTrue(empty(array_diff($this->anArray(), $data)));

        sleep(1);
        $this->assertTrue(!$fCache->isCacheValid($key, 2));
    }

    public function anArray()
    {
        return ['tagada' => 'tsoin', 'tsoin'];
    }
}
