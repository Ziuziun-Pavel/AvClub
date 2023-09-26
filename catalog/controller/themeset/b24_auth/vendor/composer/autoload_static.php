<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e3a4534adca39765b746e5ea6e025c5
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'CurlHelper' => 
            array (
                0 => __DIR__ . '/..' . '/mervick/curl-helper',
            ),
        ),
    );

    public static $classMap = array (
        'Bitrix24Authorization\\Bitrix24Authorization' => __DIR__ . '/..' . '/ujy/bitrix24_api_authorization/src/bitrix24Authorization.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit4e3a4534adca39765b746e5ea6e025c5::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4e3a4534adca39765b746e5ea6e025c5::$classMap;

        }, null, ClassLoader::class);
    }
}
