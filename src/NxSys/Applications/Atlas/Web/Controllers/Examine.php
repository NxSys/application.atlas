<?php

namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;
use Symfony\Component\HttpFoundation as SfHttp;

class Examine
{
	public function index(WebApp $oApp, SfHttp\Request $oRequest)
	{
		$oSearchService = $oApp['atlas.search'];
		// $oVCS = $oApp['atlas.vcs'];
		// var_dump($oSearchService->checkStatus());
		$html = "<html><body><ul>";
		//$oVCS->runTest("Atlas");
		$html = $html . '</ul></body></html>';
		return $html;

	}
}