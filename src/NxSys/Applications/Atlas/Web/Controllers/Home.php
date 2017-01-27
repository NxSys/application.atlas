<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as SfHttp;

class Home
{

	#code
	public function index(WebApp $oApp)
	{
		$sContent=$oApp['twig']->render('Home.index.twig.html', []);
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
		$oResponse = $app->handle($subRequest, sfHttp\HttpKernelInterface::SUB_REQUEST);

		return $oResponse;
	}

}
