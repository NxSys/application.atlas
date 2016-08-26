<?php

namespace NxSys\Applications\Atlas\Services;

use NxSys\Applications\Atlas\Services\VCSUtil as VCS;

use Silex\Application as WebApp;


class VCS
{
	public function __construct(WebApp $oApp)
	{
		$this->app = $oApp;
		$this->repos = [];
		foreach ($oApp['config']['svn'] as $sRepoName => $aRepoConfig)
		{
			$this->repos[$sRepoName] = new VCS\Repository('SVN', $aRepoConfig['url'], $aRepoConfig['user'], $aRepoConfig['pass']);
		}
	}
	
	public function runTest($sRepo)
	{
		$this->cacheDir($sRepo);
	}
	
	public function cacheDir($sRepo, $sPath = "/")
	{
		$aRepo = $this->repos[$sRepo];
		$r = $aRepo->ls($aRepo->url . $sPath);
		$files = $r->list->entry;
		foreach ($files as $file)
		{
			$sFilePath = '/' . (string) $file->name;
			$sKind = $file['kind'];
			$sRevision = $file->commit[0]['revision'];
			$sAuthor = (string) $file->commit->author;
			$sDate = (string) $file->commit->date;
			
			$oNewFile = new VCS\FileTree($sFilePath, $sKind === "dir");
			$oNewFile["Revision"] = $sRevision;
			$oNewFile["Author"] = $sAuthor;
			$oNewFile["Date"] = $sDate;
			
			$aRepo->files->addChild($oNewFile);
		}
	}
}