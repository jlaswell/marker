<?php

namespace RealPage\Marker\Tests\Acceptance;

use Illuminate\Filesystem\Filesystem;
use RealPage\Marker\Git;

class GitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->cleanRepoDirectory();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->cleanRepoDirectory();
    }

    protected function cleanRepoDirectory()
    {
        $filesystem = new Filesystem();
        $filesystem->cleanDirectory('tests/fixtures/repos');
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(Git::class, new Git());
    }

    public function testClone()
    {
        (new Git())->clone('docker-library/official-images', 'tests/fixtures/repos');
        $this->assertFileExists('tests/fixtures/repos/docker-library/official-images/README.md');
    }
}
