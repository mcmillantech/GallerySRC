<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	DataList.php
//		Class to List table contents
//
//  Requirements
//		Empty functions to be overriden
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------


class DataList
{
	protected	$mysqli;
	private		$sqlShow;
	private		$editPage;

	function __construct($mysqli)
	{
		$this->mysqli = $mysqli;
	}

	public function sqlShow($sql)
	{
		$this->sqlShow = $sql;
	}

	public function editPage($page)
	{
		$this->editPage = $page;
	}

	public function showHeading()
	{
	}

	public function run()
	{
            // Process according to mode parameter
            if (array_key_exists('mode', $_GET))
            {
                $mode = $_GET['mode'];
                switch ($mode)
                {
                case 'ins':
                    $this->insertItem();
                    break;
                case 'upd':
                    $this->updateItem();
                    break;
                case 'del':
                    $this->deleteItem();
                    break;
                }
            }
            $this->showList();
	}

	protected function insertItem()
	{
	}

	protected function upDateItem()
	{
	}

	protected function deleteItem()
	{
	}

	// -------------------------------------------
	//	Show the list of events
	//
	// -------------------------------------------
	protected function showList()
	{
								// Place a button for a new item	
		$newItem = 'window.location="' . $this->editPage . '?mode=ins"'; 
		echo "<button onClick='$newItem'>New ...</button>";
		echo "<br><br>";

		$this->showHeading();

		$result = $this->mysqli->query($this->sqlShow);
		while ($line =  mysqli_fetch_array($result, MYSQLI_ASSOC))
			$this->showListLine($line);
	
	}


	public function showListLine($line)
	{
	}

	// ----------------------------------------------
	//	Make a quoted, safe string from a POST field
	//
	//	Parameter	key to $_POST
	// ----------------------------------------------
	protected function postField($key)
	{
		$str = '"'
			. addslashes($_POST[$key])
			. '"';
		return $str;
	}

	protected function getCheckBox($name)
	{
		if (array_key_exists($name, $_POST) && $_POST[$name] == 'on')
			return 1;
		else
			return 0;
	}

	protected function sqlError($msg)
	{
		echo "$msg: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
	}
}

?>