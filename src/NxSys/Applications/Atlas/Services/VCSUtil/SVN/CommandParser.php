<?php

namespace NxSys\Applications\Atlas\Services\VCSUtil\SVN;

use Symfony\Component\Process;

class CommandParser
{
	public function __construct($sBaseCommand, $aArgs = [], $aOptions = [])
	{
		$this->baseCommand = $sBaseCommand;
		$this->options = $aOptions;
		$this->args = $aArgs;
		$this->process = null;
		return $this;
	}
	
	public function addOption($sOption, $sValue = Null)
	{
		$this->options[$sOption] = $sValue;
		return $this;
	}
	
	public function addArgument($sArg)
	{
		$this->args[] = $sArg;
		return $this;
	}
	
	public function runCommand()
	{
		$this->process = new Process\Process($this->buildCommandString(), APP_CACHE_DIR);
		$this->process->mustrun();
		return $this;
	}
	
	public function getOutput()
	{
		if ($this->process != null)
		{
			//var_dump($this->process->getErrorOutput());
			return $this->process->getOutput();
		}
		return null;
	}
	
	protected function buildCommandString()
	{
		$sCmdStr = $this->baseCommand;
		
		foreach ($this->options as $sOption=>$sValue)
		{
			if (strlen($sOption)>1)
			{
				$sOptionIndicator = "--";
			}
			else
			{
				$sOptionIndicator = "-";
			}
			if ($sValue === Null)
			{
				$sCmdStr .= ' ' . $sOptionIndicator . $sOption;
			}
			else
			{
				if (strpos($sValue, ' ') === false)
				{
					$sCmdStr .= ' ' . $sOptionIndicator . $sOption . '=' . $sValue;
				}
				else
				{
					$sCmdStr .= ' ' . $sOptionIndicator . $sOption . '="' . $sValue . '"';
				}
			}
		}
		
		foreach ($this->args as $sArg)
		{
			$sCmdStr .= ' ' . $sArg;
		}
		
		//var_dump($this->escapeString($sCmdStr));
		return $this->escapeString($sCmdStr);
	}
	
	
	protected function escapeString($sString)
	{
		if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' )
        {
            $sString = str_replace( '%', '"%"', $sString );
            $sString = preg_replace( '(\\\\$)', '\\\\\\\\', $sString );

            return $sString;
        }

        return escapeshellarg( $sString );
	}
}