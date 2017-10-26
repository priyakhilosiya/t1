<center>
	<form method="post" name="redirect" action="<?php echo $payment_url;?>"> 
		<input type="text" name="encRequest" value="<?php echo $encrypted_data;?>">
		<input type="text" name="access_code" value="<?php echo $access_code;?>">
	</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
	