<?php

namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as sfHttp;


class BEL
{
	/**
	 * Get JS templates/views
	 *
	 */
	public function getWebView(WebApp $oApp, sfHttp\Request $oReq)
	{
		# $sViewName
		# code...
	}

	/**
	 *
	 *
	 */
	public function getDataForTree(WebApp $oApp, sfHttp\Request $oReq)
	{
		# $sRevIdInitial, $sPath='ROOT', $sRevIdEnd='HEAD'
		//if (0 === strpos($request->headers->get('Content-Type'), 'application/json'))

		return 'hi';
	}
}