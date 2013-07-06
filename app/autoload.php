<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
///
use Symfony\Component\ClassLoader\UniversalClassLoader;
$loader = require __DIR__.'/../vendor/autoload.php';

///
$universalLoader = new UniversalClassLoader();

//$loader->registerNamespace('tcpdf_', __DIR__.'/../web/tcpdf');

/*
$loader->registerPrefixes(array(
    'Html2Pdf_'        => __DIR__.'/../vendor/html2pdf/lib',
));
*/
///
$universalLoader->registerNamespace("TCPDF", __DIR__.'/../web/tcpdf/');
$universalLoader->register();



// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
}


AnnotationRegistry::registerLoader(array($loader, 'loadClass'));






return $loader;
