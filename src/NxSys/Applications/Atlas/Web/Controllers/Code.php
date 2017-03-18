<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as sfHttp;
use Symfony\Component\HttpKernel as sfHttpKern;

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

	/**
	 * This does.....
	 * @api
	 * @param $oReq sfHttp\Request
	 * @param $oReq sfHttp\Request
	 * @return sfHttp\JsonResponse Must return an object
	 */
	public function apiGetNodeData(WebApp $oApp, sfHttp\Request $oReq)
	{
		//get posted data
		$aDataIn=json_decode($oReq->getContent(), true);
		if(!$aDataIn)
		{
			throw new sfHttpKern\Exception\BadRequestHttpException('Could not de-serialize json object');
		}

		//and do stuff
		$sDataOut=print_r($aDataIn, true);

		return new sfHttp\JsonResponse((object)[$sDataOut]);
	}
}