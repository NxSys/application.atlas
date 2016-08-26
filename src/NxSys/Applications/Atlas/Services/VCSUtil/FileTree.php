<?php

namespace NxSys\Applications\Atlas\Services\VCSUtil;

class FileTree implements \RecursiveIterator, \Countable, \ArrayAccess
{
	public function __construct($sPath = "/", $bIsDir = True, FileTree $oParent = null)
	{
		$this->path = $sPath;
		$this->isDir = $bIsDir;
		$this->parent = $oParent;
		$this->children = [];
		$this->properties = [];
		$this->position = 0;
		
		if ($this->parent !== null)
		{
			$this->parent->addChild($this);
		}
	}
	
	public function addChild(FileTree $oChild)
	{
		if ($this->path === $oChild->path)
		{
			if (!$oChild->isDir)
			{
				throw new FileExistsException("Attempted to overwrite directory with file.");
			}
			//Directory got re-added, ignore it.
			return;
		}
		
		if ($this->path == '/')
		{
			$aMyPath = [''];
		}
		else
		{
			$aMyPath = explode('/', $this->path);
		}
		
		if ($oChild->path == '/')
		{
			$aChildPath = [''];
		}
		else
		{
			$aChildPath = explode('/', $oChild->path);
		}
		$iMyDepth = count($aMyPath);
		$iChildDepth = count($aChildPath);
		
		if ($iMyDepth >= $iChildDepth)
		{
			//Child is above this node, pass on to parent.
			return $this->parent->addChild($oChild);
		}
		
		if (!$this->isDir)
		{
			throw new FileExistsException("Can't add children to files!");
		}
		
		if ($iMyDepth + 1 < $iChildDepth)
		{
			//More than one level down.
			$sNextNodePath = implode('/', array_slice($aChildPath, 0, $iMyDepth + 1));
			$oNextNode = $this->find($sNextNodePath);
			if ($oNextNode === null)
			{
				//Intermediate dir doesn't exist. Add it.
				$oNextNode = new FileTree($sNextNodePath, True, $this);
			}
			return $oNextNode->addChild($oChild);
		}
		
		//Direct descendent.
		if (array_key_exists($oChild->path, $this->children))
		{
			throw new FileExistsException("Attempted to overwrite a file.");
		}
		
		//Check if parent is set.
		if ($oChild->parent === null)
		{
			$oChild->parent = $this;
		}
		
		//Finally, add the child!
		$this->children[$oChild->path] = $oChild;
	}
	
	public function find($sPath)
	{
		//Strip trailing slash.
		if (substr($sPath, -1) == '/' and strlen($sPath) > 1)
		{
			$sPath = substr($sPath, 0, -1);
		}
		
		if ($this->path === $sPath)
		{
			return $this;
		}
		
		if ($this->path == '/')
		{
			$aMyPath = [''];
		}
		else
		{
			$aMyPath = explode('/', $this->path);
		}
		
		if ($sPath == '/')
		{
			$aSearchPath = [''];
		}
		else
		{
			$aSearchPath = explode('/', $sPath);
		}
		
		$iMyDepth = count($aMyPath);
		$iSearchDepth = count($aSearchPath);
		
		
		
		if ($iMyDepth >= $iSearchDepth)
		{
			//Hand this off to the Parent node.
			if ($this->parent != null)
			{
				return $this->parent->exists($sPath);
			}
			else
			{
				return null;
			}
		}
		
		//Searching for a child with greater depth than this Node.
		
		$sSearchRoot = implode('/', array_slice($aSearchPath, 0, $iMyDepth));
		if ($sSearchRoot === "")
		{
			$sSearchRoot = '/';
		}
		
		if ($sSearchRoot != $this->path)
		{
			//Part of a different tree?
			//Pass upwards.
			if ($this->parent != null)
			{
				return $this->parent->exists($sPath);
			}
			else
			{
				return null;
			}
		}
		
		if (!$this->isDir)
		{
			//Not a dir, search node can't possibly exist.
			return null;
		}
		
		if (array_key_exists($sPath, $this->children))
		{
			return $this->children[$sPath];
		}
		
		$sNextNode = $sSearchRoot . '/' . $aSearchPath[$iMyDepth];
		if (array_key_exists($sNextNode, $this->children))
		{
			if ($this->children[$sNextNode]->isDir)
			{
				//Recursive search
				return $this->children[$sNextNode]->find($sPath);
			}
			else
			{
				//Child path is not a dir, search node can't exist.
				return null;
			}
		}
		//Child path doesn't exist.
		return null;
	}
	
	//###Countable###
	public function count()
	{
		return count($this->children);
	}
	//###Countable###
	
	//##RecursiveIterator###
	public function hasChildren()
	{
		return count($this->current()) > 0;
	}
	
	public function getChildren()
	{
		return $this->current;
	}
	//##RecursiveIterator###
	
	//##Iterator###
	public function rewind()
	{
        $this->position = 0;
    }

    public function current()
	{
        return $this->children[$this->key()];
    }

    public function key()
	{
        return array_keys($this->children)[$this->position];
    }

    public function next()
	{
        ++$this->position;
    }

    public function valid()
	{
        return isset(array_keys($this->children)[$this->position]);
    }
	//##Iterator###
	
	//##ArrayAccess###
	public function offsetSet($offset, $value)
	{
       $this->properties[$offset] = $value;
    }
	
    public function offsetExists($offset)
	{
		return array_key_exists($this->properties[$offset]);
    }
	
    public function offsetUnset($offset)
	{
        unset($this->properties[$offset]);
    }
	
    public function offsetGet($offset)
	{
        return $this->properties[$offset];
    }
	//##ArrayAccess###
}

class FileExistsException extends \OutOfBoundsException implements VCSExceptionType{}