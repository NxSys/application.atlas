<?php

namespace NxSys\Applications\Atlas\Services\XMapUtil;

class XMapNode
{
	public function __construct($aPath, $iSize, XMapNode $oParent = null)
	{
		$this->path = $aPath;
		$this->size = $iSize;
		$this->children = [];
		$this->parent = $oParent;
		
		if ($oParent != null)
		{
			$this->parent->addChild($this);
		}
	}
	
	public function addChild(XMapNode $oChild)
	{
		$sChildPath = join("/", $oChild->path);
		
		if (!array_key_exists($sChildPath, $this->children))
		{
			$this->children[$sChildPath] = $oChild;
		}
	}
	
	public function locate($aPath)
	{
		$oRoot = $this;
		
		while ($oRoot->parent != null)
		{
			$oRoot = $oRoot->parent;
		}
		
		$aNextPath = [];
		$oCurrentNode = $oRoot;
		
		foreach ($aPath as $sNode)
		{
			$aNextPath[] = $sNode;
			if (array_key_exists(join("/", $aNextPath), $oCurrentNode->children))
			{
				$oCurrentNode = $oCurrentNode->children[join("/", $aNextPath)];
			}
			else
			{
				throw new OutOfRangeException("Attempted to navigate to an XMap path that doesn't exist");
			}
		}
		
		return $oCurrentNode;
	}
	
}