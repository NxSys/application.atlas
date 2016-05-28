<?php
/**
 * Application
 *
 * $Id$
 * DESCRIPTION
 *
 * @link http://nxsys.org/spaces/atlas-code-explorer/wiki/
 * @package NxSys.Atlas\Main
 * @license http://nxsys.org/spaces/atlas-code-explorer/wiki/License
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2015 Nexus Systems, Inc.
 *
 * @author Chris R. Feamster <cfeamster@nxsysts.com>
 * @author $LastChangedBy$
 *
 * @version $Revision$
 */

/** Local Namespace **/
namespace NxSys\Applications\Atlas;

use Silex\Application as WebApp;
use Symfony\Component\HttpFoundation as SfHttp;
use Nette\Neon as NeonConfig;
use F2Dev\Utils as F2Utils;
use Igorw\Silex\ConfigServiceProvider;
class Application
{
	/* @var Silex\Application */
	public $oWebApp;
	public $oConfig;

	public static $aRegistry;

	public function __construct(WebApp $oWebApp)
	{

		//$this->oConfig=$oConfig;
		$this->oWebApp=$oWebApp;
		//if debug
		$this->oWebApp['debug']=1;
		$this->oWebApp->register(new ConfigServiceProvider(APP_ETC_DIR
														   .DIRECTORY_SEPARATOR
														   .'svn.yml'));
		

		//$this->oWebApp['monolog']->debug('Testing the Monolog logging.');
		//self::$aRegistry['svc']['log']=$this->oWebApp['monolog'];
	}
    
    public function routes()
    {        
		$this->oWebApp->mount('noroute', new \Silex\ControllerCollection(new \Silex\Route));
		$this->oWebApp->match('ping', function(){ return APP_IDENT.'-'.APP_VERSION;});
		$this->oWebApp->match('/', 		'NxSys\Applications\Atlas\Web\Controllers\Home::index');
		$this->oWebApp->match('/setup', 'NxSys\Applications\Atlas\Web\Controllers\Home::index');
		//$this->oWebApp->match('/list', [new Web\Controlers\Home, 'index']);
		$this->oWebApp->match('/examine', 'NxSys\Applications\Atlas\Web\Controllers\Examine::index');
		//$this->oWebApp->match('/search', [new Web\Controlers\Home, 'index']);
		//$this->oWebApp->match('/list', [new Web\Controlers\Home, 'index']);
    }

	public function init()
	{
        //get configuration
        $config=NeonConfig\Neon::decode(file_get_contents(APP_RESOURCE_DIR.DIRECTORY_SEPARATOR.'config-defaults.neon'));
		//identify self url
        //self::$aRegistry['conf']['sHomeUrl']=sprintf('%s://%s', $aHomeURL['scheme'], $aHomeURL['host']);
		
		//var_dump($aPortMap);
		//build running config
			//setup event listeners
		#we're using JiT routing, maybe?
		$this->routes();
		$this->oWebApp->boot();
	}

	public static function getResource($sType, $sName)
	{
		switch($sType)
		{
			case 'conf':
			{

				return self::$aRegistry['conf'][$sName];
			}
			case 'service':
			{
				return self::$aRegistry['svc'][$sName];
			}
		}
	}

	public function run()
	{
		$oReq=SfHttp\Request::createFromGlobals();
		$oResp=$this->oWebApp->handle($oReq);
        $oResp->send();
        $this->oWebApp->terminate($oReq, $oResp);
	}
}
