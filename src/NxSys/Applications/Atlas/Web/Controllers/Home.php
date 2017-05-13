<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as sfHttp;
use Symfony\Component\HttpKernel as sfHttpKern;

class Home
{
	#code
	public function welcome(WebApp $oApp)
	{
		$sContent=$oApp['twig']->render('AtlasWelcome.twig.html', []);
		return new sfHttp\Response($sContent);
	}

	public function forwardToDefault(WebApp $oApp, sfHttp\Request $oReq)
	{

		//@todo get def target

		$subRequest = sfHttp\Request::create('/code', 'GET', [],
									  $oReq->cookies->all(), [],
									  $oReq->server->all());

		if ($oReq->getSession())
		{
			$subRequest->setSession($oReq->getSession());
		}
		$oResponse = $oApp->handle($subRequest, sfHttpKern\HttpKernelInterface::SUB_REQUEST);

		return $oResponse;
	}
}
