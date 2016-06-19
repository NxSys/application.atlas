<?php

namespace NxSys\Applications\Atlas\Models;

class File extends BaseModel
{
	public $Path = []; //String
	public $Contents = []; //String
	public $Commit = []; //Models\Commit
	
	public function getPath()
	{
		$this->EmptyCheck("Path");
		return $this->Path;
	}
	
	public function setPath(array $Path)
	{
		$this->TypeCheck($Path, "string");
		$this->Path = $Path;
	}
	
	public function addPath(string $Path)
	{
		$this->Path[] = $Path;
	}
	
	public function getContents()
	{
		$this->EmptyCheck("Contents");
		return $this->Contents;
	}
	
	public function setContents(array $Contents)
	{
		$this->TypeCheck($Contents, "string");
		$this->Contents = $Contents;
	}
	
	public function addContents(string $Contents)
	{
		$this->Contents[] = $Contents;
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