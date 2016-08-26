<?php

namespace NxSys\Applications\Atlas\Services;

use Silex\Application as WebApp;
use Elastica\Client as SearchClient;
use Elastica\Query\QueryString;
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
											 ['name' => ['type' => 'string'],
											  'description' => ['type' => 'string']]));
		
		$oRepositoryType = $this->index->getType('repository');
		$oRepositoryType->setMapping( (new SearchMapping($oRepositoryType,
														['location' => ['type' => 'string']]))->setParent('project'));
		
		$oCommitType = $this->index->getType('commit');
		$oCommitType->setMapping( (new SearchMapping($oCommitType,
											['revision' => ['type' => 'string'],
											 'date' => ['type' => 'date'],
											 'message' => ['type' => 'string']]))->setParent('repository'));
		
		$oFileType = $this->index->getType('file');
		$oFileType->setMapping( (new SearchMapping($oFileType,
										  ['path' => ['type' => 'string'],
										   'directory' => ['type' => 'boolean'],
										   'contents' => ['type' => 'string']]))->setParent('commit'));
		
		
		$oDeveloperType = $this->index->getType('developer');
		$oDeveloperType->setMapping( (new SearchMapping($oDeveloperType,
											   ['name' => ['type' => 'string'],
												'user' => ['type' => 'string']]))->setParent('commit'));
	}
	
	public function indexObject($oObject)
	{
		
	}
	
	public function deindexObject($oObject)
	{
		
	}
	
	private function getSearchDocument($oObject)
	{
		
	}
	
	public function search($sQuery)
	{
		$oQuery = new QueryString($sQuery);
		$oQuery->setPhraseSlop(2); //Specifies number of acceptable word order variations. (https://www.elastic.co/guide/en/elasticsearch/guide/master/slop.html)
		$oQuery->setFuzzyMinSim(0.5); //Minimal similarity required. (Typos acceptable) (https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#_string_fields)
		$oQuery->setAnalyzer("english");
		$oEnglishResults = $this->index->search($oQuery);

		$oQuery = new QueryString($sQuery);
		$oSimpleResults = $this->index->search($oQuery);

		$aWeightedResults = ["English" => [$oEnglishResults, 1.0], "Simple" => [$oSimpleResults, 0.5]];
		
		return $aWeightedResults;
	}
	
}