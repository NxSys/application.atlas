<?php

/**
 * this can be relative or absolute SEE: realpath()
 *
 * if you can't move the index.php file then it should be:
 * 	./approot
 *
 * if you can move it, use the relative OR full path to the application directory:
 *  ../approot
 *   OR
 *  /mnt/virtualized/home/foo/www/wacc/executive
 *   OR EVEN
 *  d:\inetpub\httproot\foo\www_root\wacc\executive
 *
 */
$PATH_TO_APPROOT='../src';

////////////////////////////////////////////////////////////////////////////////
////////////// PLEASE CHANGE NOTHING ELSE BELOW THIS BARRIER ///////////////////
////////////////////////////////////////////////////////////////////////////////

define('APPROOT_PATH',realpath($PATH_TO_APPROOT));
define('WEBROOT_PATH',realpath(dirname(__FILE__)));

chdir(WEBROOT_PATH.DIRECTORY_SEPARATOR.'..');

if(!APPROOT_PATH || !WEBROOT_PATH)
{
	throw new RuntimeException('Please ensure that you have the correct path to '.$PATH_TO_APPROOT.' and that related permissions have been granted.');
}
//require_once APPROOT_PATH.'/Bootloader.php';

require_once __DIR__.'/../src/Common.php';
//\Bootloader::getDependency('wacc.webprocessor')->run();

$app = new Silex\Application();

$app->get('/hello', function () {
    return 'Hello!';
});

$app->run();

//all done