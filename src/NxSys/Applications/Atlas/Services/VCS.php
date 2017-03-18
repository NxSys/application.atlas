<?php

namespace NxSys\Applications\Atlas\Services;

use NxSys\Applications\Atlas\Services\VCSUtil as VendorVCS;

use Silex\Application as WebApp;


class VCS
{
	public function __construct(WebApp $oApp)
	{
		$this->app = $oApp;
		$this->repos = [];
		foreach ($oApp['config']['svn'] as $sRepoName => $aRepoConfig)
		{
			$this->repos[$sRepoName] = new VendorVCS\Repository('SVN', $aRepoConfig['url'], $aRepoConfig['user'], $aRepoConfig['pass']);
		}
	}

	public function runTest($sRepo)
	{
		$this->cacheDir($sRepo, "/", "20");
		var_dump($this->repos[$sRepo]->files->find('/trunk/LICENSE.txt')->getContents());
		//var_dump($this->repos[$sRepo]);
	}

	public function cacheDir($sRepo, $sPath = "/", $sRevisionCap = "HEAD")
	{
		$aRepo = $this->repos[$sRepo];
		$aCommits = [];
		$oLogs = $aRepo->log($aRepo->url . $sPath, "$sRevisionCap:1", false, true, true);
		foreach ($oLogs->logentry as $oLog)
		{
			$sRev = $oLog['revision'];
			$sMsg = (string) $oLog->msg;
			$sAuthor = (string) $oLog->author;
			$sDate = (string) $oLog->date;

			$aCommits[$sRev] = ['msg' => $sMsg,
								'author' => $sAuthor,
								'date' => $sDate,
								'paths' => []];
			

			/**
			 * Not sure that we need a full list of modified paths yet, commenting this out for performance purposes.
			foreach ($oLog->paths as $oPath)
			{
				$aCommits[$sRev][] = (string) $oPath;
			}
			**/
		}
		$r = $aRepo->ls($aRepo->url . $sPath, $sRevisionCap);
		$files = $r->list->entry;
		foreach ($files as $file)
		{
			//@TODO: Find a simple way to get file size.
			$sFilePath = '/' . (string) $file->name;
			$sKind = $file['kind'];
			$sRevision = $file->commit[0]['revision'];
			$sAuthor = (string) $file->commit->author;
			$sDate = (string) $file->commit->date;

			$oNewFile = new VendorVCS\FileTree($aRepo, $sFilePath, $sKind === "dir");
			$oNewFile["Revision"] = $sRevision;
			$oNewFile["Author"] = $sAuthor;
			$oNewFile["Date"] = $sDate;

			$aCommit = $aCommits[$sRevision];

			$oNewFile["Message"] = $aCommit['msg'];

			$aRepo->files->addChild($oNewFile);
		}
	}
}