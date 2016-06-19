<?php

namespace NxSys\Applications\Atlas\Models;

class Commit extends BaseModel
{
	public $Revision = []; //String
	public $Date = []; //DateTime
	public $Message = []; //String
	public $Repository = []; //Models\Repository
	public $File = []; //Models\File
	public $User = []; //Models\User
	
	public function getRevision()
	{
		$this->EmptyCheck("Revision");
		return $this->Revision;
	}
	
	public function setRevision(array $Revision)
	{
		$this->TypeCheck($Revision, "string");
		$this->Revision = $Revision;
	}
	
	public function addRevision(string $Revision)
	{
		$this->Revision[] = $Revision;
	}
	
	public function getDate()
	{
		$this->EmptyCheck("Date");
		return $this->Date;
	}
	
	public function setDate(array $Date)
	{
		$this->TypeCheck($Date, "DateTime");
		$this->Date = $Date;
	}
	
	public function addDate(\DateTime $Date)
	{
		$this->Date[] = $Date;
	}
	
	public function getMessage()
	{
		$this->EmptyCheck("Message");
		return $this->Message;
	}
	
	public function setMessage(array $Message)
	{
		$this->TypeCheck($Message, "string");
		$this->Message = $Message;
	}
	
	public function addMessage(string $Message)
	{
		$this->Message[] = $Message;
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
	
	public function getFile()
	{
		$this->EmptyCheck("File");
		return $this->File;
	}
	
	public function setFile(array $File)
	{
		$this->TypeCheck($File, "File");
		$this->File = $File;
		$this->SetReciprocal($File);
	}
	
	public function addFile(File $File)
	{
		if (!in_array($File, $this->File))
		{
			$this->File = $File;
			$this->SetReciprocal([$File]);
		}
	}
	
	public function getUser()
	{
		$this->EmptyCheck("User");
		return $this->User;
	}
	
	public function setUser(array $User)
	{
		$this->TypeCheck($User, "User");
		$this->User = $User;
		$this->SetReciprocal($User);
	}
	
	public function addUser(User $User)
	{
		if (!in_array($User, $this->User))
		{
			$this->User = $User;
			$this->SetReciprocal([$User]);
		}
	}
	
}