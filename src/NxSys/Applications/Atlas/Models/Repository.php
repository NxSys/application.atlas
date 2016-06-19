<?php

namespace NxSys\Applications\Atlas\Models;

class Repository extends BaseModel
{
	public $Location = []; //String
	public $Project = []; //Models\Project
	public $Commit = []; //Models\Commit
	
	public function getLocation()
	{
		$this->EmptyCheck("Location");
		return $this->Location;
	}
	
	public function setLocation(array $Location)
	{
		$this->TypeCheck($Location, "string");
		$this->Location = $Location;
	}
	
	public function addLocation(string $Location)
	{
		$this->Location[] = $Location;
	}
	
	public function getProject()
	{
		$this->EmptyCheck("Project");
		return $this->Project;
	}
	
	public function setProject(array $Project)
	{
		$this->TypeCheck($Project, "Project");
		$this->Project = $Project;
		$this->SetReciprocal($Project);
	}
	
	public function addProject(Project $Project)
	{
		if (!in_array($Project, $this->Project))
		{
			$this->Project = $Project;
			$this->SetReciprocal([$Project]);
		}
	}
	
	public function getCommit()
	{
		$this->EmptyCheck("Commit");
		return $this->Commit;
	}
	
	public function setCommit(array $Commit)
	{
		$this->TypeCheck($Commit, "Commit");
		$this->Commit = $Commit;
		$this->SetReciprocal($Commit);
	}
	
	public function addCommit(Commit $Commit)
	{
		if (!in_array($Commit, $this->Commit))
		{
			$this->Commit = $Commit;
			$this->SetReciprocal([$Commit]);
		}
	}
}