<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0e6f69efea77dce5e54e95c87314bb26
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '65fec9ebcfbb3cbb4fd0d519687aea01' => __DIR__ . '/..' . '/danielstjules/stringy/src/Create.php',
        '72579e7bd17821bb1321b87411366eae' => __DIR__ . '/..' . '/illuminate/support/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Translation\\' => 30,
            'Stringy\\' => 8,
        ),
        'I' => 
        array (
            'Illuminate\\Support\\' => 19,
            'Illuminate\\Database\\' => 20,
            'Illuminate\\Contracts\\' => 21,
            'Illuminate\\Container\\' => 21,
        ),
        'C' => 
        array (
            'Carbon\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Stringy\\' => 
        array (
            0 => __DIR__ . '/..' . '/danielstjules/stringy/src',
        ),
        'Illuminate\\Support\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/support',
        ),
        'Illuminate\\Database\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/database',
        ),
        'Illuminate\\Contracts\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/contracts',
        ),
        'Illuminate\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/container',
        ),
        'Carbon\\' => 
        array (
            0 => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon',
        ),
    );

    public static $prefixesPsr0 = array (
        's' => 
        array (
            'src' => 
            array (
                0 => __DIR__ . '/../..' . '/giftbox',
            ),
        ),
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Inflector\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/inflector/lib',
            ),
        ),
    );

    public static $classMap = array (
        'giftbox\\Factory\\ConnectionFactory' => __DIR__ . '/../..' . '/src/giftbox/ConnectionFactory.php',
        'giftbox\\models\\Categorie' => __DIR__ . '/../..' . '/src/giftbox/models/Categorie.php',
        'giftbox\\models\\Coffret' => __DIR__ . '/../..' . '/src/giftbox/models/Coffret.php',
        'giftbox\\models\\Prestation' => __DIR__ . '/../..' . '/src/giftbox/models/Prestation.php',
        'giftbox\\view\\CatView' => __DIR__ . '/../..' . '/src/giftbox/view/CatView.php',
        'giftbox\\view\\PanierView' => __DIR__ . '/../..' . '/src/giftbox/view/PanierView.php',
        'giftbox\\view\\PrestaView' => __DIR__ . '/../..' . '/src/giftbox/view/PrestaView.php',
        'giftbox\\view\\htmlView' => __DIR__ . '/../..' . '/src/giftbox/view/htmlView.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0e6f69efea77dce5e54e95c87314bb26::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0e6f69efea77dce5e54e95c87314bb26::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit0e6f69efea77dce5e54e95c87314bb26::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit0e6f69efea77dce5e54e95c87314bb26::$classMap;

        }, null, ClassLoader::class);
    }
}
