<?php

namespace OpenCrest\Endpoints;

use OpenCrest\Endpoints\Objects\RacesObject;

class RacesEndpoint extends Endpoint
{
    /**
     * Uri
     *
     * @var string
     */
    public $uri = "races/";

    /**
     * @var string
     */
    public $object = RacesObject::class;
}