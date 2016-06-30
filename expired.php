<!DOCTYPE html>
<html>
<head>
	<title>HTS APP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/sticky-footer-navbar.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
	<div class="header-nav">
		<nav class="navbar navbar-fixed-top navbar-style">
			<div class="container">
				<a class="navbar-brand logo" href="#">Approval</a>
				<div>
					<img class="logo-image" src="img/logo_hts_glowing.png" alt="">
				</div>
			</div>
		</nav>
	</div>
	<div class="header-date">
		<div class="container">
			<div class="date">
				<?php echo date("l"); echo('&nbsp;'); echo date("d/m/Y"); echo('&nbsp;'); ?>
			</div>
			<div id="clock" class="time"></div>
			<div class="user">
				<b>
					<?php echo ucwords($user); ?>
				</b>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="content">
				<div class="alerts" style="line-height:30px;">
					<font color="#FF0000">Data link tidak ditemukan atau sudah expired.!</font>
				</div>			
			</div>
		</div>
	</div>
	<footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2016 PT. Hawk Teknologi Solusi, All Rights Reserved.</p>
      </div>
    </footer>
</body>
<script type="text/javascript">
function startTime()
{
var today=new Date()
var h=today.getHours()
var m=today.getMinutes()
var s=today.getSeconds()
var ap="AM";

//to add AM or PM after time

if(h>11) ap="PM";
if(h>12) h=h-12;
if(h==0) h=12;

//to add a zero in front of numbers<10

m=checkTime(m)
s=checkTime(s)

document.getElementById('clock').innerHTML=h+":"+m+":"+s+" "+ap
t=setTimeout('startTime()', 500)
}

function checkTime(i)
{
if (i<10)
{ i="0" + i}
return i
}

window.onload=startTime;

</script>