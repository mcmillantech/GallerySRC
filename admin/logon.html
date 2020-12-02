<!DOCTYPE html>
<html>
<head>
<title>Admin log on</title>
	<meta charset="utf-8">
	<style>
	container
	{
		position: 	absolute;
	}
	.prompt
	{
		position: 	absolute;
		left:		100px;
	}
	.data
	{
		position: 	absolute;
		left:		300px;
	}
	#errors
	{
		position: 	absolute;
		left:		100px;
		color:		red;
	}
	</style>
</head>
<body>
<h3>Please log on</h3>
<br><br>

<form onsubmit='doSubmit()' action='index.php'>
	<span class='prompt'>Password</span>
	<span class='data'><input type='password' id='pw'></span>
	<br><br>
	<input type='button' value='Log on' onClick='doSubmit()'>
</form>
<div id='errors'></div>

<script>
// ----------------------------------------------
//	Handle Logon button
//
//	Pass password to AJAX
//	AJAX will return OK or an error message
//
//	If OK, return true to the form action
//	Else 
// ----------------------------------------------
function doSubmit()
{	
	el = document.getElementById('pw');
	var pw = el.value;

	var hAjax;
	var reply = false;
	hAjax = new XMLHttpRequest();
	
	hAjax.onreadystatechange=function()
	{
		if (hAjax.readyState==4 && hAjax.status==200)
		{
		    var httxt = hAjax.responseText;
		    if (httxt == 'OK')						// Logon success
		    {
			   window.location = 'index.php';
	    	}
			var el = document.getElementById('errors');
		    el.innerHTML = httxt;					// Otherwise display the error
	    }
    }
    var str = "AjaxLogOn.php?pw=" + pw;
	hAjax.open("GET",str,true);
	hAjax.send();
}
</script>
</body>
</html>
