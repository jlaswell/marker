<?php

namespace RealPage\Marker;

class Comparator
{
    const ALGORITHM = 'sha256';

    public static function compareFilesAreSame($firstPath, $secondPath): bool
    {
        return self::generateSha($firstPath) === self::generateSha($secondPath);
    }

    public static function generateSha(string $path): string
    {
        return hash_file(self::ALGORITHM, realpath($path));
    }
}
