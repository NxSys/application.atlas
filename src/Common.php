<?php
//place loader and bootstrap code here

const APP_IDENT = 'ACE';
const APP_NAME 	= 'Atlas: Code Explorer';
//@todo get branch name or trunk rev
const APP_VERSION = 'trunk';

if(!defined('APP_BASE_DIR'))
{
	//because i'm in src, lets walk back a level
	define('APP_BASE_DIR', realpath(__DIR__.'/../'));
}


//**(Phar)Packed Dirs
//App Sources/Classpath
define('APP_SOURCE_DIR',   APP_BASE_DIR.DIRECTORY_SEPARATOR.'src');

//App binary resources (if in phar, consider extracting before using)
define('APP_RESOURCE_DIR', APP_BASE_DIR.DIRECTORY_SEPARATOR.'res');

//3rd Party Classes, Frameworks, and Libraries
// and other namespaced code
define('APP_VENDOR_DIR',   APP_BASE_DIR.DIRECTORY_SEPARATOR.'vendor');


//**(Phar)Unpacked\Redist Dirs;
//consider changing APP_BASE_DIR to getcwd() if the dir structure is updated\compiled

//Shared\Ext Libs e.g. other Phars, PhpExts, and other "packages"
define('APP_LIB_DIR', APP_BASE_DIR.DIRECTORY_SEPARATOR.'libs');

//"InSitu" Documentation
//define('APP_DOC_DIR', getcwd().DIRECTORY_SEPARATOR.'docs');

//Misc assorted files e.g. config files and etc
define('APP_ETC_DIR', APP_BASE_DIR.DIRECTORY_SEPARATOR.'etc');

//now you can use a bare autoloader, unless we're in a PHAR, then we need a shim...
set_include_path( APP_SOURCE_DIR.PATH_SEPARATOR
				 .APP_VENDOR_DIR.PATH_SEPARATOR
				 .get_include_path());
spl_autoload_register();

//Use non-ignorant autoloader
// because phars are a case affected by phpbug #49625
if (!function_exists('_SHIM_MATCH_CLASSFILE_ASIS_LOADER'))
{
	function _SHIM_MATCH_CLASSFILE_ASIS_LOADER($class)
	{
		$aPaths=explode(PATH_SEPARATOR, get_include_path());
		if(false!==strpos($class,'_')) //because twig....
		{
			$class=str_replace('_', DIRECTORY_SEPARATOR, $class);
		}
		if('\\' !== DIRECTORY_SEPARATOR)
		{
			$class=str_replace('\\', DIRECTORY_SEPARATOR, $class);
		}
		$aExts=array_reverse(explode(',', spl_autoload_extensions()));
		foreach ($aPaths as $sBasePath)
		{
			foreach($aExts as $ext)
			{
				$cpath=$sBasePath.DIRECTORY_SEPARATOR.$class.$ext;
				if (is_readable($cpath))
				{
					require_once $cpath;
					return true;
				}
			}
		}
		return false;
	}
	spl_autoload_register('_SHIM_MATCH_CLASSFILE_ASIS_LOADER');
}

//classmaps and include/require bloc's should be here
//require_once APP_VENDOR_DIR.DIRECTORY_SEPARATOR.'autoload.php';

//"Packages"
require_once 'phar://'.APP_LIB_DIR.DIRECTORY_SEPARATOR.'silex-1.3.5.phar/vendor/autoload.php';
require_once APP_LIB_DIR.DIRECTORY_SEPARATOR.'rb-4.3.1.php';