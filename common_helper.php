<?php
require_once (BASEPATH."../application/config/access.php");

	function pr($arr, $option="")
	{
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
		if ($option != "") {
			exit();
		}
	}

	function siteURL() {
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'] . '/';
		return $protocol . $domainName;
	}

    function format_date($date,$format='d/M/Y')
    {
        return date($format, strtotime($date));
    }

	function public_path($type="www")
	{
		return base_url()."public/";
	}

	function admin_path($type="www")
	{
		return base_url().'admin/';
	}

    function profile_img_path($type="www")
    {
        return base_url()."uploads/profile_images/";
    }

    function bk_logo_path($type="www")
    {
        return base_url()."../uploads/logo/";
    }
	
	function highlight_word( $content, $word, $color ) {
		//$replace = '<span style="background-color: ' . $color . ';">' . $word . '</span>'; 
		$replace = '<span class="sel-pill">' . $word . '</span>'; 
		// create replacement
		$content = str_replace($word, $replace, strtolower($content)); // replace content
		return $content; // return highlighted data
	}

	function getSetting($var,$db = false)
	{
		$CI =& get_instance();
		$setting = $CI->session->userdata('setting');
		if ($setting && !$db)
		{
			return $setting[$var];
		}
		else
		{
			$settings = $CI->common_model->selectData(SETTING, '*');
			$csettings = array();
			foreach($settings as $setting)
			{	
				$csettings[$setting->option_name] = $setting->option_field;
			}
			$CI->session->set_userdata('setting',$csettings);
			return $csettings[$var];
		}
	}
	function getCities()
	{

		$CI =& get_instance();
		$cities = $CI->common_model->selectData(CITIES, 'city_name',array(),"city_name","ASC");
		return $cities;
		
	}
	function getMyCredits()
	{		
		$CI =& get_instance();
		$user = $CI->session->userdata('user_session');
		$data = $CI->common_model->selectData("users", 'U_CREDITS', array('U_ID' => $user['id'] ));
		return $data[0]->U_CREDITS;
	}
    function is_front_login()
    {

        $CI =& get_instance();
        $session = $CI->session->userdata('front_session');

        if (!isset($session['id'])) {
            redirect(base_url());
        }
    }

	function success_msg_box($msg)
	{
		$html = '<div class="alert alert-success alert-dismissable">
                    <i class="fa fa-check"></i>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
                    '.$msg.'
                </div>';
        return $html;
	}

	function error_msg_box($msg)
	{
		$html = '<div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
                    '.$msg.'
                </div>';
        return $html;
	}

	function genHtmlDomAdmin($id,$field,$defvalue)
	{
		$html = "";
		$type = isset($field['type'])?$field['type']:"text";
		switch ($type)
		{
			case "text":
				$html = "<div class=\"form-group other-fields ".$id."\">";
				$html .= "<label>".$field["name"].":</label>";
				$html .= "<input type=\"text\" placeholder=\"".$field["placeholder"]."\" class=\"form-control validate[required]\" name=\"".$id."\" id=\"".$id."\" value=\"".@$defvalue."\">";
				$html .="</div>";
				break;
			case "radio":
				$values = $field['value'];
				$html = "<div class=\"form-group other-fields ".$id."\" >";
				$html .= "<label class=\"control-label col-sm-3\" for=\"text\">".$field["name"].":</label>";
				$html .= "<div class=\"\">";
				foreach ($values as $k=>$val)
				{
					$selected= ($k == @$defvalue)?"checked=\"checked\"":"";
					
					$html .= "<input type=\"radio\" class=\"\" name=\"".$id."\" id=\"".$val."_".$k."\" value=\"".$k."\" ".$selected." />";
					$html .= "<label class=\"control-label custom-label \" for=\"".$val."_".$k."\">".$val."</label>&nbsp;&nbsp;";
					
				}
				$html .="</div>";
				$html .="</div>";
				break;
		}
		return $html;
	}

	function genHtmlDom($id,$field,$defvalue)
	{
		$html = "";
		$type = isset($field['type'])?$field['type']:"text";
		switch ($type)
		{
			case "text":
				$html = "<div class=\"form-group other-fields ".$id."\" >";
				$html .= "<label class=\"control-label col-sm-3\" for=\"text\">".$field["name"].":</label>";
				$html .= "<div class=\"col-sm-6\">";
				$html .= "<input type=\"text\" placeholder=\"".$field["placeholder"]."\" class=\"form-control\" name=\"".$id."\" id=\"".$id."\" value=\"".@$defvalue."\">";
				$html .="</div>";
				$html .="</div>";
				break;
			case "radio":
				$values = $field['value'];
				$html = "<div class=\"form-group other-fields ".$id."\" >";
				$html .= "<label class=\"control-label col-sm-3\" for=\"text\">".$field["name"].":</label>";
				$html .= "<div class=\"form-inline\">";
				$html .= "<div class=\"col-sm-6\">";
				$html .= "<div class=\"form-group\">";
				$html .= "<div class=\"input-group\" style=\"margin-left:15px;\">";
				foreach ($values as $k=>$val)
				{
					$selected= ($k == @$defvalue)?"checked=\"checked\"":"";
					
					$html .= "<input type=\"radio\" class=\"\" name=\"".$id."\" id=\"".$val."_".$k."\" value=\"".$k."\" ".$selected." />";
					$html .= "<label class=\"control-label custom-label \" for=\"".$val."_".$k."\">".$val."</label>&nbsp;&nbsp;";
					
				}
				$html .="</div>";
				$html .="</div>";
				$html .="</div>";
				$html .="</div>";
				$html .="</div>";
				break;
		}
		return $html;
	}

	function get_active_tab($tab)
    {
    	$CI =& get_instance();
        if ($CI->router->fetch_class() == $tab) {
            return 'active';
        }
    }


    function sendEmail($to, $subject, $emailTpl, $from, $from_name, $cc='', $bcc=''){
        $CI =& get_instance();

        $CI->load->library('email');

		if(isset($from) && trim($from) != "" && $from_name != "")
			$CI->email->from($from, $from_name);
		else
			$CI->email->from(FROM_EMAIL, FROM_NAME);

        $CI->email->to($to);

        if($cc != ''){
            $CI->email->cc($cc);
        }

        if($bcc != ''){
            $CI->email->bcc($bcc);
        }

		//$CI->email->reply_to($from,$from_name);

        $CI->email->subject($subject);
        $CI->email->message($emailTpl);

		/*var_dump($result);
		echo '<br />';
		echo $this->email->print_debugger();
		*/

        $email_Sent = $CI->email->send();
		//echo $CI->email->print_debugger();pr($email_Sent,9);
        return $email_Sent;
    }

	function replace_char($str)
	{
		return str_replace(array(",","/","(",")","&","%"," ","@"),"-",trim($str));
	}


    function curl_request($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        return $response;
    }

	function hasAccess($class,$method = "index")
	{
		global $access;
		$CI =& get_instance();
		$user = $CI->session->userdata('user_session');
		
		$role = $user['U_ROLE'];
		$page = $access[$class][$method]; 

		if (!isset($user['U_ROLE'])) 
			return false;

		if (!in_array($role,$page))
			return false;

		return true;
	}

	function getNewImageName($imageName) {
        $imageName = strtolower($imageName);
        $paramArr = array();
        $extArr = explode('.', $imageName);
        $ext = array_pop($extArr);
        $tmpImgName = str_replace('.' . $ext, '', $imageName);
        $tmpImgName = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $tmpImgName);
        $newImageName = $tmpImgName . '_' . time() . '.' . $ext;
        return $newImageName;
    }

	function displayCategory($categories,$order,$root=true){
		if ($root)
			$html = "<ol id='categoryList' class='products-list product-list-in-box'>";
		else
			$html = "<ol>";

			foreach ($order as $o) {
				$category = $categories[$o['id']];
				$html .= '<li class="item" id ="cat-'.$category['cat_id'].'"><div class="clearfix" >
												<div class="product-img">
													<img alt="Product Image" src="'.image(category_img_path().$category['cat_image'],'cat_icon').'">
												</div>
												<div class="product-info">
													<a class="product-title" href="javascript:void(0);">'.$category['cat_name'].'</a>
														<span class="pull-right">
															<a href="'.site_url('/admin/category/edit/'.$category['cat_id']).'" class="fa fa-edit"></a>
															<a href="javascript:void(0);" onclick="delete_category('.$category['cat_id'].')" class="fa fa-trash-o"></a>
														</span>
													<span class="product-description">'.character_limiter($category['cat_title'],54).'</span>
												</div>
										  </div>';
					if (isset($o['children']))
						$html .=displayCategory($categories,$o['children'],false);

					$html .='</li>';
			}
		$html .= "</ol>";
		return $html;
	}
	
	function connectMobile($message,$phoneNumber)
	{
		$response = Unirest\Request::get("https://webaroo-send-message-v1.p.mashape.com/sendMessage?message=$message&phone=$phoneNumber",
		  array(
			"X-Mashape-Key" => "4kmFC7Hp0emshTbrd6RBSakzkVr2p1JjfELjsnJb0nqHVmzk6V"
		  )
		);
		  return $response;
	}

	function sendSMS($phoneNumber)
	{
		$password=mt_rand(10000, 99999);
		$message="Thanks to use Gujjubazar.Your Password : $password.Use this number as your password in login.";
		$response = connectMobile($message,$phoneNumber);
		$res=json_decode($response->raw_body,true);
		if($res['response']['status']=='success')
		{
			return array('status'=>'success','password'=>$password);
		}
		else
		{
			return array('status'=>'error','password'=>$password);
		}
	}

	function categoryDropdown ($categories=array(),$first=true,$idprefix="",$cateId='',$catename='')
	{
		if ($first)
		{
			$CI =& get_instance();
			$categories=$CI->common_model->getCategoryOrderedArray();
			$html = '<div class="dropdown" style="position:relative;width:100%;">
			<input type="hidden" name="category" id="sel_cat_id" value="'.$cateId.'">
				<a href="#" class="categoryListA dropdown-toggle " data-toggle="dropdown">';
					if($catename=='')
					$html.='<span id="categorySelName">Select Category</span> <span class="caret"></span>';
					else
					$html.='<span id="categorySelName">'.$catename.'</span> <span class="caret"></span>';
				$html.='</a>
				<ul class="dropdown-menu srchcatmenu">';
		}
		else
			$html = "<ul class='dropdown-menu sub-menu'>";

		foreach($categories as $category) {
			//echo "<pre>"; print_r($category);exit;
			$catid = str_replace("--","-",$idprefix.$category['category']['cat_id']."-");
			if (isset($category['children']))
			{
				$html .= '<li data-value="'.$catid.'"><a class="trigger right-caret">'.$category['category']['cat_name'].'</a>';
				$childern = array_merge(array($catid=>array("category"=>array("cat_id"=>"","cat_name"=>"All ".$category['category']['cat_name']))),$category['children']);
				
				$html .= categoryDropdown($childern,false,$catid);
				$html .="</li>";
			}
			else
			{
				$html .= '<li data-value="'.$catid.'"><a href="#">'.$category['category']['cat_name'].'</a></li>';	
			}
		}

		$html .= "</ul>";

		if ($first)
			$html .= "</div>";

		return $html;
	}

	function processAdImages($data)
	{
		$imgArr = $files = array();
		$fileParam = array('name', 'type', 'tmp_name', 'error', 'size');
		if (isset($data) && !empty($data)) {
				$files = $data;
				foreach ($files as $k => $v) {
					if (isset($v['name']) && !empty($v['name'])) {
						if (is_array($v['name'])) {
							foreach ($v['name'] as $p => $q) {
								if ($q == '') {
									foreach ($fileParam as $i)
										unset($files['images'][$i][$p]);
								}
							}
						}
					}
				}
				foreach ($fileParam as $i)
					$files['images'][$i] = array_values($files['images'][$i]);
			}
			for ($i = 0; $i < count($files['images']['name']); $i++) {
				$files['images']['name'][$i] = getNewImageName($files['images']['name'][$i]);
			}
			return $files;
	}
	function sendNotification($macid,$message)
	{
			$fields = array
			(
				'registration_ids' 	=> $macid,
				'data'			=> $message
			);
			 
			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
			if($result['success']==1)
			{
				return 1;
			}
			else
			{
				return 0;
			}
	}

	function download_send_headers($filename) {
    // disable caching
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");

		// force download  
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}	

	function array2csv(array &$array)
	{
	   if (count($array) == 0) {
		 return null;
	   }
		
		$operation = array(1=>"Created",2=>"Updated",3=>"Deleted");

	   ob_start();
	   $df = fopen("php://output", 'w');
	   //print_r(array_keys(reset($array)));exit;
	   //fputcsv($df, array_keys(reset($array)));
	   foreach ($array as $row) {

		  if (isset($row["Time"]))
			  $row["Time"] = date( 'jS M y h:i:s', strtotime($row["Time"]));

		  if (isset($row["Created At"]))
			  $row["Created At"] = date( 'jS M y h:i:s', strtotime($row["Created At"]));

		  if (isset($row["Operation"]))
			  $row["Operation"] = $operation[$row["Operation"]];

		  fputcsv($df, $row);
	   }
	   fclose($df);
	   return ob_get_clean();
	}

	function slugify($text)
	{ 
	  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	  $text = trim($text, '-');
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  $text = strtolower($text);
	  $text = preg_replace('~[^-\w]+~', '', $text);
	  if (empty($text))
	  {
		return '';
	  }
	  return $text;
	}

	function getFullName($name)
	{	
		$name = ucwords(substr($name,0,strpos($name," ")+2) . ".");
		return $name;
	}
	function replaceSpecialChar($arg,$replaceWith="")
	{
		$replaceArr=Array("+","#","&","/","\\","*","?","\"","\'","<",">","|",":"," ","'",".");
		$arg=str_replace($replaceArr,$replaceWith,$arg);
		return $arg;
	}
	###Function to create array from an object
	function makeArray($obj) {
		$arr = (array)$obj;
		if(empty($arr))
		{
			$arr = "";
		}
		else
		{
			foreach($arr as $key=>$value){
				if(!is_scalar($value)){
					$arr[$key] = makeArray($value);
				}
			}
		}
		return $arr;
	}
	
	### Format Phone for Consumer Display
	function format_phone($phone, $formatParam = ".", $extension=true,$theme='')
	{
		$formatParam ='-';
		$phone = trim($phone);
		$phone = str_replace ("-", "", $phone);
		$phone = str_replace (" ", "", $phone);
		$phone = str_replace ("(", "", $phone);
		$phone = str_replace (")", "", $phone);
		$phone = str_replace (".", "", $phone);
		$phone = str_replace ("X", "E", $phone);
		$phone = str_replace ("x", "E", $phone);

		$ext = "";
		if (stripos ($phone, "E") !== FALSE)
		{
			$tmpArr = explode ("E", $phone);
			$phone = $tmpArr[0];
			if ($tmpArr[1] != "")
				$ext = " Ext " . $tmpArr[1];
		}

		if (strlen($phone) == 11)
		{
			if ( $extension )
				return substr ($phone, 0, 1) . $formatParam . substr ($phone, 1, 3) . $formatParam . substr ($phone, 4, 3) . $formatParam . substr ($phone, 7) . $ext;
			else
				return substr ($phone, 0, 1) . $formatParam . substr ($phone, 1, 3) . $formatParam . substr ($phone, 4, 3) . $formatParam . substr ($phone, 7);

		}
		else if (strlen($phone) == 10)
		{
			if($theme=="cocoearly"){
				if ( $extension )
					return "(".substr ($phone, 0, 3) .") ".  substr ($phone, 3, 3) . $formatParam . substr ($phone, 6) . $ext;
				else
					return "(".substr ($phone, 0, 3) .") ".  substr ($phone, 3, 3) . $formatParam . substr ($phone, 6);
			}else{
				if ( $extension )
					return substr ($phone, 0, 3) . $formatParam . substr ($phone, 3, 3) . $formatParam . substr ($phone, 6) . $ext;
				else
					return substr ($phone, 0, 3) . $formatParam . substr ($phone, 3, 3) . $formatParam . substr ($phone, 6) ;

			}

		}
		else if (strlen($phone) == 7)
		{
			if ( $extension )
				return substr ($phone, 0, 3) . $formatParam . substr ($phone, 3, 3) . $ext;
			else
				return substr ($phone, 0, 3) . $formatParam . substr ($phone, 3, 3);

		}
		else if (strlen($phone) == 1)
		{
			return "";
		}
		else
		{
			return $phone;
		}
	}

	function getPropsite($bkArr=array())
	{
		$CI =& get_instance();
		//$id = 808;//MLS
		if(isset($id) && $id != "") {
			$propWhereArr = array("id"=>$id,"status"=>"1","enddate >="=>date("Y-m-d h:i:s"));
		} else {
			//$propSiteDomain = base_url();
			$propSiteDomain = str_replace("www.","",$_SERVER['HTTP_HOST']);
			$propWhereArr	= array("domain"=>$propSiteDomain,"status"=>"1","enddate >="=>date("Y-m-d h:i:s"));
		}

		//Broker Admin Preview Mode
		if(!empty($bkArr) && $bkArr['bkflag']==1) {
			$propSiteArr = $bkArr['propDetArr'];
		}
		else {
			/********************* GET PROSITE INFO *********************/
			$propDetailArr = $CI->common_model->selectDataArr($CI->common_model->cs_db,"propsite","*",$propWhereArr);
			if(count($propDetailArr) <= 0 || empty($propDetailArr) || $propDetailArr == null)
			{
				redirect('404');exit;
			}
			$propSiteArr = $propDetailArr[0];
			/********************* END - GET PROSITE INFO *********************/
			$CI->setStatsData(array("propId"=>$propSiteArr['id'],"userid"=>$propSiteArr['userid']));
		}

		/********************* GET AGENT/USER INFO FROM PROPSITE *********************/
		$agtSelFields = "id,fname,lname,address,city,state,zip,phone,mobile,email,leademail,photo,STATUS,role,agentsiteurl,designation,source,agentuid,logo";
		$agtWhereArr = array("id"=>$propSiteArr['userid']);
		$agtArr = $CI->common_model->selectDataArr($CI->common_model->cs_db,"user",$agtSelFields,$agtWhereArr);
		if(count($agtArr) <= 0 || empty($agtArr) || $agtArr == null)
		{
			redirect('404');exit;
		}
		$agentInfoArr = $agtArr[0];
		//pr($agentInfoArr);
		/********************* END - GET AGENT/USER INFO FROM PROPSITE *********************/

		/********************* GET BROKER INFO *********************/
		$mlsConnArr = "";
		$agtSocialUrl = array();
		if($agentInfoArr['agentuid'] != "")
		{
			$teraUserArr = $CI->common_model->getUserTB($agentInfoArr['agentuid'],"U_BKID");
			$brokerArr	 = $CI->common_model->getBroker($teraUserArr['U_BKID'],"BK_THEME,BK_ORG_NAME");

			/********************* Get Agent Social URLS *********************/
			$teraAgtInfo = $CI->common_model->getBrokerAgent($agentInfoArr['agentuid'],"BA_ID");
			//echo $aid  = $teraAgtInfo['BA_ID'];
			$agtSocialUrl= $CI->common_model->getAgentCommunityUrl($teraAgtInfo['BA_ID'],"*");

			//Merger Agent info with terabitz db
			$agentSite = $CI->common_model->outsourceUser($agentInfoArr['agentuid']);
			$agentInfoArr = (array_merge($agentInfoArr,$agentSite));
			/********************* END - Agent Social URLS *********************/
		}
		else {
			//External Agents
			$brokerArr = array("BK_THEME"=>"default","BK_ORG_NAME"=>"Terabitz Inc.");
			$datasourceName = array("name"=>$propSiteArr['datasource']);
			$mlsConnArr = $CI->common_model->selectDataArr($CI->common_model->cs_db,"datasource",'',$datasourceName,'priority','desc','',1);				
		}
		/********************* END - BROKER INFO *********************/

		/********************* GET PROPERTY BASIC INFO *********************/
		$setPropArr['mlsNo']    = $propSiteArr['mlsno'];
		$setPropArr['proptype'] = $propSiteArr['proptype'];
		$setPropArr['showprice']= $propSiteArr['showprice'];
		$CI->load->model('propsite_model');
		$CI->theme = $brokerArr['BK_THEME'];
		//pr($CI->propsite);exit;
		$CI->propsite_model->mlsConnArr = $mlsConnArr;
		$CI->propsite_model->loadDB($CI->theme);
		$propInfoArr = $CI->propsite_model->getPropertyInfo($setPropArr);
		if(count($propInfoArr) <= 0 || empty($propInfoArr) || $propInfoArr == null)
		{
			redirect('404');exit;
		}
		//Append User description with MLS Desc
		$propInfoArr['DESCRIPTION'] .= "<br /><br />".$propSiteArr['userdesc'];
		/********************* END - GET PROPERTY BASIC INFO *********************/

		/********************* GET PROPERTY MORE INFO *********************/
		$terabitzId = $propInfoArr['TERABITZ_ID'];
		$pktList	= $propInfoArr['PKTLIST'];
		$datasource = $propInfoArr['DATASOURCE'];
		$featurePtnfArr	= $CI->propsite_model->getFeatureDetails(array("terabitzId"=>$terabitzId,"pktList"=>$pktList,"datasource"=>$datasource,"propType"=>$propInfoArr['TYPE']));
		$photoArr	= $CI->propsite_model->getPhotoDetails($terabitzId,$pktList);		
		$userPtnfArr= $CI->propsite_model->getUsersPtnfDetails($terabitzId,$pktList);
		$mlsTerabitzId = "";
		if($pktList == "ENH")
			$mlsTerabitzId = $propInfoArr['MLS_TERABITZID'];
		$openHomesArr = $CI->propsite_model->getOpenHomesDetails($terabitzId,$pktList,$mlsTerabitzId);		
		/********************* END - GET PROPERTY MORE INFO *********************/

		/********************* GET EXTRA INFO *********************/
		$CI->load->library('areaAmenities');
		$areaAmenitiesArr = $CI->areaamenities->getAreaAmenitiesList();
		
		/*$CI->load->model('disclaimer');
		$CI->disclaimer->brokerName = $brokerArr['BK_ORG_NAME'];
		$CI->disclaimer->getDisclaimer($propInfoArr['DATASOURCE']);
		pr($CI->disclaimer->discliamerArr);exit;*/
		/********************* END - GET EXTRA INFO *********************/
		
		//Assign Theme Logo
		if($agentInfoArr['logo'] == "" || $agentInfoArr['logo'] == null)
			$agentInfoArr['logo'] = public_path()."img/propsite/".$brokerArr['BK_THEME']."/logo.png";

		//pr($propSiteArr);exit;
		//pr($featurePtnfArr);exit;
		//pr($agentInfoArr);exit;
		//pr($propInfoArr);exit;
		//pr($photoArr);exit;
		//pr($userPtnfArr);exit;
		//pr($openHomesArr);exit;
		//pr($areaAmenitiesArr);exit;
		//pr($agtSocialUrl);exit;
		$templateNo = "template".$propSiteArr['tempthemeid'];
		return array("propSiteArr"=>$propSiteArr,"featurePtnfArr"=>$featurePtnfArr,"agentInfoArr"=>$agentInfoArr,"propInfoArr"=>$propInfoArr,"photoArr"=>$photoArr,"userPtnfArr"=>$userPtnfArr,"openHomesArr"=>$openHomesArr,"templateNo"=>$templateNo,"theme"=>$brokerArr['BK_THEME'],"areaAmenitiesArr"=>$areaAmenitiesArr,"agtSocialUrl"=>$agtSocialUrl);
	}
	function getDispalyDatasource($datasource)
	{
		$datasource=strtolower($datasource);
		$teraMlsSourceMapping = array();
		$teraMlsSourceMapping['jonathan bowen'] = 'MLSPIN';
		$teraMlsSourceMapping['interometrolist'] = 'MetroList-Sacramento';
		$teraMlsSourceMapping['daar'] = 'Darien MLS';
		$teraMlsSourceMapping['snvmls'] = 'SYAOR';
		$teraMlsSourceMapping['scmls'] = 'CMLS';
		if(array_key_exists($datasource,$teraMlsSourceMapping))
			return $teraMlsSourceMapping[$datasource];
		else
			return strtoupper($datasource);

	}

	function feeStructure($caregory,$regtype)
	{
		$today_date = strtotime(date("Y-m-d"));
		//$today_date = strtotime(date("2017-10-20"));
		if($today_date < strtotime("2017-10-31")){
			$typeReg = "earlybird";
		}
		elseif(($today_date > strtotime("2017-11-01")) && ($today_date < strtotime("2017-11-28"))){
			$typeReg = "late";
		}
		else{
			$typeReg = "spot";			
		}

		$feeStruct = array();
		$feeStruct['workshop']['delegate'] = array("earlybird"=>"8000","late"=>"9000","spot"=>"10000");
		$feeStruct['workshop']['pg'] = array("earlybird"=>"5000","late"=>"5500","spot"=>"7000");
		$feeStruct['conference']['delegate'] = array("earlybird"=>"5000","late"=>"6000","spot"=>"8000");
		$feeStruct['conference']['pg'] = array("earlybird"=>"3500","late"=>"4000","spot"=>"4500");
		$feeStruct['conference']['accompanying'] = array("earlybird"=>"4000","late"=>"4500","spot"=>"5000");
		$feeStruct['combo']['delegate'] = array("earlybird"=>"11000","late"=>"13000","spot"=>"15000");
		$feeStruct['combo']['pg'] = array("earlybird"=>"7000","late"=>"8000","spot"=>"10000");

		if((isset($caregory) && $caregory != "") && (isset($regtype) && $regtype != "")){
			return $feeStruct[$regtype][$caregory][$typeReg];
		}
	}

	function TicketStructure()
	{
		
		$feeStruct = array('workshop'=>'Workshop','conference'=>'Conference','combo'=>'Combo');	
		return $feeStruct;
	
	}

	
	function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}

	function arrayTocsv($key,$result){

		$date=date('Y-m-d');		
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"Attendees-$date".".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen('php://output', 'w');
	    fputcsv($handle, $key);
		$i = 1;
		foreach ($result as $data) {
			fputcsv($handle, array($i, $data["ATD_FNAME"]."".$data["ATD_LNAME"], $data["ATD_EMAIL"], $data["ATD_T_NAME"], $data["ORD_REFERENCE"], $data["ATD_CREATED"]));
			$i++;
		}
		fclose($handle);
		exit;
	}

	function arrayToxls($key,$result){
		$filename = $_POST["ExportType"] . ".xls";       
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        ExportFile($data);
	}

	function arrayTohtml($key,$result){
		$html="";
		$html .='<table id="sheet0" class="sheet0 gridlines" cellspacing="0" cellpadding="0" border="0">
		<colgroup><col class="col0">
		<col class="col1">
		<col class="col2">
		<col class="col3">
		<col class="col4">
		<col class="col5">
		</colgroup>
		<tbody>';
			$html .='<tr class="row0">';
				
			foreach($key as $k=>$val){
				$html .='<td class="column'.$k.' style1 s">'.$val.'</td>';
			}
			$html .='</tr>';

				
			foreach($result as $k1=>$val1){
				$j=$k1+1;
				$html .='<tr class="row'.$j.'">';			
				//$html .='<td class="column'.$k1.' style0 s">'.$val1.'</td>';
				$html .='</tr>';
			
			}
		$html .='</tbody>';
		$html .='</table>';

		file_put_contents('test', $html);

	}

	function arrayToprint($key,$result){

	}

?>
