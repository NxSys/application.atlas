<?php

namespace NxSys\Applications\Atlas\Models;

class BaseModel
{
	/**
	 * Checks that Array has children that match given type.
	 * @param array $aArray Array to check.
	 * @param string $sType Type or Class Name to check against.
	 * @throws DataMismatchException
	 */
	protected function TypeCheck(array $aArray, string $sType)
	{
		foreach ($aArray as $mChild)
		{
			$sChildType = gettype($mChild);
			if ($sChildType == 'object')
			{
				$sChildClass = get_class($mChild);
				if ($sChildClass != "NxSys\\Applications\\Atlas\\Models\\" . $sType)
				{
					throw new DataMismatchException("Type expected was $sType, $sChildClass given.");
				}
			}
			elseif ($sChildType != $sType)
			{
				throw new DataMismatchException("Type expected was $sType, $sChildType given.");
			}
		}
	}

	/**
	 * Checks if property is empty, and throws an exception if it is.
	 * @param array $aArray Array to check.
	 * @throws DataEmptyException
	 */
	protected function EmptyCheck(string $sProp)
	{
		$aArray = $this->$sProp;
		$sClass = get_class($this);
		if (count($aArray) == 0)
		{
			throw new DataEmptyException("$sProp member requested on object $sClass, but no value was set.");
		}
	}
	
	protected function SetReciprocal(array $aObjects)
	{
		$aClassArray = explode("\\", get_class($this));
		$sThisName = array_pop($aClassArray);
		foreach ($aObjects as $oObject)
		{
			if (!in_array($this, $oObject->$sThisName))
			{
				$oObject->$sThisName[] = $this;
			}
		}
	}
}

class DataMismatchException extends \InvalidArgumentException implements DataExceptionType{}
class DataEmptyException extends \LogicException implements DataExceptionType{}