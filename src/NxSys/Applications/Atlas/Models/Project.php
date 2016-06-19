<?php

namespace NxSys\Applications\Atlas\Models;

class Project extends BaseModel
{
	public $Name = []; //String
	public $Description = []; //String
	public $Repository = []; //Models\Repository
	
	public function getName()
	{
		$this->EmptyCheck("Name");
		return $this->Name;
	}
	
	public function setName(array $Name)
	{
		$this->TypeCheck($Name, "string");
		$this->Name = $Name;
	}
	
	public function addName(string $Name)
	{
		$this->Name[] = $Name;
	}
	
	public function getDescription()
	{
		$this->EmptyCheck("Description");
		return $this->Description;
	}
	
	public function setDescription(array $Description)
	{
		$this->TypeCheck($Description, "string");
		$this->Description = $Description;
	}
	
	public function addDescription(string $Description)
	{
		$this->Description[] = $Description;
	}
	
	public function getRepository()
	{
		$this->EmptyCheck("Repository");
		return $this->Repository;
	}
	
	public function setRepository(array $Repository)
	{
		$this->TypeCheck($Repository, "Repository");
		$this->Repository = $Repository;
		$this->SetReciprocal($Repository);
	}
	
	public function addRepository(Repository $Repository)
	{
		if (!in_array($Repository, $this->Repository))
		{
			$this->Repository = $Repository;
			$this->SetReciprocal([$Repository]);
		}
	}
}