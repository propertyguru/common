<?php

namespace VGMdb;

/**
 * Base class for application controllers.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */
abstract class AbstractController
{
    protected $app;

    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app = null)
    {
        $this->setContainer($app);
    }

    /**
     * Attaches the application context.
     *
     * @param Application $app
     */
    public function setContainer(Application $app = null)
    {
        $this->app = $app;
    }
}