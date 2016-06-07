<?php

namespace RealPage\Marker\Tests\Unit;

use RealPage\Marker\Comparator;

class ComparatorTest extends \PHPUnit_Framework_TestCase
{
    protected $firstPath;

    protected $secondPath;

    public function setUp()
    {
        parent::setUp();
        $this->firstPath  = 'tests/fixtures/thumbs-up.gif';
        $this->secondPath = 'tests/fixtures/thumbs-up-daft-punk.gif';
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(Comparator::class, new Comparator());
    }

    public function testSameFilesAreComparedCorrectly()
    {
        $this->assertTrue(Comparator::compareFilesAreSame($this->firstPath, $this->firstPath));
    }

    public function testDifferentFilesAreComparedCorrectly()
    {
        $this->assertFalse(Comparator::compareFilesAreSame($this->firstPath, $this->secondPath));
    }

    public function testGeneratesAValidShaFromFile()
    {
        $this->assertEquals(hash_file('sha256', $this->firstPath), Comparator::generateSha($this->firstPath));
    }
}

