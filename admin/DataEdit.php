<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	DataEdit.php
//		Class to edit listed items
//
//  Requirements
//		Empty functions to be overriden
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
function __construct($mysqli)
public function run()
protected function setNewItem()
protected function fetchItem()
protected function showForm($mode)
protected function showLine($prompt, $dta, $key, $size)
protected function textArea($prompt, $dta, $key, $rows, $cols)
protected function showDropDown($prompt, $dta, $key, $values)
protected function showCheckBox($prompt, $dta, $key)
*/
class DataEdit
{
	protected	$mysqli;
	private		$sqlShow;
	private		$editPage;
	protected	$record;

	function __construct($mysqli)
	{
		$this->mysqli = $mysqli;
	}

	public function run()
	{
		$mode = $_GET['mode'];
		switch ($mode)
		{
		case 'ins':
			$this->setNewItem();
			break;
		case 'upd':
			$this->fetchItem();
			break;
		}
//	print_r($this->record);	
		$this->showForm($mode);
	}

	protected function setNewItem()
	{
	}

	protected function fetchItem()
	{
	}

	protected function showForm($mode)
	{
	}

	// -------------------------------------------
	// -------------------------------------------
	protected function showLine($prompt, $dta, $key, $size)
	{
		$value = '"' . $dta[$key] . '"';
		echo "\n<span class='prompt'>$prompt</span>";
		echo "<span class='input'>";
		echo "<input type='text' name='$key' id='$key' onChange='fldChange()' size='$size' value=$value>";
		echo "</span><br><br>";
	}
	
	// -------------------------------------------
	// -------------------------------------------
	protected function textArea($prompt, $dta, $key, $rows, $cols)
	{
		$value = $dta[$key];
		echo "\n<span class='prompt'>$prompt</span>";
		echo "<span class='input'>";
		echo "<textarea rows='$rows' cols='$cols' name='$key' onChange='fldChange()'>";
		echo $value;
		echo "</textarea></span>";
	
		for ($i=0; $i<=$rows; $i++)			// Space over the box
			echo "<br>";
	}

	// ---------------------------------------------------
	//  Show a drop down
	//
	// ---------------------------------------------------
	protected function showDropDown($prompt, $dta, $key, $values)
	{
		$curValue = $dta[$key];
		echo "\n<span class='prompt'>$prompt</span>";
		echo "<span class='input'>";
		echo "<select id='$key' name='$key'>";
		for ($n=0; $n<count($values); $n++)
		{
			$item = $values[$n];
			echo "\n<option value='$item'";
			if ($item == $curValue)
				echo " selected";
			echo ">$item</option>";
		}
		echo "</select></span><br><br>";
	}

	// ---------------------------------------------------
	//  Show a check box
	//
	// ---------------------------------------------------
	protected function showCheckBox($prompt, $dta, $key)
	{
		$value = $dta[$key];

		echo "\n<span class='prompt'>$prompt</span>";
		echo "<span class='input'>";
		echo "<input type='checkbox' id='$key' name='$key'";
		if ($value)
			echo " checked";
		echo "></span><br><br>";
	}
}

