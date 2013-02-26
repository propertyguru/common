<?php

namespace VGMdb\Provider;

use VGMdb\Component\HttpFoundation\Request;
use VGMdb\Component\HttpKernel\EventListener\LocaleMappingListener;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Loads locale specific configuration upon boot.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */
class LocaleServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['locale.mapping'] = array();
        $app['locale.formats'] = array();

        $app['locale.mapping_listener'] = $app->share(function ($app) {
            return new LocaleMappingListener($app['request_context'], $app['locale.mapping']);
        });

        $app['locale.formatter.datetime._proto'] = $app->protect(function ($pattern = null) use ($app) {
            $localeWithKeywords = $app['request_context']->getLocaleWithKeywords();
            $isTraditional = (Boolean) strpos($localeWithKeywords, 'calendar');

            return new \IntlDateFormatter(
                $localeWithKeywords,
                \IntlDateFormatter::LONG,
                \IntlDateFormatter::NONE,
                date_default_timezone_get(),
                $isTraditional ? \IntlDateFormatter::TRADITIONAL : \IntlDateFormatter::GREGORIAN,
                $pattern
            );
        });

        $app['locale.formatter.date'] = $app->share(function ($app) {
            $dateFormat = null;
            if (isset($app['locale.formats']['date']) && isset($app['locale.formats']['date'][$app['locale']])) {
                $dateFormat = $app['locale.formats']['date'][$app['locale']];
            }

            return $app['locale.formatter.datetime._proto']($dateFormat);
        });

        $app['locale.formatter.year'] = $app->share(function ($app) {
            return $app['locale.formatter.datetime._proto']('yyyy');
        });

        $app['locale.formatter.number'] = $app->share(function ($app) {
            return new \NumberFormatter($app['request_context']->getLocale(), \NumberFormatter::DECIMAL);
        });

        $app['locale.formatter.currency'] = $app->share(function ($app) {
            return new \NumberFormatter($app['request_context']->getLocale(), \NumberFormatter::CURRENCY);
        });
    }

    public function boot(Application $app)
    {
        $app['dispatcher']->addSubscriber($app['locale.mapping_listener']);
    }
}
