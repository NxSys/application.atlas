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


//Vendor Namespaces
use Silex\Application as WebApp;
use Symfony\Component\HttpFoundation as SfHttp;
use Igorw\Silex\ConfigServiceProvider;

//Service Namespaces
use NxSys\Applications\Atlas\Services\Search as SearchService;
use NxSys\Applications\Atlas\Services\VCS as VCSService;
use Elastica\Client as SearchClient;


class Application
{
	/* @var Silex\Application */
	public $oWebApp;
	public $oConfig;

	public static $aRegistry;

	public function __construct(WebApp $oWebApp)
	{
		//$this->oConfig=$oConfig;
		$this->app=$oWebApp;
		//if debug
		$this->app['debug']=1;

		//$this->oWebApp['monolog']->debug('Testing the Monolog logging.');
		//self::$aRegistry['svc']['log']=$this->oWebApp['monolog'];
	}
    
    public function routes()
    {
		$this->app->mount('noroute', new \Silex\ControllerCollection(new \Silex\Route));
		$this->app->match('ping', function(){ return APP_IDENT.'-'.APP_VERSION;});
		$this->app->match('/', 		'NxSys\Applications\Atlas\Web\Controllers\Home::index');
		$this->app->match('setup', 'NxSys\Applications\Atlas\Web\Controllers\Home::index');
		//$this->app->match('/list', [new Web\Controlers\Home, 'index']);
		$this->app->match('examine', 'NxSys\Applications\Atlas\Web\Controllers\Examine::index');
		//$this->app->match('/search', [new Web\Controlers\Search, 'index']);
		$this->app->match('sys/bel-views', 'NxSys\Applications\Atlas\Web\Controlers\BEL::getWebViews');
		$this->app->match('sys/bel-data-tree', 'NxSys\Applications\Atlas\Web\Controlers\BEL::getDataForTree');
    }
	
	public function services()
	{
		$this->app['name']=APP_NAME;
		$this->app['ident']=APP_IDENT;
		$this->app['version']=APP_VERSION;
		
		$this->app['elastica.search'] = function ($app) {
			return new SearchClient($app['config']['search']);
		};
		$this->app['atlas.search'] = function ($app) {
			return new SearchService($app, $app['elastica.search']);
		};
		$this->app['atlas.vcs'] = function ($app) {
			return new VCSService($app);
		};
		
		$this->app->register(new \Silex\Provider\TwigServiceProvider(),
							 ['twig.path' => APP_RESOURCE_DIR.DIRECTORY_SEPARATOR.'templates',
							  'twig.options' => ['cache' => APP_ETC_DIR.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'tmpl',
												 'debug' => true]
							 ]);
	}

	public function init()
	{
        //get configuration
		$this->loadConfig();
		
		//identify self url
        //self::$aRegistry['conf']['sHomeUrl']=sprintf('%s://%s', $aHomeURL['scheme'], $aHomeURL['host']);
		
		//var_dump($aPortMap);
		//build running config
			//setup event listeners
		#we're using JiT routing, maybe?
		$this->routes();
		$this->services();
		$this->app->boot();
	}
	
	public function loadConfig()
	{
		$this->app->register(new ConfigServiceProvider(APP_CONFIG_DIR.DIRECTORY_SEPARATOR.'config.yml', [], null, 'config'));
		$aInclude = $this->app['config']['include'];
		
		foreach ($aInclude as $sConfigFile)
		{
			$this->app->register(new ConfigServiceProvider(APP_CONFIG_DIR.DIRECTORY_SEPARATOR.$sConfigFile, [], null, 'config'));
		}
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
		$oResp=$this->app->handle($oReq);
        $oResp->send();
        $this->app->terminate($oReq, $oResp);
	}
}
