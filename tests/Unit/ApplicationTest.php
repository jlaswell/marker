<?php

namespace RealPage\Marker\Tests\Unit;

use RealPage\Marker\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $testConfigDirectory;

    protected $testConfigFilename;

    public function setUp()
    {
        parent::setUp();
        $this->testConfigDirectory = 'tests/fixtures/config';
        $this->testConfigFilename  = 'example.php';
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function createApplication()
    {
        return new Application($this->testConfigDirectory, $this->testConfigFilename);
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(Application::class, $this->createApplication());
    }

    public function testGetsAllConfig()
    {
        $this->assertEquals(
            [
                'images' => [
                    'nginx' => [
                        'parent_repository' => 'docker-library/official-images',
                        'location'          => 'library/nginx',
                        'repository'        => 'realpage/nginx',
                    ],
                    'php'   => [
                        'parent_repository' => 'docker-library/official-images',
                        'location'          => 'library/php',
                        'repository'        => 'realpage/php',
                    ],
                ],
                'root_location' => 'tests/fixtures/repos',
            ],
            $this->createApplication()->config->all()
        );
    }

    public function testGetsSpecificConfigFromFile()
    {
        $this->assertEquals([
            'parent_repository' => 'docker-library/official-images',
            'location'          => 'library/php',
            'repository'        => 'realpage/php',
            ],
            $this->createApplication()->config->get('images.php')
        );
    }
}

