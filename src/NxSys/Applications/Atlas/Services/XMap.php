<?php

namespace NxSys\Applications\Atlas\Services;

use NxSys\Applications\Atlas\Services\XMapUtil\XMapNode;

use Silex\Application as WebApp;

class XMap
{
	public function generateNewXMap($iBuffer, $sCurrentRev, $aCurrentPath, $sNextRev, $aNextPath, $iMaxRelativeDepth = 3)
	{
		//Get current Map, top level bound by max relative depth.
		$oMap1 = $this->testMap1();
		//Get next map
		$oMap2 = $this->testMap2();
		
		//Determine which to parse first
		if ($iBuffer == 1)
		{
			$oCurrentMap = $oMap1;
			$oNextMap = $oMap2;
		}
		else
		{
			$oCurrentMap = $oMap2;
			$oNextMap = $oMap1;
		}
		
		$iMaxAbsoluteDepth = count($aNextPath) + $iMaxRelativeDepth;
		
		//Build map transition from First -> Second
		$aXMap = ["id" => "root",
				  "children" => $this->recursiveMap($oCurrentMap, $oNextMap, $iMaxAbsoluteDepth)];
		
		//Add any nodes (bound by depth scoping) created in Second map.
		$this->recursiveAdd($oNextMap, $oCurrentMap, $aXMap, $iMaxAbsoluteDepth);
		
		//Iterate over nodes to turn absolute size values to percentage values.
	}
	
	/**
	 * Recursively iterate over a node map, building the required XMap object.
	 * Will handle changes and removals.
	 * Will NOT handle additions, needs additional processing to go over new map to find nodes not in current map.
	 */
	private function recursiveMap($oCurrentNode, $oNewMap, $iMaxAbsoluteDepth)
	{
		$iCurrentDepth = count($oCurrentNode->path);
		
		$aNodeChildren = [];
		
		foreach ($oCurrentNode->children as $oChild)
		{	
			if ($iCurrentDepth + 1 > $iMaxAbsoluteDepth)
			{
				$iSize = [$this->recursiveSize($oChild)];
			}
			else
			{
				$iSize = [$oChild->size];
			}
			
			try
			{
				$oMatchingNode = $oNewMap->locate($oChild->path);
				if ($iCurrentDepth + 1 > $iMaxAbsoluteDepth)
				{
					$iSize[] = $this->recursiveSize($oMatchingNode);
				}
				else
				{
					$iSize[] = $oMatchingNode->size;
				}
			}
			catch (OutOfRangeException $e)
			{
				$iSize[] = 0;
			}
			
			$aCurrentChild = ["id" => join('/', $oChild->path),
							  "color" => [0.5],
							  "size" => $iSize];
			
			if ($iCurrentDepth + 1 <= $iMaxAbsoluteDepth and count($oChild->children) > 0)
			{
				$aCurrentChild["children"] = $this->recursiveMap($oChild, $oNewMap, $iMaxAbsoluteDepth);
			}
			
			$aNodeChildren[] = $aCurrentChild;
		}
		
		return $aNodeChildren;
	}
	
	private function recursiveSize($oCurrentNode)
	{
		$oSize = $oCurrentNode->size;
		
		foreach ($oCurrentNode->children as $oChild)
		{
			$oSize += $this->recursiveSize($oChild);
		}
		
		return $oSize;
	}
	
	private function recursiveAdd($oCurrentNode, $oOldMap, &$aXMap, $iMaxAbsoluteDepth)
	{
		$iCurrentDepth = count($oCurrentNode->path);
		
		foreach ($oCurrentNode->children as $oChild)
		{
			try
			{
				$oOldMap->locate($oChild->path);
				$this->recursiveAdd($oChild, $oOldMap, $aXMap, $iMaxAbsoluteDepth);
			}
			catch (OutOfRangeException $e)
			{
				if ($iCurrentDepth + 1 > $iMaxAbsoluteDepth)
				{
					$iSize = $this->recursiveSize($oChild);
				}
				else
				{
					$iSize = $oChild->size;
				}
				
				$aCurrentChild = ["id" => join('/', $oChild->path),
									"color" => [0.5],
									"size" => [0, $iSize]];
				
				if ($iCurrentDepth + 1 <= $iMaxAbsoluteDepth and count($oChild->children) > 0)
				{
					$aCurrentChild["children"] = $this->recursiveMap($oChild, $oNewMap, $iMaxAbsoluteDepth);
				}
				
				$this->addToXMap($aXMap);
			}
		}
	}
	
	private function addToXMap(&$aXMapRoot, $aPath, $aNode)
	{
		$aIndexPath = $this->findXMapPath($aXMapRoot, $aPath);
		
		$aTruePath = [];
		
		foreach ($aIndexPath as $iPiece)
		{
			$aTruePath[] = "children";
			$aTruePath[] = $iPiece;
		}
		
		$aReplacementArray = $this->buildReplacementArray($aNode, $aTruePath);
		
		$aXMapRoot = array_replace_recursive($aXMapRoot, $aReplacementArray);
	}
	
	private function buildReplacementArray($aNewNode, $remainingPath)
	{
		$arr = [];
		if (count($remainingPath) > 0)
		{
			$curPiece = $remainingPath[0];
			$remainingPath = array_slice($remainingPath, 1);
			$arr[$curPiece] = $this->buildReplacementArray($aNewNode, $remainingPath);
			return $arr;
		}
		else
		{
			return $aNewNode;
		}
	}
	
	private function findXMapPath($aXMap, $aPath, $aIndices = [], $iCurrentDepth = 1)
	{
		if (!array_key_exists("children", $aXMap))
		{
			return $aIndices;
		}
		$sNextID = join("/", array_slice($aPath, 0, $iCurrentDepth));
		
		foreach ($aXMap["children"] as $iKey=>$aChild)
		{
			if ($aChild["id"] == $sNextID)
			{
				$aIndices[] = $iKey;
				return $this->findXMapPath($aChild, $aPath, $aIndices, $iCurrentDepth + 1);
			}
		}
		
		return $aIndices;
	}
	
	private function testMap1()
	{
		$oRoot = new XMapNode(["root"], 1);
		$oFoo = new XMapNode(["root", "foo"], 1, $oRoot);
		new XMapNode(["root", "foo", "file1"], 500, $oFoo);
		new XMapNode(["root", "foo", "file2"], 300, $oFoo);
		$oBar = new XMapNode(["root", "bar"], 1, $oRoot);
		new XMapNode(["root", "bar", "file1", 750, $oBar]);
		
		return $oRoot;
	}
	
	private function testMap2()
	{
		$oRoot = new XMapNode(["root"], 1);
		$oFoo = new XMapNode(["root", "foo"], 1, $oRoot);
		new XMapNode(["root", "foo", "file1"], 500, $oFoo);
		new XMapNode(["root", "foo", "file2"], 400, $oFoo);
		$oBar = new XMapNode(["root", "bar"], 1, $oRoot);
		new XMapNode(["root", "bar", "file1", 700, $oBar]);
		new XMapNode(["root", "bar", "file2", 100, $oBar]);
		
		return $oRoot;
	}
}