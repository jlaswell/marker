# Marker

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Marker is a notification application that will submit issues to your existing Github repos when upstream changes have been made. We use it to tell when there has been an update to Dockerfiles upstream, but you can really use it for any upstream file you want to track!

## Install

*Currently, you need to pull down this repo to build a fresh container for every deploy. That will be updated on the next release.*

Via Composer

``` bash
$ composer require realpage/marker
$ vim config/marker.php
$ docker build -t yournamespace/marker .
$ docker run -e GITHUB_USERNAME=aUsername -e GITHUB_TOKEN=anAccessToken yournamespace/marker
```

*Future installs will use a yaml config file to remove the need for pulling this repo.*

## Usage

Here's a quick summary of what you will need to update in the `marker.php` config file. *Again, this will be updated to yaml and mounted to the container in the future.*

``` php
<?php

return [
    'github_username' => env('GITHUB_USERNAME', ''),
    'github_token'    => env('GITHUB_TOKEN', ''),
    'root_location'   => 'storage/repos',
    'images'          => [
        'nginx'      => [
            'parent_repository' => 'docker-library/official-images',    # Upstream repo to watch
            'location'          => 'library/nginx',                     # Location of the file to watch
            'repository'        => 'realpage/nginx',                    # Repository to submit an issue
        ],
        'php'        => [
            'parent_repository' => 'docker-library/official-images',
            'location'          => 'library/php',
            'repository'        => 'realpage/php',
        ],
    ],
];

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email john.laswell@realpage.com instead of using the issue tracker.

## Credits

- [John Laswell][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/realpage/marker.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/realpage/marker/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/realpage/marker.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/realpage/marker.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/realpage/marker.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/realpage/marker
[link-travis]: https://travis-ci.org/realpage/marker
[link-scrutinizer]: https://scrutinizer-ci.com/g/realpage/marker/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/realpage/marker
[link-downloads]: https://packagist.org/packages/realpage/marker
[link-author]: https://github.com/jlaswell
[link-contributors]: ../../contributors
