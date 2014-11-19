<?php
namespace rOpenDev\Cache;

use \Exception;

/**
 * Simple Cache Files Maintener
 * Make it easy to delete cache files
 * PSR-2 Coding Style, PSR-4 Autoloading
 *
 * @author     Robin <contact@robin-d.fr> http://www.robin-d.fr/
 * @link       https://github.com/RobinDev/SimpleCacheFile
 * @since      File available since Release 2014.11.18
 */
class Maintener extends SimpleCacheFile
{
    /**
     * Delete all cache files with the $prefix
     *
     * @return int Number of deleted files
     *
     * @throw \Exception If the prefix is empty
     */
    public function deleteCacheFilesByPrefix()
    {
        if (empty($this->prefix)) {
            throw new Exception('SimpleCacheFile::Prefix is empty : Can\'t delete cache files by prefix.');
        }

        $deletedFilesCounter = 0;
        $files = glob($this->folder.'/'.$this->prefix.'*', GLOB_NOSORT);
        foreach ($files as $file) {
            unlink($file);
            ++$deletedFilesCounter;
        }

        return $deletedFilesCounter;
    }
}
