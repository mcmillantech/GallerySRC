<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	order.php
//		Order an item
//		Called from the "Buy" button
//
//  Calling parameters	id of painting
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
function showForm($id)
function showLine($name, $size)
JS function checkFld(fld)
JS function checkFld(fld)
*/

	session_start();
	require_once "common.php";

	$config = setConfig();					// Connect to database, fetch the painting
	$mysqli = dbConnect($config);
	
	$mode = 0;
	$order = array();
	if (array_key_exists('correct', $_GET))
	{
		$mode = 1;
		$order = $_SESSION['order'];
	}
	$id = $_GET['id'];
	$sql = "SELECT * FROM paintings WHERE id=$id";
	$result = $mysqli->query($sql)
		or die("Collections error " . $mysqli->error);
	$record = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$title = "Buy now";

	require "top2.php";
	
	$title = $record['name'];
	showTop("Order Cunha Painting", "Order $title");

	showForm($id, $record);
	
// ---------------------------------------
//	Show a form to request delivery data
//
//	Parameter	id of product for posting
// ---------------------------------------
function showForm($id, $record)
{
	$acn = "order2.php?id=$id";
	echo "<p>Please enter your address</p>";
	echo "<form action='$acn' onsubmit='return check()' method='post'>";
		echo "<span class='prompt'>Your name *</span>";
		showLine('name', 45);
		echo "<br><span class='prompt'>Delivery address *</span>";
		showLine('addr1', 45);
		showLine('addr2', 45);
		showLine('addr3', 45);
		showLine('addr4', 45);
		echo "<span class='prompt'>Post code *</span>";
		showLine('pcode', 16);
		echo "<span class='prompt'>Your email *</span>";
		showLine('email', 60);
		
		if ($record['quantity'] > 1)
		{
			echo "<br><span class='prompt'>Number required *</span>";
			showLine('quantity', 5);
		}
		echo "<br><span class='prompt'>Country to ship to</span>";

		echo "<span class='input'>";
			echo "<select id='region' name='region'>";
			echo "<option value='uk' selected>UK</option>";
			echo "<option value='eu'>EU</option>";
			echo "<option value='usa'>USA, Canada</option>";
			echo "<option value='aus'>Australia, Brazil</option>";
			echo "<option value='collect'>Will collect from artist</option>";
			echo "</select>";
		echo "</span><br><br>";
//# option 1

		echo "<span class='prompt'>Voucher code</span>";
		showLine('voucher', 12, 1);
		echo "\n<span style='margin-left:450px'>";
		echo "<button type='button' onClick='checkVoucher()'>Check Code</button></span>";
//# end 1
		
		echo "<br><span class='prompt'>";
		echo "<input type='submit' value='Submit'>";
		echo "</span>";
	echo "</form>";
	echo "<br><br>";

}

function showLine($name, $size, $noLine = 0)
{
	global $mode, $order;

	echo "\n<span class='input'><input type='text' id='$name' name='$name' size='$size'";
	if ($mode)
		echo " value='" . $order[$name] . "'";
	echo "></span>";
	if ($noLine == 0)
		echo "<br>";
}

?>
</div>
<script>
// ---------------------------------------
//	Validate the form
//
//	Check for mandatory fields
// ---------------------------------------
function check()
{
	msg = '';
	err = false;

	checkFld ('name');
	checkFld ('addr1');
	checkFld ('pcode');
	if (err)
		alert (msg);
	return !err;
}

// ---------------------------------------
//	Check one input field
//
//	If missing, set the global error flag 
//	and build up the global message
// ---------------------------------------
function checkFld(fld)
{
	var el = document.getElementById(fld);
	if (el.value == '')
	{
		switch (fld)
		{
		case 'name':
			msg += "You must enter your name\n";
			break;
		case 'addr1':
			msg += "You must enter at least one line of address\n";
			break;
		case 'pcode':
			msg += "You must enter a postcode\n";
			break;
		}
		err = true;
	}
}

// ---------------------------------------
// ---------------------------------------
function checkVoucher()
{
	var el = document.getElementById('voucher');
	var code = el.value;

	hAjax = openAjax();
	hAjax.onreadystatechange=function()
	{
		if (ajax_response(hAjax))		// The list content is returned
		{								// Place it in the list element and show it
		    var httxt = hAjax.responseText;
		    alert (httxt);
		}
	}

	var str = "ajaxvoucher.php?code=" + code;
	hAjax.open("GET",str,true);
	hAjax.send();
}

</script>
</body>
</html>
