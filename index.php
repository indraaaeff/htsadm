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
		</nav>
	</div>
	<div class="spasi"></div>
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
			<!-- Alert -->
<!-- 		<div class="alert alert-success fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php 
				// $po_tgl_approved_rt = 1;
				if(empty($po_tgl_approved_rt) || empty($po_tgl_approved_hp) || empty($po_tgl_approved_dl))
				{
					echo "No Approval needed.";
				}				
			?>
		</div> -->
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
				<?php 
					if (!empty($notif)) {
						echo '<div class="alert alert-danger">Data ini sudah di proses.</div>';
					}
				?>
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
									<th class="tengah">STATUS</th>
								</tr>

							</thead>
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

							// $po_tgl_approved_rt="2/6/2016";
							// $po_approve_by_rt=1;
							// $po_tgl_approved_hp=1;
							// $po_approve_by_hp=0;
							// $po_tgl_approved_dl=1;
							// $po_comment_rt = "OKAY !";
							// $po_comment_hp = "saya sangat tidak setuju, karena alat yang lama masih mendukung kinerja tersebut";
						    // $po_comment_dl = "saya setuju karena, kita harus menggunakan teknologi terbaru guna mendukung kecepatan koneksi";
							?>
							<tbody>
								<!-- loop for row details -->
								<?php
								$no = +1;
								$grand= 0;
								while ($row = $PPO_TableDetail->fetch_assoc()) {

								?>
								<tr align="center" id="detail_column">
									<?php $Tanggal_po = date( 'd-m-Y', strtotime( $row['tgl_po'] )); ?>
            						<td><?php echo $no;?></td>
            						<td><?php echo $row['no_po'];?></td>
            						<td><?php echo $row['nama_vendor'];?></td>
            						<td><?php echo  $Tanggal_po;?></td>
            						<!-- <td style="display:none;"><?php echo $row['comment_rt'] ?></td> -->
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

            						<input  type="hidden"  name="po_tgl_app_hp[]" value="<?php echo $po_tgl_approved_hp;?>" >
            						<input  type="hidden"  name="po_app_hp[]"     value="<?php echo $po_approve_by_hp;?>" >
            						<input  type="hidden"  name="po_tgl_app_dl[]" value="<?php echo $po_tgl_approved_dl;?>" >
            						<input  type="hidden"  name="po_app_dl[]"     value="<?php echo $po_approve_by_dl;?>" >
            						<!-- BOD_RT Session CHECKBOX  -->
            						<?php 
										// $po_approve_by_hp = 1;
            						// $po_approve_by_dl = 1;
            						// $po_tgl_approved_hp = 'rejected';
            						// $po_approve_by_rt =1;
            						// $po_tgl_approved_rt =1;
            							if ($user == BOD_RT)
            							{
            						?>
            						<td id="check-box">
            							<?php 
            								//dummy data for checking checkbox
            								// $po_tgl_approved_rt=0;
            								// $po_approve_by_hp=1;
            								// $po_approve_by_dl=1;
            								// $po_tgl_approved_dl = 1;
            								// $po_tgl_approved_hp = 1;
            								// $po_approve_by_hp =1 ;
            								// $po_approve_by_dl =1 ;

            								if ($po_tgl_approved_rt==''){
            									if(!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
            										echo '<input type="checkbox" disabled>';
	            								}else{
	            						?>
	            							<input type="checkbox" class="tanggal" name="tanggal" id="chk<?php echo $no;?>" onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<!-- <input type="checkbox" name="approve_by_rt" onclick="check(this, 'date1');"> -->
	            							<?php
	            								}
	            							?>
	            							<input type="" name="po_tgl_approved_rt[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_rt;?>" readonly="readonly">
	            							<input type="" name="po_approve_by_rt[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_rt;?>" readonly="readonly">
	            							<input type="" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="" name="sub_by" value="<?php echo $by;?>">
	            							<input type="" name="comment_rt" value="<?php echo $comment_rt; ?>">
            						</td>
	            						<?php 
	            							} else {
	            								if (!empty($po_approve_by_rt)) {
	            									echo '<input type="checkbox" disabled checked>';
	            								} else {
	            									echo '<span class="glyphicon glyphicon-remove"></span>';
	            								}
	            							}
	            						?>
            						<td id="check-box">
            							<?php 
            								if($po_approve_by_hp==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if (empty($po_tgl_approved_hp)) {
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
										<span class="glyphicon glyphicon-remove"></span>
										<?php
            								}
            							?>
            						</td>
            						<td id="check-box">
            							<?php 
            								if($po_approve_by_dl==1) 
            								{ 
            							?>
										<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_dl)) {
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
            							<span title="" class="glyphicon glyphicon-remove"></span>
            							<?php		
            								}
            							?>
            						</td>
	            					<td>
	            						<!-- <p>Waiting</p> -->
	            						<?php 
	            							if(!empty($po_tgl_approved_rt)){
	            								if($po_approve_by_rt==1){
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:red;font-weight:bold;">Rejected</p>';
	            								}
	            							} else if(empty($po_tgl_approved_rt)){
	            								if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
	            								}
	            							}
	            						?>
	            					</td>
	            					<!-- BOD_HP Session CHECKBOX -->
	            					<?php 
	            						} else if ($user == BOD_HP) 
	            						{
	            					// $po_approve_by_rt = 1;
	            					// $po_tgl_approved_rt=1;
	            					// $po_approve_by_dl=1;
	            					// $po_tgl_approved_dl = 1;
	            					// $po_approve_by_hp= 1;
	            					// $po_tgl_approved_hp = 1;

	            					?>
	            					<td id="check-box">
										<?php 
            								if($po_approve_by_rt==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_rt)){
            							?>
            							<input type="checkbox" disabled>
            							<?php 
            								} else {
            							?>
            							<span class="glyphicon glyphicon-remove"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td id="check-box">
	            						<?php 
            								if(!empty($po_tgl_approved_hp)){
            									if (!empty($po_approve_by_hp)) {
            										echo '<input type="checkbox" disabled checked>';
            									} else {
            										echo '<span class="glyphicon glyphicon-remove"></span>';
            									}
	            							} else {
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<?php 
	            							}
	            							?>
	            							<input type="" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
	            							<input type="" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
	            							<input type="" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="" name="sub_by" value="<?php echo $by;?>">
	            							<input type="" name="comment_rt" value="<?php echo $comment_hp; ?>">
	            					
	            					</td>
	            					 <td id="check-box">
										<?php 
            								if($po_approve_by_dl==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked >
            							<?php 
            								} else if(empty($po_tgl_approved_dl)){
            							?>
            							<input type="checkbox" disabled>
            							<?php 
            								} else {
            							?>
            							<span class="glyphicon glyphicon-remove"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td>
	            						<!-- <p>Waiting</p> -->
	            						<?php 
	            							if(!empty($po_tgl_approved_rt)){
	            								if($po_approve_by_rt==1){
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:red;font-weight:bold;">Rejected</p>';
	            								}
	            							} else if(empty($po_tgl_approved_rt)){
	            								if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
	            								}
	            							}
	            						?>
	            					</td>
	            					<!-- BOD_DL CHECKBOX -->
	            					<?php 
	            						} else {
	            							// dummy data for checking DL checkbox
	            							// $po_approve_by_rt =1 ;
	            					?>
	            					<td id="check-box">
										<?php 
            								if($po_approve_by_rt==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if(empty($po_tgl_approved_rt)){
            							?>
            							<input type="checkbox" disabled>
            							<?php 
            								} else {
            							?>
            							<span class="glyphicon glyphicon-remove"></span>
            							<?php
            								}
            							?>
	            					</td>
	            					<td id="check-box">
            							<?php 
            								if($po_approve_by_hp==1) 
            								{ 
            							?>
            							<input type="checkbox" disabled checked>
            							<?php 
            								} else if (empty($po_tgl_approved_hp)) {
            							?>
            							<input type="checkbox" disabled>
            							<?php
            								} else {
            							?>
										<span class="glyphicon glyphicon-remove"></span>
										<?php
            								}
            							?>
	            					</td>
	            					<td id="check-box">
										<?php 
            								if(!empty($po_tgl_approved_hp)){
            									if (!empty($po_approve_by_hp)) {
            										echo '<input type="checkbox" disabled checked>';
            									} else {
            										echo '<span class="glyphicon glyphicon-remove"></span>';
            									}
	            							} else {
	            						?>
	            							<!-- <input type="checkbox" name="approve_by_hp"> -->
	            							<input type="checkbox" class="tanggal"  name="tanggal"  id="chk<?php echo $no;?>"
                                                    onClick="check(this, '<?php echo $no;?>'); check2(this, 'pp<?php echo $no;?>');" />
	            							<?php 
	            							}
	            							?>
	            							<input type="" name="po_tgl_approved_hp[]" class="tgl" id="<?php echo $no;?>"  value="<?php echo $po_tgl_approved_hp;?>" readonly>
	            							<input type="" name="po_approve_by_hp[]"  id="pp<?php echo $no;?>" value="<?php echo $po_approve_by_hp;?>" readonly>
	            							<input type="" name="no_po[]"  value="<?php echo $row['no_po'];?>">
	            							<input type="" name="total[]"  value="<?php echo $row['total'];?>">
	            							<input type="" name="tgl_po[]" value="<?php echo  date( 'd-m-Y', strtotime( $row['tgl_po'] ));?>">
	            							<input type="" name="nama_vendor[]" value="<?php echo $row['nama_vendor'];?>">
	            							<input type="" name="no_ppo" value="<?php echo $PPO_Number;?>">
	            							<input type="" name="sub_by" value="<?php echo $by;?>">
	            							<input type="" name="comment_rt" value="<?php echo $comment_hp; ?>">
            						</td>
	            					<td>
										<!-- <p>Waiting</p> -->
										<?php 
	            							if(!empty($po_tgl_approved_rt)){
	            								if($po_approve_by_rt==1){
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:red;font-weight:bold;">Rejected</p>';
	            								}
	            							} else if(empty($po_tgl_approved_rt)){
	            								if (!empty($po_approve_by_hp) && !empty($po_approve_by_dl)) {
	            									echo '<p style="color:green;font-weight:bold;">Approved</p>';
	            								} else {
	            									echo '<p style="color:orange;font-weight:bold;">Waiting</p>';
	            								}
	            							}
	            						?>
	            					</td>
	            					<?php
	            						} 
	            					?>
            					</tr>
            					<!-- Modal -->
            					<div class="modal fade" id="myModal" role="dialog">
            						<div class="modal-dialog modal-lg">
            							<!-- Modal content-->
            							<div class="modal-content" style="">
            								<div class="modal-header" style="background: #f26904;border-radius:5px;">
            									<button type="button" class="close" data-dismiss="modal" style="color:white;opacity:1;">&times;</button>
            									<h2 class="modal-title" style="color:white;">Approval Details</h2>
            								</div>
            								<div class="modal-body">
            									<div class="row">
            										<div class="col-md-6">
            											<h3><u>PO Details</u></h3>
            											<div class="form-group">
            												<label for="ppo_number">Nomor PO :</label>
            												<input class="form-control" type="text" id="ppo_number" value="<?php echo $row['no_po']; ?>" readonly>
            												<label>Tanggal PO :</label>
            												<input class="form-control "type="text" id="tanggal_po" value="<?php echo $Tanggal_po ?>" readonly>
            												<label>Nama Vendor :</label>
            												<input class="form-control "type="text" id="nama_vendor" readonly>
            											</div>
            										</div>
            										<div class="col-md-6">
            											<h3><u>BOD's Note</u></h3>
            								<!-- 			<label for="comment_rt">Richardus Teddy</label>
            											<textarea class="form-control" name="comment_rt[]" rows="5" <?php if($user == BOD_HP || $user == BOD_DL){echo "readonly";} ?>><?php if(!empty($po_comment_rt)){echo $po_comment_rt;} ?></textarea>
            											<label for="comment_hp">Harijanto Pribadi</label>
            											<textarea class="form-control" name="comment_hp[]" rows="5" <?php if($user == BOD_RT || $user == BOD_DL){echo "readonly";} ?>><?php if(!empty($po_comment_hp)){echo $po_comment_hp;} ?></textarea>
            											<label for="comment_dl">Dicky Lisal</label>
            											<textarea class="form-control" name="comment_dl[]" rows="5" <?php if($user == BOD_RT || $user == BOD_HP){echo "readonly";} ?>><?php if(!empty($po_comment_dl)){echo $po_comment_dl;} ?></textarea> -->
            										</div>
            									</div>
            								</div>
            								<div class="modal-footer">
            									<h5 style="float:left;font-weight:bold;">Submitted by <?php echo $by ?> </h5>
            									<button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
            								</div>
            							</div>
            						</div>
            					</div>
            					<!-- end of modal -->
            					<?php 
            						$no++;} 
            					?>
 								<!-- validation if BOD processed or not -->
            					<?php 
            					 if ( isset($_GET['notif']) )
            					 {
            					 	echo"<div class='alerts'> $_GET[notif] </div>";
            					 } else {
            					 	if ($user==BOD_RT) 
            					 	{
            					 		if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
            					 		{
            					 			if ( !is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp )   )
            					 			{
            					 				$appA= BOD_RT;
				              					$appB= BOD_HP;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_rt dan $po_tgl_approved_hp</div><br>                            
				              					</div>";	
				              				} 
				              				else if ( !is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl )   )
				              				{
				              					$appA= BOD_RT;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_rt dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				} 
				              				else { 
				              					$yang_approv=BOD_RT;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div> <div style='float:left;margin-left:10px;margin-right:10px;'> : </div> <div style=float:left;>$end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> <div style='float:left; margin-left:10px;margin-right:10px;'>:</div> <div style=float:left;>$po_tgl_approved_rt</div> </div>";
				              				}
				              			}   else  { 
				              				if ( (!is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp )) && (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))  )
				              				{
				              					$appA= BOD_HP;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				} else { 
				              	?>
				              					<tr>
				              						<td colspan="10">
				              							<input type="submit" class="btn btn-success submit" value="Submit">
				              							<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/> -->
				              						</td>		
				              					</tr>
				              	<?php 
				              				} 
				              			} 
				              		} 
				              		else if ($user==BOD_HP) 
				              		{
				              			if (!is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp ))
				              			{
				              				if (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))
				              				{
				              					$appA= BOD_HP;
				              					$appB= BOD_DL;
				              					$end_appA = ucwords($appA);
				              					$end_appB = ucwords($appB);
				              					echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'> Process By</div> 
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;>$end_appA dan $end_appB</div><br>
				              					<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
				              					<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>                            
				              					</div>";	
				              				}  else {  
				              					echo'<div class="alerts"><b>Data pengajuan PO ini telah selesai di proses</b></div>';
				              				}
				              			} else { 
				              				if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
				              				{
				              					$yang_approv=BOD_RT;
				              					$end_yang_app = ucwords($yang_approv);
				              					echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
				              					<div style='float:left; width:75px;'>Process By</div>
				              					<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
				              					<div style=float:left;> $end_yang_app</div> <br>
				              					<div style='float:left; width:75px;'> Tanggal</div> 
				              					<div style='float:left; margin-left:10px;margin-right:10px;'>:</div>
				              					<div style=float:left;> $po_tgl_approved_rt</div> </div>";
				              				} else {
				              	?>
								              	<tr>
								              		<td colspan="10">
								              			<input type="submit" class="btn btn-success submit" value="Submit">
								              			<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/></td> -->
								              		</tr>
                 				<?php   
                 							} 
                 						}
		            				} 
		            				else if ($user==BOD_DL)  
		            				{
		            					if (!is_null( $po_tgl_approved_dl ) || !empty( $po_tgl_approved_dl ))
		            					{
		            						if ( !is_null( $po_tgl_approved_hp ) || !empty( $po_tgl_approved_hp ) )
		            						{		            							
		            							$appA= BOD_HP;
		            							$appB= BOD_DL;
		            							$end_appA = ucwords($appA);
		            							$end_appB = ucwords($appB);
		            							echo"<div class='alerts' style='line-height:170%;'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'> Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_appA dan $end_appB</div><br>
		            							<div style='float:left; width:75px;'>Tanggal</div><div style='float:left;margin-left:10px;margin-right:10px;'>:</div>     
		            							<div style=float:left;> $po_tgl_approved_hp dan $po_tgl_approved_dl</div><br>          
		            							</div>";	
		            						} else {
		            							echo'<div class="alerts"><b>Data pengajuan PO ini telah selesai di proses</b></div>';
		            						}
		            					} else { 
		            						if (!is_null( $po_tgl_approved_rt ) || !empty( $po_tgl_approved_rt ))
		            						{
		            							$yang_approv=BOD_RT;
		            							$end_yang_app = ucwords($yang_approv);
		            							echo"<div class='alerts'><b>Data pengajuan PO ini telah selesai di proses</b><br>
		            							<div style='float:left; width:75px;'>Process By</div> 
		            							<div style='float:left;margin-left:10px;margin-right:10px;'> : </div> 
		            							<div style=float:left;>$end_yang_app</div> <br>
		            							<div style='float:left; width:75px;'> Tanggal</div> 
		            							<div style='float:left; margin-left:10px;margin-right:10px;'>:</div> 
		            							<div style=float:left;>$po_tgl_approved_rt</div> </div>";
		            						} else {
	                        	?>
	                        					<tr>
	                        						<td colspan="10">
	                        							<input type="submit" class="btn btn-success submit" value="Submit">
	                        							<!-- <input type="submit" name="submit" value="submit"  onClick="return confirm('Anda sudah yakin?')"/>-->
	                        						</td> 
	                        					</tr>
		              			<?php  
		              						} 
		              					}
									} else {
										//do something	
									} 
						 		?> 
								<?php 
								} 
								?>
								<!-- end of validation processed or not -->
							</tbody>
						</table>
					</form>

				</div>
			</div>
		</div>
	</div>
	<?php 
	// echo $user;
		} 
	}
	?>
	<footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2016 PT. Hawk Teknologi Solusi, All Rights Reserved.</p>
      </div>
    </footer>
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

$('tr #check-box').on('click',function(){
    $("#myModal").modal("show");
    $("#ppo_number").val($(this).closest('tr').children()[1].textContent);
    $("#tanggal_po").val($(this).closest('tr').children()[3].textContent);
    $("#nama_vendor").val($(this).closest('tr').children()[2].textContent);
});

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

			return year + "-" + month + "-" + day + " " + hourFormatted + ":" +
					minuteFormatted + ":" + second;
		}
		function check(cb, test)
		{
			if (cb.checked) {
			
				document.getElementById( test ).value = formatDate(new Date ());
			}
			else
				document.getElementById( test ).value = "";
		}
				function check2(cb2, test2)
		{
			if (cb2.checked) {
				
				document.getElementById( test2 ).value = '1';	
			}
			else
				document.getElementById( test2 ).value = "0";
		}
</script>