<?php

namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;
use Symfony\Component\HttpFoundation as SfHttp;
use Webcreate\Vcs\Common\Adapter\CliAdapter;
use Webcreate\Vcs\Svn\Parser\CliParser;
use Webcreate\Util\Cli;

use Webcreate\Vcs;

class Examine
{
	public function index(WebApp $oApp, SfHttp\Request $oRequest)
	{
		$aRepos = $oApp['svn'];
		$html = "<html><body><ul>";
		foreach ($aRepos as $aRepo)
		{
			$sConfigDir = $aRepo['config'];
			$oSVN = new Vcs\Svn($aRepo['url'], new CliAdapter("svn --config-dir=$sConfigDir", new Cli(), new CliParser()));
			$dirs = [['.']];
			while (count($dirs) > 0)
			{
				$dir = array_pop($dirs);
				$nodes = $oSVN->ls(join('/', $dir));
				foreach ($nodes as $node)
				{
					if ($node->isDir())
					{
						$newDir = $dir;
						$newDir[] = $node->getFilename();
						$dirs[] = $newDir;
						
					}
					else
					{
						$html = $html . '<li>' . join('/', $dir) . '/' . $node->getFilename() . '</li>';
					}
				}
			}
		}
		$html = $html . '</ul></body></html>';
		return $html;
		
	}
}