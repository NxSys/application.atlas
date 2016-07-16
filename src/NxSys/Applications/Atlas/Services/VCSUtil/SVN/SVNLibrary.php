<?php

namespace NxSys\Applications\Atlas\Services\VCSUtil\SVN;


class SVNLibrary
{
	public function __construct($sRepositoryURL, $sUsername = null, $sPassword = null)
	{
		$this->url = $sRepositoryURL;
		$this->username = $sUsername;
		$this->password = $sPassword;
		$this->staticOptions = ["config-dir" => APP_CONFIG_DIR.DIRECTORY_SEPARATOR.'VCS'.DIRECTORY_SEPARATOR.'SVN',
								"non-interactive" => null];
		
		if ($this->username != null)
		{
			$this->staticOptions['username'] = $this->username;
		}
		
		if ($this->password != null)
		{
			$this->staticOptions['password'] = $this->password;
		}
	}
	
	protected function runCommand($sCommand, $aArgs = [], $aOptions = [])
	{
		$sResults = (new CommandParser('svn', array_merge([$sCommand],$aArgs), array_merge($this->staticOptions, $aOptions)))->runCommand()->getOutput();
		return $sResults;
	}
	
	public function info()
	{
		return $this->runCommand("info", [$this->url], ['xml' => null]);
	}
}