<?php

namespace NxSys\Applications\Atlas\Models;

class Developer extends BaseModel
{
	public $Name = []; //String
	public $User = []; //String
	public $Commit = []; //Models\Commit
	
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
	
	public function getUser()
	{
		$this->EmptyCheck("User");
		return $this->User;
	}
	
	public function setUser(array $User)
	{
		$this->TypeCheck($User, "string");
		$this->User = $User;
	}
	
	public function addUser(string $User)
	{
		$this->User[] = $User;
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