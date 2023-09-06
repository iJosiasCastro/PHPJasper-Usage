<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit54ce485300402ea2df13635d85729cf5
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPJasper\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPJasper\\' => 
        array (
            0 => __DIR__ . '/..' . '/geekcom/phpjasper/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit54ce485300402ea2df13635d85729cf5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit54ce485300402ea2df13635d85729cf5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit54ce485300402ea2df13635d85729cf5::$classMap;

        }, null, ClassLoader::class);
    }
}
