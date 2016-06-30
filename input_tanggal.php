<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<title>Form Edit Data</title>
	</head>

	<script>
		function formatDate(date) {
			var year = date.getFullYear(),
				month = date.getMonth() + 1, // months are zero indexed
				month = month < 10 ? "0" + month : month,
				day = date.getDate(),
				hour = date.getHours(),
				minute = date.getMinutes(),
				second = date.getSeconds(),
				hourFormatted = hour % 12 || 12, // hour returned in 24 hour format
				minuteFormatted = minute < 10 ? "0" + minute : minute,
				morning = hour < 12 ? " am" : " pm";

			return day + "-" + month + "-" + year + " " + hourFormatted + ":" +
					minuteFormatted + ":" + second + morning;
		}

		function check(cb, id)
		{
			if (cb.checked) {
				// var d = new Date();
				// var now = formatDate( d ); // d.getDate() + '-' + (d.getMonth()+1) + '-' + d.getFullYear();
				document.getElementById( id ).value = formatDate( new Date() );
			}
			else
				document.getElementById( id ).value = "";
		}
	 </script>
	 
	<body>
		<?php
			$TotalRow = 10;
			
			if ( isset( $_POST[ 'aDate' ] )) {
				$aDate = $_POST[ 'aDate' ];
				foreach ($aDate as $date) {
					if ( !empty( $date ) )
						echo ( $date . "<br />" );
				}
			}
			else
				$aDate = array();
			reset( $aDate );

			if ( isset( $_POST[ '$crt' ] )) {
				$crt = $_POST[ '$crt' ];
				foreach ($crt as $rt) {
					if ( !empty( $rt ) )
						echo ( $rt . "<br />" );
				}
			}
			else {
				for ($x=0; $x< $TotalRow; $x++) { $crt[ $x ] = ""; }
			}

			if ( isset( $_POST[ '$chp' ] )) {
				$chp = $_POST[ '$chp' ];
				foreach ($chp as $hp) {
					if ( !empty( $hp ) )
						echo ( $hp . "<br />" );
				}
			}
			else {
				for ($x=0; $x< $TotalRow; $x++) { $chp[ $x ] = ""; }
			}

			if ( isset( $_POST[ '$cdl' ] )) {
				$cdl = $_POST[ '$cdl' ];
				foreach ($cdl as $dl) {
					if ( !empty( $dl ) )
						echo ( $dl . "<br />" );
				}
			}
			else {
				for ($x=0; $x< $TotalRow; $x++) { $cdl[ $x ] = ""; }
			}
		?>

		<form method="post" >
		  <input type="checkbox" onclick="check(this, 'date1');" />
		  Tanggal <input type="text" id="date1" name="aDate[]" value=><br />

		  <input type="checkbox" onclick="check(this, 'date2');" />
		  Tanggal <input type="text" id="date2" name="aDate[]"><br />

		  <input type="checkbox" onclick="check(this, 'date3');" />
		  Tanggal <input type="text" id="date3" name="aDate[]"><br />

		  <?php
			for ($x = 0; $x < $TotalRow; $x++) {
			  echo '<textarea name="$crt[]">' . $crt[$x] . '</textarea>';
			  echo '<textarea name="$chp[]">' . $chp[$x] . '</textarea>';
			  echo '<textarea name="$cdl[]">' . $cdl[$x] . '</textarea><br />';
			}
		  ?>
		  <input type="submit" name="submit" value="Submit Data">
	  </form>
	</body>
</html>
