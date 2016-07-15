<?php

namespace NxSys\Applications\Atlas\Services;

use Silex\Application as WebApp;

class VCS
{
	public function __construct(WebApp $oApp)
	{
		$this->app = $oApp;
		$this->repos = $oApp['config']['svn'];
		
	}
	
	public function runTest($sRepo)
	{
		$aRepo = $this->repos[$sRepo];
	}
}