<?php

namespace NxSys\Applications\Atlas\Services;

use Silex\Application as WebApp;
use Elastica\Client as SearchClient;
use Elastica\Type\Mapping as SearchMapping;
use Elastica\Exception\Connection\HTTPException;

class Search
{
	public function __construct(WebApp $oApp, SearchClient $oClient)
	{
		$this->app = $oApp;
		$this->client = $oClient;
		if ($this->checkStatus())
		{
			$sIndexName = $this->app['config']['search']['index'];
			$this->index = $this->client->getIndex($sIndexName);
			if (!$this->index->exists())
			{
				$this->setupIndex();
			}
		}
	}
	
	public function checkStatus()
	{
		try
		{
			$this->client->getStatus();
		}
		catch (HTTPException $e)
		{
			return False;
		}
		return True;
	}
	
	/**
	 * Creates and configures an ElasticSearch Index
	 */
	public function setupIndex()
	{
		$this->index->create();
		
		$oProjectType = $this->index->getType('project');
		$oProjectType->setMapping( new SearchMapping($oProjectType,
											 ['name' => 'string',
											  'description' => 'string']));
		
		$oRepositoryType = $this->index->getType('repository');
		$oRepositoryType->setMapping( new SearchMapping($oRepositoryType,
														['location' => 'string',
														 '_parent' => $oProjectType]));
		
		$oCommitType = $this->index->getType('commit');
		$oCommitType->setMapping( new SearchMapping($oCommitType,
											['revision' => 'string',
											 'date' => 'date',
											 'message' => 'message',
											 '_parent' => $oRepositoryType]));
		
		$oFileType = $this->index->getType('file');
		$oFileType->setMapping( new SearchMapping($oFileType,
										  ['path' => 'string',
										   'contents' => 'string',
										   '_parent' => $oCommitType]));
		
		
		$oDeveloperType = $this->index->getType('developer');
		$oDeveloperType->setMapping( new SearchMapping($oDeveloperType,
											   ['name' => 'string',
												'user' => 'string',
												'_parent' => $oCommitType]));
	}
	
}