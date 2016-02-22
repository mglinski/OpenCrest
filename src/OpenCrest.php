<?php

namespace OpenCrest;

use OpenCrest\Endpoints\AlliancesEndpoint;
use OpenCrest\Endpoints\BloodLinesEndpoint;
use OpenCrest\Endpoints\CharactersEndpoint;
use OpenCrest\Endpoints\ConstellationsEndpoint;
use OpenCrest\Endpoints\CorporationsEndpoint;
use OpenCrest\Endpoints\CrestEndpoint;
use OpenCrest\Endpoints\PlanetsEndpoint;
use OpenCrest\Endpoints\RegionsEndpoint;
use OpenCrest\Endpoints\SystemsEndpoint;
use OpenCrest\Endpoints\TypesEndpoint;
use OpenCrest\Endpoints\WarsEndpoint;

class OpenCrest
{
    /**
     * @var null|string
     */
    protected static $apiVersion = "v3";
    /**
     * @var string
     */
    protected static $version = "1.0.0";
    /**
     * @var string
     */
    private static $token;
    /**
     * @var TypesEndpoint
     */
    public $types;
    /**
     * @var RegionsEndpoint
     */
    public $regions;
    /**
     * @var ConstellationsEndpoint
     */
    public $constellations;
    /**
     * @var SystemsEndpoint
     */
    public $systems;
    /**
     * @var PlanetsEndpoint
     */
    public $planets;
    /**
     * @var AlliancesEndpoint
     */
    public $alliances;
    /**
     * @var CharactersEndpoint
     */
    public $characters;
    /**
     * @var CorporationsEndpoint
     */
    public $corporations;
    /**
     * @var CrestEndpoint
     */
    public $crest;
    /**
     * @var WarsEndpoint
     */
    public $wars;
    public $bloodLines;

    /**
     * OpenCrest constructor.
     *
     * @param $token
     * @param $apiVersion
     */
    public function __construct($token = "", $apiVersion = null)
    {
        self::$token = $token;

        $this->alliances = new AlliancesEndpoint();
        $this->characters = new CharactersEndpoint();
        $this->corporations = new CorporationsEndpoint();
        $this->types = new TypesEndpoint();
        $this->regions = new RegionsEndpoint();
        $this->constellations = new ConstellationsEndpoint();
        $this->systems = new SystemsEndpoint();
        $this->planets = new PlanetsEndpoint();
        $this->crest = new CrestEndpoint();
        $this->wars = new WarsEndpoint();
        $this->bloodLines = new BloodLinesEndpoint();

        if ($apiVersion) {
            self::$apiVersion = $apiVersion;
        }
    }

    /**
     * @return null|string
     */
    public static function apiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @return string
     */
    public static function version()
    {
        return self::$version;
    }

    /**
     * @return Endpoints\Objects\ListObject
     */
    public static function status()
    {
        return (new CrestEndpoint())->all();
    }

    public static function token()
    {
        return self::$token;
    }
}