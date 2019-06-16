<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'think\\swoole\\' => array($vendorDir . '/topthink/think-swoole/src'),
    'think\\helper\\' => array($vendorDir . '/topthink/think-helper/src'),
    'think\\composer\\' => array($vendorDir . '/topthink/think-installer/src'),
    'think\\' => array($vendorDir . '/topthink/think-queue/src'),
    'app\\' => array($baseDir . '/application'),
    'XCron\\' => array($vendorDir . '/xavier/xcron-expression/src/Cron'),
    'Symfony\\Polyfill\\Util\\' => array($vendorDir . '/symfony/polyfill-util'),
    'Symfony\\Polyfill\\Php56\\' => array($vendorDir . '/symfony/polyfill-php56'),
    'SuperClosure\\' => array($vendorDir . '/jeremeamia/superclosure/src'),
    'PhpParser\\' => array($vendorDir . '/nikic/php-parser/lib/PhpParser'),
);
