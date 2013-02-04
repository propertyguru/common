<?php

namespace VGMdb\Component\Domain;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Base class for domain objects.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */
abstract class AbstractDomainObject implements DomainObjectInterface
{
    protected $repositories;
    protected $serializer;
    protected $logger;
    protected $dispatcher;

    // collection of EntityManipulators

    // each DomainObject should have its own interface definition!
    // expose object properties in array notation?

    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
    }
}