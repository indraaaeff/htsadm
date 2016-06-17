<html>
<head>
	<title>HTS APP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
	<?php

	if ( isset($_GET['u']) && isset($_GET['p']) && isset($_GET['k']) ) {
		include "connection.php";
		include "user.php";
		$PPO_Number = $_GET['p'];
		$PPO_Table = $Database->query( "Call GetPPO_Number( '$PPO_Number' )" );
		$key = "";
		if ( $PPO_Table && ($PPO_Table->num_rows > 0) ) { 
			$row = $PPO_Table->fetch_assoc();
			
			if ( ($_GET['k'] == $row['key_param'])  && ($tgl <= $row['expiration'])  ) {
				$by =$row['submit_by'];
				 $tgl_pengajuan=$row['tgl_pengajuan'];
				 $ex=$row['expiration'];
				$key = $_GET['k'];
			}
			$PPO_Table->data_seek(0);  // Go top
		}

		if ($key != ""){
			switch ( $_GET['u'] ) {
				case BOD_RT_ID : 
					$_SESSION['username'] = BOD_RT;
					break;

				case BOD_HP_ID : 
					$_SESSION['username'] = BOD_HP;
					break;

				case BOD_DL_ID : 
					$_SESSION['username'] = BOD_DL;
					break;
			}
		}
	}

	if( !isset( $_SESSION['username'] )) {
		if ( $PPO_Table ) {
			$PPO_Table->free();
			$Database->close();
		}

		if ( isset($_GET['u']) )
			header( "Location:expired.php?u=$_GET[u]" );
		else
			header( "Location:expired.php" );
		
	}
		//user session
		$user=$_SESSION['username'];
	?>

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
	<!-- spasi header dan content -->
	<div class="kotak_user">
	<div class="user_res">
		<b><?php echo ucwords($user);?></b>
	</div>
	</div>
	<!-- spasi user dengan content -->
    <?php
		if ($PPO_Table && ($PPO_Table->num_rows > 0)) {
	    $PPO_Table->data_seek(0);  // Go top
	    $row = $PPO_Table->fetch_assoc();
		$PPO_Number = $row['no_ppo'];
		$PPO_Table->free();
		$Database->next_result();
		}

    ?>

    <?php

	if (isset($_GET['p'])) {
	//$user=BOD_RT;
		$PPO_TableDetail = $Database->query( "Call GetPPO_Detail( '$PPO_Number' )" );
			if ($PPO_TableDetail && ($PPO_TableDetail->num_rows > 0)) {
		
	?>
	<div class="kotak_po">
		<div class="panel panel-default">
			<div class="panel-heading nopadding" style="background:#f26904;">
				<div class="po_head">
					<table class="table nopadding" style="margin-bottom:0px;">
						<tbody style="color:white;">
							<tr>
								<td><b>No Pengajuan : <?php echo $PPO_Number; ?></b></td>
								<td><b>Tanggal : <?php echo $tgl_pengajuan;?></b></td>
								<td><b>Submitted By : <?php echo $by;?></b></td>
								<td><b><?php $total_ppo=$PPO_TableDetail->num_rows; echo "Total PO : $total_ppo";?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="hidden_po" style="color:white;"> 
				 	<b>
				 		No Pengajuan : <?php echo $PPO_Number; ?><br>
							Tanggal :  <?php echo $tgl_pengajuan;?><br>
						Submitted By <?php echo $by;?><br>
						<?php 
							$total_ppo=$PPO_TableDetail->num_rows;
							echo "Total PO : $total_ppo";
						?>
					</b>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<form method="post" action="post.php">
						<table class="table table-striped table-bordered table-hover">
							<thead class="table_po">
								<tr>
									<th></th>
									<th class="tengah">NO PO</th>
									<th class="tengah">NAMA VENDOR</th>
									<th class="tengah">TANGGAL PO</th>
									<th class="tengah">PPN</th>
									<th class="tengah">TOTAL</th>
									<th class="tengah">RT</th>
									<th class="tengah">HP</th>
									<th class="tengah">DL</th>
									<th class="tengah">NOTE</th>
								</tr>

							</thead>
							<tbody>
								<!-- loop for row details -->
								<?php
								$no = +1;
								$grand= 0;
								while ($row = $PPO_TableDetail->fetch_assoc()) {

								?>
								<tr align="center" id="detail_column">
            						<td><?php echo $no;?></td>
            						<td><?php echo $row['no_po'];?></td>
            						<td><?php echo $row['nama_vendor'];?></td>
            						<td><?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?></td>
            						<?php 
	            						if ($row['non_ppn'] == 0)
	            							{ $ppn='ya';
	            							echo '<td align="center">Ya</td>';
	            							echo "<input type='hidden' name='ppn' value='$ppn'>";
	            						}else {
	            							$ppn='tidak';
	            							echo '<td align="center">Tidak</td>';
	            							echo "<input type='hidden' name='ppn' value='$ppn'>";
	            						}
            						?>
            						<td><?php echo number_format( $row['total'] );?></td>
            						<?php 
            							$grand += $row['total'];
            						?>
            						<?php 
	            						$po_approve_by_rt   = $row['approve_by_rt'];
	            						$po_tgl_approved_rt = $row['tgl_approved_rt'];	  
	            						$po_comment_rt      = $row['comment_rt'];		

	            						$po_approve_by_hp   = $row['approve_by_hp'];
	            						$po_tgl_approved_hp = $row['tgl_approved_hp'];	  
	            						$po_comment_hp      = $row['comment_hp'];		

	            						$po_approve_by_dl   = $row['approve_by_dl'];
	            						$po_tgl_approved_dl = $row['tgl_approved_dl'];	  
	            						$po_comment_dl      = $row['comment_dl'];		
            						?>
            						<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
            						<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
            						<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>" >
            						<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>" >
									<!-- BOD_RT CHECKBOX  -->
            						<?php 
            							if ($user == BOD_RT)
            							{
            						?>
            						<td>
            							<?php 
            								//dummy data for checking checkbox
            								// $po_tgl_approved_dl = 1;
            								// $po_tgl_approved_hp = 1;
            								// $po_approve_by_hp =1 ;
            								// $po_approve_by_dl =1 ;
            								// $po_comment_hp = 'saya setuju';

            								if ($po_tgl_approved_rt==''){
            									if(!empty($po_tgl_approved_hp) || !empty($approve_by_hp) && !empty($po_tgl_approved_dl) || !empty($po_approve_by_dl)) {
            										echo '<input type="checkbox" disabled>';
	            								}else{
	            						?>
	            							<input type="checkbox" name="approve_by_rt" value="checked">
	            							<?php
	            								}
	            							?>
            						</td>
	            						<?php 
	            							}
	            						?>
            						<td>
            							<input type="checkbox" value="" disabled <?php if(!empty($po_approve_by_hp)){echo "checked";} ?> >
            						</td>
            						<td>
            							<input type="checkbox" disabled <?php if(!empty($po_approve_by_hp)){echo "checked";} ?> >
            						</td>
	            					<td>
	            						<textarea name="comment_rt" id="comment_rt" cols="15" rows="1">
	            						</textarea>
	            					</td>
	            					<?php 
	            						} else if ($user == BOD_HP) 
	            						{
	            					?>
	            					<td>
	            						<input type="checkbox" disabled <?php if(!empty($po_approve_by_rt)){echo "checked";} ?>>
	            					</td>
	            					<td>
	            						<?php 
            								if ($po_tgl_approved_hp=='' && $po_approve_by_hp==''){
            									if(!empty($po_tgl_approved_rt) || !empty($po_approve_by_rt)){
            										echo '<input type="checkbox" disabled>';
	            								}else{
	            						?>
	            							<input type="checkbox" name="approve_by_rt" value="checked">
	            							<?php
	            								}
	            							?>
	            					</td>
	            					<?php
	            						}
	            					 ?>
	            					 <td>
	            						<input type="checkbox" disabled <?php if(!empty($po_approve_by_rt)){echo "checked";} ?>>
	            					</td>
	            					<td>
	            						<textarea name="comment_hp" id="comment_hp" cols="15" rows="1"></textarea>
	            					</td>
	            					<!-- BOD_DL CHECKBOX -->
	            					<?php 
	            						} else {
	            							// dummy data for checking DL checkbox
	            							// $po_approve_by_rt =1 ;
	            					?>
	            					<td>
	            						<input type="checkbox" disabled <?php if(!empty($po_approve_by_rt)){echo "checked";} ?>>
	            					</td>
	            					<td>
	            						<input type="checkbox" disabled <?php if(!empty($po_approve_by_hp)){echo "checked";} ?>>
	            					</td>
	            					<td>
            							<?php 
            								if ($po_tgl_approved_dl==''){
            									if(!empty($po_tgl_approved_rt) || !empty($po_approve_by_rt)){
            										echo '<input type="checkbox" disabled>';
	            								}else{
	            						?>
	            							<input type="checkbox" name="approve_by_rt" value="checked">
	            							<?php
	            								}
	            							?>
            						</td>
	            						<?php 
	            							}
	            						?>
	            					<td>
	            						<textarea name="comment_dl" id="comment_dl" cols="15" rows="1"></textarea>
	            					</td>
	            					<?php
	            						} 
	            					?>
            					</tr>
            					<?php 
            						} 
            					?>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php 
		} 
	}
	?>
</body>
</html>
<script>
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