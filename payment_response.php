<?php
		$post = $this->input->post();
		pr($post);
		if(!isset($post["encResp"])) exit;

		$encResponse=$post["encResp"];
		$rcvdString=decrypt($encResponse,WORKING_KEY);
		$order_status="";
		$decryptValues=explode('&', $rcvdString);
		pr($decryptValues);pr($_SESSION,3);
		$o_response= array();
		foreach($decryptValues as $v)
		{
			$s=explode('=',$v);
			$o_response[$s[0]] = $s[1];
		}
		unset($o_response["bin_country"]);
		unset($o_response["bin_supported"]);
		//print_r($o_response);exit;
		$this->common_model->insertData($this->common_model->cs_db,'payment_info',$o_response);

?>