<?php
define ('BOD_RT','richardus teddy');
define ('BOD_HP','harijanto pribadi');
define ('BOD_DL','dicky lisal');
define ('BOD','bod@hts.net.id');
define ('BOD_RT_ID','01');
define ('BOD_HP_ID','02');
define ('BOD_DL_ID','03');
?>
<!-- comment RT -->
<?php 
	if (!empty($po_tgl_approved_rt)) {
		echo "readonly";
	} else {
		if (!empty($po_tgl_approved_hp)&&!empty($po_tgl_approved_dl)) {
			if ($po_approve_by_hp==1 && $po_approve_by_dl==1) {
				echo "readonly";
			}else if ($po_approve_by_hp==0 && $po_approve_by_dl==0){
				echo "readonly";
			}else{
				echo "";
			}
		} else {
			echo "";
		}
	}
?>
<!-- comment hp -->
<?php 
	if (!empty($po_tgl_approved_rt)) {
		echo "readonly";
	} else {
		if (!empty($po_tgl_approved_hp)) {
			echo "readonly";
		} else {
			echo "";
		}
	}
?>
<?php if (!empty($po_tgl_approved_rt)) {echo "readonly";} else {if (!empty($po_tgl_approved_hp)) {echo "readonly";} else {echo "";}}?>