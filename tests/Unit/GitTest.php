<?php

namespace RealPage\Marker\Tests\Unit;

use RealPage\Marker\Git;
use Symfony\Component\Process\Process;

class GitTest extends \PHPUnit_Framework_TestCase
{
    protected $repo;

    protected $rootLocation;

    public function setUp()
    {
        parent::setUp();
        $this->repo         = 'example/repo';
        $this->rootLocation = 'test/fixtures/repos';
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(Git::class, new Git());
    }

    public function testCustomCommandLine()
    {
        $process = new Process('ls -ltra');
        $git     = new Git($process);

        $this->assertSame($process, $git->getProcess());
    }

    public function testCloneIntoLocalRepositoryPath()
    {
        $cloneRepository = 'git clone ' . Git::GITHUB_URL . $this->repo . '.git ' . $this->rootLocation . DIRECTORY_SEPARATOR . $this->repo;
        $process         = $this->getMockBuilder(Process::class)->setConstructorArgs([''])->getMock();
        $process->expects($this->once())->method('setCommandLine')->with($cloneRepository);
        $process->expects($this->once())->method('disableOutput');
        $process->expects($this->once())->method('run');
        $process->expects($this->any())->method('isSuccessful')->will($this->returnValue(true));

        $git = new Git($process);
        $git->clone($this->repo, $this->rootLocation);
    }

    /**
     * @expectedException \RealPage\Marker\Exceptions\FailedToClone
     */
    public function testFailedCloneIntoLocalRepositoryPath()
    {
        $cloneRepository = 'git clone ' . Git::GITHUB_URL . $this->repo . '.git ' . $this->rootLocation . DIRECTORY_SEPARATOR . $this->repo;
        $process         = $this->getMockBuilder(Process::class)->setConstructorArgs([''])->getMock();
        $process->expects($this->once())->method('setCommandLine')->with($cloneRepository);
        $process->expects($this->once())->method('disableOutput');
        $process->expects($this->once())->method('run');
        $process->expects($this->any())->method('isSuccessful')->will($this->returnValue(false));

        $git = new Git($process);
        $git->clone($this->repo, $this->rootLocation);
    }

    public function testPullIntoLocalRepositoryPath()
    {
        $pullRepository = 'git -C ' . $this->rootLocation . DIRECTORY_SEPARATOR . $this->repo . ' pull';
        $location       = $this->rootLocation . DIRECTORY_SEPARATOR . $this->repo;
        $process        = $this->getMockBuilder(Process::class)->setConstructorArgs([''])->getMock();
        $process->expects($this->once())->method('setCommandLine')->with($pullRepository);
        $process->expects($this->once())->method('run');
        $process->expects($this->any())->method('isSuccessful')->will($this->returnValue(true));

        $git = new Git($process);
        $git->pull($location);
    }

    /**
     * @expectedException \RealPage\Marker\Exceptions\FailedToPull
     */
    public function testFailedPullIntoLocalRepositoryPath()
    {
        $pullRepository = 'git -C ' . $this->rootLocation . DIRECTORY_SEPARATOR . $this->repo . ' pull';
        $location       = $this->rootLocation . DIRECTORY_SEPARATOR . $this->repo;
        $process        = $this->getMockBuilder(Process::class)->setConstructorArgs([''])->getMock();
        $process->expects($this->once())->method('setCommandLine')->with($pullRepository);
        $process->expects($this->once())->method('run');
        $process->expects($this->any())->method('isSuccessful')->will($this->returnValue(false));

        $git = new Git($process);
        $git->pull($location);
    }
}

