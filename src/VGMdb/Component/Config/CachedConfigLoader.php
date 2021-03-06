<?php

namespace VGMdb\Component\Config;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

/**
 * Writes and loads configuration in cache.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */
class CachedConfigLoader extends ConfigLoader implements WarmableInterface
{
    public function getConfig()
    {
        $files = $this->options['files'];
        $directories = (array) $this->options['base_dirs'];

        $cacheClass = implode('', array_map('ucfirst', explode('-', $this->options['cache_class'])));
        $cacheFile = $this->options['cache_dir'] . '/' . $cacheClass . '.php';
        $cache = new ConfigCache($cacheFile, $this->options['debug']);

        if (!$cache->isFresh()) {
            $conf = array();
            foreach ($directories as $directory) {
                foreach (glob($directory . '/' . $files) as $filename) {
                    $conf = array_merge($conf, $this->loadConfig($filename));
                }
            }

            $configs = $resources = array();
            foreach ($conf as $path => $config) {
                $resources[] = new FileResource($path);
                $configs = array_replace_recursive($configs, $config);
            }

            $cache->write(
                '<?php' . PHP_EOL . '$configs = ' . var_export($configs, true) . ';',
                $resources
            );
        }

        require $cache;

        return $configs;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        $this->setOption('cache_dir', $cacheDir);

        $this->getConfig();
    }
}
