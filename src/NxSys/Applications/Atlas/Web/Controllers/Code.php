<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as sfHttp;

class Code
{
	public function browse(WebApp $oApp, sfHttp\Request $oReq)
	{
		$sContent=$oApp['twig']->render('Code.browse.twig.html', []);
		return new sfHttp\Response($sContent);
	}
	public function displayLog(WebApp $oApp, sfHttp\Request $oReq)
	{

	}
	public function diff(WebApp $oApp, sfHttp\Request $oReq)
	{

	}
}