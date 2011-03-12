<?php
$vxml_library = dirname(__DIR__) . '/library';
$vxml_tests = __DIR__;
set_include_path(get_include_path() . PATH_SEPARATOR . $vxml_library . PATH_SEPARATOR . $vxml_tests);

require_once 'VXML/Loader.php';
VXML\Loader::registerAutoload();

require_once 'PHPUnit/Framework/TestCase.php';