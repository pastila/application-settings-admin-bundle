<?php


namespace Tests\AppBundle;

use Tests\FixtureAwareWebTestCase;

class AppWebTestCase extends FixtureAwareWebTestCase
{
    /** @var Client */
    protected $client = null;

    protected function setUp ()
    {
        $this->client = $this->getClient(true);

        parent::setUp();

        $this->setUpFixtures();
        $this->executeFixtures();
    }

    protected function getClient($reload=false)
    {
        if (!$this->client || $reload)
        {
            $this->client = static::createClient();
        }

        return $this->client;
    }
}