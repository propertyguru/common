<?php

namespace VGMdb\Provider;

use VGMdb\Component\Serializer\ArraySerializationVisitor;
use VGMdb\Component\Serializer\Construction\DoctrineObjectConstructor;
use VGMdb\Component\Serializer\Handler\DateHandler;
use Silex\Application;
use Silex\ServiceProviderInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\YamlSerializationVisitor;
//use JMS\Serializer\Construction\DoctrineObjectConstructor; // overridden to avoid ManagerRegistry
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use JMS\Serializer\Handler\ArrayCollectionHandler;
use JMS\Serializer\Handler\ConstraintViolationHandler;
//use JMS\Serializer\Handler\DateHandler; // overridden to add array format
use JMS\Serializer\Handler\FormErrorHandler;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * JMS Serializer integration for Silex.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */
class SerializerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        // default settings
        $app['serializer.naming_strategy.separator'] = '_';
        $app['serializer.naming_strategy.lower_case'] = true;
        $app['serializer.datetime_handler.default_format'] = \DateTime::ISO8601;
        $app['serializer.datetime_handler.default_timezone'] = 'UTC';
        $app['serializer.disable_external_entities'] = true;
        $app['serializer.serialize_null'] = false;
        $app['serializer.src_dir'] = '';
        $app['serializer.cache_dir'] = '';
        $app['serializer.config_dirs'] = array();
        $app['serializer.json_serialization_visitor.options'] = array();
        $app['serializer.xml_deserialization_visitor.doctype_whitelist'] = array();
        $app['serializer.version'] = '1.0';

        // handlers
        $app['serializer.datetime_handler'] = $app->share(function () use ($app) {
            $format = $app['serializer.datetime_handler.default_format'];
            $defaultTimezone = $app['serializer.datetime_handler.default_timezone'];

            return new DateHandler($format, $defaultTimezone);
        });

        $app['serializer.array_collection_handler'] = $app->share(function () use ($app) {
            return new ArrayCollectionHandler();
        });

        if (isset($app['translator'])) {
            $app['serializer.form_error_handler'] = $app->share(function () use ($app) {
                return new FormErrorHandler($app['translator']);
            });
        }

        if (class_exists('Symfony\\Component\\Validator\\ConstraintViolation')) {
            $app['serializer.constraint_violation_handler'] = $app->share(function () use ($app) {
                return new ConstraintViolationHandler();
            });
        }

        // naming strategies
        $app['serializer.camel_case_naming_strategy'] = $app->share(function () use ($app) {
            $separator = $app['serializer.naming_strategy.separator'];
            $lowerCase = $app['serializer.naming_strategy.lower_case'];

            return new CamelCaseNamingStrategy($separator, $lowerCase);
        });

        $app['serializer.naming_strategy'] = $app->share(function () use ($app) {
            return new SerializedNameAnnotationStrategy($app['serializer.camel_case_naming_strategy']);
        });

        // object constructors
        $app['serializer.object_constructor'] = $app->share(function () use ($app) {
            return new UnserializeObjectConstructor();
        });

        $app['serializer.doctrine_object_constructor'] = $app->share(function () use ($app) {
            return new DoctrineObjectConstructor($app['entity_manager'], $app['serializer.object_constructor']);
        });

        // visitors
        $app['serializer.array_serialization_visitor'] = $app->share(function () use ($app) {
            return new ArraySerializationVisitor($app['serializer.naming_strategy']);
        });

        $app['serializer.json_serialization_visitor'] = $app->share(function () use ($app) {
            $jsonSerializationVisitor = new JsonSerializationVisitor($app['serializer.naming_strategy']);
            $jsonSerializationVisitor->setOptions($app['serializer.json_serialization_visitor.options']);

            return $jsonSerializationVisitor;
        });

        $app['serializer.json_deserialization_visitor'] = $app->share(function () use ($app) {
            return new JsonDeserializationVisitor($app['serializer.naming_strategy']);
        });

        $app['serializer.xml_serialization_visitor'] = $app->share(function () use ($app) {
            return new XmlSerializationVisitor($app['serializer.naming_strategy']);
        });

        $app['serializer.xml_deserialization_visitor'] = $app->share(function () use ($app) {
            $xmlDeserializationVisitor = new XmlDeserializationVisitor($app['serializer.naming_strategy']);
            $xmlDeserializationVisitor->setDoctypeWhitelist($app['serializer.xml_deserialization_visitor.doctype_whitelist']);
            if (false === $app['serializer.disable_external_entities']) {
                $xmlDeserializationVisitor->enableExternalEntities();
            }

            return $xmlDeserializationVisitor;
        });

        $app['serializer.yaml_serialization_visitor'] = $app->share(function () use ($app) {
            return new YamlSerializationVisitor($app['serializer.naming_strategy']);
        });

        // serializer
        $app['serializer'] = $app->share(function () use ($app) {
            $serializer = SerializerBuilder::create()
                ->setDebug($app['debug'])
                ->setCacheDir($app['serializer.base_cache_dir'])
                ->addMetadataDirs($app['serializer.config_dirs'])
                ->setPropertyNamingStrategy($app['serializer.naming_strategy'])
                ->setObjectConstructor($app['serializer.doctrine_object_constructor'])
                ->setSerializationVisitor('array', $app['serializer.array_serialization_visitor'])
                ->setSerializationVisitor('json', $app['serializer.json_serialization_visitor'])
                ->setSerializationVisitor('xml', $app['serializer.xml_serialization_visitor'])
                ->setSerializationVisitor('yml', $app['serializer.yaml_serialization_visitor'])
                ->setDeserializationVisitor('json', $app['serializer.json_deserialization_visitor'])
                ->setDeserializationVisitor('xml', $app['serializer.xml_deserialization_visitor'])
                ->configureHandlers(function ($handlerRegistry) use ($app) {
                    $handlers = array(
                        'serializer.datetime_handler',
                        'serializer.array_collection_handler',
                        'serializer.form_error_handler',
                        'serializer.constraint_violation_handler'
                    );
                    foreach ($handlers as $handler) {
                        if (isset($app[$handler])) {
                            $handlerRegistry->registerSubscribingHandler($app[$handler]);
                        }
                    }
                })->build();

            $serializer->setSerializeNull($app['serializer.serialize_null']);
            $serializer->setVersion($app['serializer.version']);

            return $serializer;
        });
    }

    public function boot(Application $app)
    {
        // Register our annotations upon boot so that Doctrine doesn't crash and burn
        AnnotationRegistry::registerAutoloadNamespace('JMS\\Serializer\\Annotation', $app['serializer.src_dir']);
    }
}