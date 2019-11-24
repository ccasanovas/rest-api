<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5c3d9cedbc9e4f7972471da5069a6888
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5c3d9cedbc9e4f7972471da5069a6888::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5c3d9cedbc9e4f7972471da5069a6888::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
