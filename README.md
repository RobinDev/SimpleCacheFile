# Make it easy to get and store data in a cache file

[![Quality Score](https://img.shields.io/scrutinizer/g/RobinDev/SimpleCacheFile.svg?style=flat-square)](https://scrutinizer-ci.com/g/RobinDev/SimpleCacheFile)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1941f35e-53cb-4801-896d-30c2838c3493/mini.png)](https://insight.sensiolabs.com/projects/1941f35e-53cb-4801-896d-30c2838c3493)
[![Build Status](https://travis-ci.org/RobinDev/SimpleCacheFile.svg)](https://travis-ci.org/RobinDev/SimpleCacheFile)

SimpleCacheFile is :
* a PHP librairy wich permit to easily manage cached data (integer, string, array) in files,
* PSR compliant (1 & 2 Coding Style, 4 Autoloading),
* Easy to install with composer,
* Intuitive and documented (inline and in this Readme)
* Light

##Table of contents
* [Usage](#usage)
* [Installation](#installation)
    * [Packagist](https://packagist.org/packages/ropendev/cache)
* [Requirements](#requirements)
* [Contributors](#contributors)
* [Licence](#licence)

##Usage

```php
use rOpenDev\Cache\SimpleCacheFile as fCache;

$key = 'data-2032'; // string to identify the cached data
$maxAge = 3600;     // 1 hour
/** Create a cache file **/
$data = fCache::instance()->setCacheFolder('/path/to/my/cacheFolder')
                          ->getElseCreate($key , $maxAge, function() {
                                      return 'My first data in cache';
                            });

$folder = './cache';
$prefix = 'tmp_';
fCache::instance($folder, $prefix)->set($key, 'My string to set in a cache || But it could be an array or an object...');
fCache::instance($folder, $prefix)->get($key, $maxAge);
fCache::instance($folder, $prefix)->get($key, 0);  // Always valid. No expiration

/** Delete all cache files with the prefix `prfixForCacheFiles_` **/
fCache::instance('./my-cache-folder', 'prfixForCacheFiles_')->getMaintener()->deleteCacheFilesByPrefix();
```

## Installation

```bash
composer require ropendev/cache
```

## Requirements

HHVM or PHP 5.5.x minimum is required (See `composer.json` file for details).

## Contributing

See `CONTRIBUTING.md` file.

## Contributors

* Original author [Robin (Seo Consultant in Marseille)](http://www.robin-d.fr)
* ...

## License

MIT (see the LICENSE file for details)
