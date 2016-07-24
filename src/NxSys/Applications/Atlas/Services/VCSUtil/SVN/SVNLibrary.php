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
	
	public function info($sPath = null, $sRev = null)
	{
		$aOptions = ["xml" => null];
		if ($sPath == null)
		{
			$sPath = $this->url;
		}
		
		if ($iRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		return $this->runCommand("info", [$sPath], $aOptions);
	}
	
	public function ls($sPath = null, $sRev = null, $bRecursive = true)
	{
		$aOptions = ["xml" => null];
		if ($bRecursive)
		{
			$aOptions["recursive"] = null;
		}
		
		if ($sPath == null)
		{
			$sPath = $this->url;
		}
		
		if ($iRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		
		return $this->runCommand("list", [$sPath], $aOptions);
	}
	
	public function cat($sPath)
	{
		return $this->runCommand("cat", [$sPath]);
	}
	
	public function blame($sPath, $sRev = null, $bUseMergeHistory = true)
	{
		$aOptions = ["xml" => null];
		if ($bUseMergeInfo)
		{
			$aOptions["use-merge-history"] = null;
		}
		
		if ($iRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		
		return $this->runCommand("blame", [$sPath], $aOptions);
	}
	
	public function log($sPath, $sRev = null, $bStopOnCopy = true, $bUseMergeHistory = true)
	{
		$aOptions = ["xml" => null];
		if ($bStopOnCopy)
		{
			$aOptions["stop-on-copy"] = null;
		}
		
		if ($bUseMergeInfo)
		{
			$aOptions["use-merge-history"] = null;
		}
		
		if ($iRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		
		return $this->runCommand("log", [$sPath], $aOptions);
	}
}