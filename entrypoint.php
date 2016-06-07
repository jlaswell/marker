<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Github\Client;
use RealPage\Marker\Git;
use RealPage\Marker\Comparator;
use RealPage\Marker\Application;

class Helpers
{
    const CURRENT = 'current';

    const PREVIOUS = 'previous';

    public $application;

    public function __construct(Application $application = null)
    {
        $this->application = $application ?? new Application();
    }

    public function generateFullRepoPath($image, string $state = 'current')
    {
        $repoLocation  = $this->application->config->get('root_location');
        $imageLocation = $this->application->config->get('images.' . $image . '.parent_repository');

        return realpath(
            $repoLocation . DIRECTORY_SEPARATOR . $state . DIRECTORY_SEPARATOR . $imageLocation
        );
    }

    public function generateRepoPath($image, string $state = Helpers::CURRENT)
    {
        $allReposLocation = $this->application->config->get('root_location');
        $repoLocation     = $this->application->config->get('images.' . $image . '.parent_repository');

        return $allReposLocation . DIRECTORY_SEPARATOR . $state . DIRECTORY_SEPARATOR . $repoLocation;
    }

    public function generateLocationPath($image, string $state = 'current')
    {
        $repoPath     = $this->generateRepoPath($image, $state);
        $fileLocation = $this->application->config->get('images.' . $image . '.location');

        return $repoPath . DIRECTORY_SEPARATOR . $fileLocation;
    }

    public function generateIssueUrl(string $image): string
    {
        $url = 'https://api.github.com/repos/' . $this->application->config->get('images.' . $image . '.repository') . '/issues';

        return $url;
    }
}

if (!function_exists('env')) {
    function env($variable, $default)
    {
        $value = getenv($variable);

        return $value ? $value : $default;
    }
}

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$app    = new Application();
$helper = new Helpers($app);

// Copy the current directory to previous to preserve state
$app->filesystem->copyDirectory(
    $app->config->get('root_location') . DIRECTORY_SEPARATOR . 'current',
    $app->config->get('root_location') . DIRECTORY_SEPARATOR . 'previous'
);

// Try to clone on current
$images = $app->config->get('images');

function pullRepo($repoPath, $image, Helpers $helper)
{
    if ((new Git())->pull($repoPath)) {
        $current  = $helper->generateLocationPath($image, Helpers::CURRENT);
        $previous = $helper->generateLocationPath($image, Helpers::PREVIOUS);
        if (!Comparator::compareFilesAreSame($current, $previous)) {
            $config    = $helper->application->config->get('images.' . $image);
            $repoParts = explode('/', $config['repository']);
            // File an issue on the appropriate repo
            $client = new Client();
            $client->authenticate($helper->application->config->get('github_token'), null,
                Client::AUTH_URL_TOKEN);
            $client->api('issue')->create($repoParts[0], $repoParts[1],
                [
                    'title' => 'Outdated Dockerfile',
                    'body'  => 'The Dockerfile at ' . $config['location'] . ' in the [' . $config['parent_repository'] . '](https://github.com/' . $config['parent_repository'] . ') repo has been updated.' . PHP_EOL . PHP_EOL . 'Please update your Dockerfiles accordingly.',
                ]
            );
        }
    }
}

foreach ($images as $image => $values) {
    $repoPath = $helper->generateRepoPath($image);
    // Make sure initial repo exists
    if (!$app->filesystem->exists($repoPath)) {
        (new Git())->clone($values['parent_repository'], $app->config->get('root_location') . DIRECTORY_SEPARATOR . 'current' );
    } else {
        pullRepo($repoPath, $image, $helper);
    }
}