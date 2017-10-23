	<script src="<?=public_path()?>js/admin/jquery.min.js" type="text/javascript"></script>


<script type="text/javascript">
	var method="<?php echo $this->router->fetch_method();?>";
	function admin_path () {
		return '<?=admin_path()?>';
	}

	function success_msg_box (msg) {
		var html = '<div class="alert alert-success alert-dismissable"> \n\
						<i class="fa fa-check"></i> \n\
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> \n\
						'+msg+' \n\
					</div>';
		return html;
	}

	function error_msg_box(msg)
	{
		var html = '<div class="alert alert-danger alert-dismissable"> \n\
						<i class="fa fa-ban"></i> \n\
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> \n\
						'+msg+' \n\
					</div>';
		return html;
	}

	function IsEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	function isNumberKey(e) {
		var charCode = (e.which) ? e.which : e.keyCode
		console.log(charCode);
		if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 36 || charCode > 41))
			return false;
		return true;
	}
</script>

<script type="text/javascript">
$(document).ready(function(){
	$("#userprofile").validationEngine();
});
</script>

<?php if (in_array($this->router->fetch_method(), array("add","edit"))) { ?>
	
<?php }else {?>
	<script src="<?=public_path()?>js/admin/<?=$this->router->fetch_class()?>/index.js" type="text/javascript"></script>
<?php } ?>
<script src="<?=public_path()?>js/btvalidationEngine.js" type="text/javascript"></script>
<script src="<?=public_path()?>js/btvalidationEngine-en.js" type="text/javascript"></script>

<script src="<?=public_path()?>js/Conference/conference.js" type="text/javascript"></script>
 
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
 <script src="<?=public_path()?>js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/datatables/jquery.dataTables.columnFilter.js" type="text/javascript"></script>

<!--<script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>-->

<script src="<?=public_path()?>vendor/geocomplete/jquery.geocomplete.min.js"></script>

<script src="<?=public_path()?>vendor/moment/moment.js"></script>

<script src="<?=public_path()?>vendor/fullcalendar/dist/fullcalendar.min.js"></script>

    

	

