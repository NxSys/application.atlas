<?php

namespace NxSys\Applications\Atlas\Services\VCSUtil\SVN;

use Arbit\Xml as XmlLib;

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
	
	protected function parseXml($sOutput)
	{
		//var_dump($sOutput);
		return XmlLib\Document::loadString($sOutput);
	}
	
	public function info($sPath = null, $sRev = null)
	{
		$aOptions = ["xml" => null];
		if ($sPath == null)
		{
			$sPath = $this->url;
		}
		
		if ($sRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		return $this->parseXml($this->runCommand("info", [$sPath], $aOptions));
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
		
		if ($sRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		
		return $this->parseXml($this->runCommand("list", [$sPath], $aOptions));
	}
	
	public function cat($sPath)
	{
		return $this->runCommand("cat", [$this->url . $sPath]);
	}
	
	public function blame($sPath, $sRev = null, $bUseMergeHistory = true)
	{
		$aOptions = ["xml" => null];
		if ($bUseMergeHistory)
		{
			$aOptions["use-merge-history"] = null;
		}
		
		if ($sRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		
		return $this->runCommand("blame", [$sPath], $aOptions);
	}
	
	public function log($sPath, $sRev = null, $bStopOnCopy = true, $bUseMergeHistory = true, $bVerbose = false)
	{
		$aOptions = ["xml" => null];
		if ($bStopOnCopy)
		{
			$aOptions["stop-on-copy"] = null;
		}
		
		if ($bUseMergeHistory)
		{
			$aOptions["use-merge-history"] = null;
		}
		
		if ($sRev != null)
		{
			$aOptions["revision"] = $sRev;
		}
		
		if ($bVerbose)
		{
			$aOptions['verbose'] = null;
		}
		
		return $this->parseXml($this->runCommand("log", [$sPath], $aOptions));
	}
}