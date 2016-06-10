<?php

namespace NxSys\Applications\Atlas\Services;

use Silex\Application as WebApp;
use Elastica\Client as SearchClient;
use Elastica\Exception\Connection\HTTPException;

class Search
{
	public function __construct(WebApp $oApp, SearchClient $oClient)
	{
		$this->app = $oApp;
		$this->client = $oClient;
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
	
	
}