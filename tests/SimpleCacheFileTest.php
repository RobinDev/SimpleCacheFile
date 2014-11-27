<?php
namespace rOpenDev\Cache\Test;

use rOpenDev\Cache\SimpleCacheFile as fCache;

class SimpleCacheFileTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test caching data with file prefixed
     */
    function testCachingWithPrefix()
    {
        $folder = './cache';
        $prefix = 'newprefix_';
        $key = 'test-cache';
        $fCache = new fCache($folder, $prefix);
        $fCache->getElseCreate($key, 3600, [$this, 'anArray']);

        $this->assertTrue(strpos($fCache->getCacheFilePath($key), $folder.'/'.$prefix) === 0);
    }

    /**
     * Test caching a file
     */
    public function testCaching()
    {
        $key = 'test-cache';

        $fCache = fCache::instance('./cache', 'tmp');
        $data = $fCache->getElseCreate($key, 3600, [$this, 'anArray']);
        $this->assertTrue(file_exists($fCache->getCacheFilePath($key)));

        $this->assertTrue(empty(array_diff($this->anArray(), $data)));

        sleep(2);
        $this->assertTrue(!$fCache->isCacheValid($key, 2));

        $fCache->getMaintener()->deleteCacheFilesByPrefix();
    }

    /**
     * Test deleting file by prefix
     */
    public function testDeletingFilesByPrefix()
    {
        $fCache = fCache::instance('./cache', 'prefix_');

        for ($i=1;$i<=10;++$i) {
            $key = 'myfilecache'.$i;
            $data = $fCache->getElseCreate($key, 3600, [$this, 'anArray']);
        }

        $this->assertTrue($fCache->getMaintener()->deleteCacheFilesByPrefix() == $i-1);
    }

    public function anArray()
    {
        return ['tagada' => 'tsoin', 'tsoin'];
    }

    public function testCacheAlwaysValid()
    {
        $key = 'my-cache';
        $data = 'Youhouhouhouhouhou ';

        $fCache = fCache::instance('./cache', 'always');
        $fCache->set($key, $data);
        $dataFromCache = $fCache->get($key, 0);

        $this->assertTrue($data == $dataFromCache);
    }
}
