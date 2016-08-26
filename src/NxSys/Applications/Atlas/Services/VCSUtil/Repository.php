<?php

namespace NxSys\Applications\Atlas\Services\VCSUtil;

use NxSys\Applications\Atlas\Services\VCSUtil\SVN as SVN;

class Repository
{
	public function __construct($sRepositoryType, $sRepositoryURL, $sUsername = null, $sPassword = null)
	{
		$this->url = $sRepositoryURL;
		$this->files = new FileTree("/");
		
		if (strtolower($sRepositoryType) == 'svn')
		{
			$this->lib = new SVN\SVNLibrary($sRepositoryURL, $sUsername, $sPassword);
			return;
		}
		
		throw new InvalidVCSException("Specified VCS of type $sRepositoryType not supported.");
	}
	
	public function __call($method, $args)
	{
		return call_user_func_array([$this->lib,$method], $args);
	}
}

class InvalidVCSException extends \InvalidArgumentException implements VCSExceptionType{}