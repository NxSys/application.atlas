<?php

namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;
use Symfony\Component\HttpFoundation as SfHttp;

use Webcreate\Vcs;

class Examine
{
	public function index(WebApp $oApp, SfHttp\Request $oRequest)
	{
		return "I'm Here!";
		
	}
}