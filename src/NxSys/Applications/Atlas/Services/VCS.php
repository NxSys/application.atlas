<?php

namespace NxSys\Applications\Atlas\Services;

use NxSys\Applications\Atlas\Services\VCSUtil\SVN as SVN;

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
		$this->svn = new SVN\SVNLibrary($aRepo['url']);
		var_dump($this->svn->info());
		
	}
}