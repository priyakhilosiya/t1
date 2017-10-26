<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {

	public function index()
	{
		date_default_timezone_set('Asia/Kolkata');
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		$eventId = "1";

		if($this->input->post('regSubmit'))
		{
			/* Set validation rule for name field in the form */ 
			$this->form_validation->set_rules('ufname', 'First Name', 'required'); 
			$this->form_validation->set_rules('ulname', 'Last Name', 'required'); 
			$this->form_validation->set_rules('uemail', 'Email', 'required|valid_email'); 
			$this->form_validation->set_rules('upassword', 'Password', 'required');
			$this->form_validation->set_rules('ucnfmpassword', 'Confirm Password', 'required|matches[upassword]');

			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata("stage","1");
				$data["view"] = "registration";
				$this->load->view("content", $data);
			}
			else
			{
				$passwd = $this->input->post('upassword');
				$cfmpasswd = $this->input->post('ucnfmpassword');
				if(isset($passwd) && isset($cfmpasswd) && $passwd==$cfmpasswd)
				{
					$fname = strip_tags($this->input->post('ufname'));
					$lname = strip_tags($this->input->post('ulname'));
					$email = strip_tags($this->input->post('uemail'));
					$whereExist = array('U_LOGIN'=>strip_tags($this->input->post('uemail')));
					$chkExist = $this->common_model->selectDataArr($this->common_model->cs_db,'users','',$whereExist);

					//pr($chkExist);exit;
					if(count($chkExist) > 0)
					{
						$wherePassExist = array('U_LOGIN' => strip_tags($this->input->post('uemail')),'U_PASSWD'=>md5($passwd));
						$chkPassExist = $this->common_model->selectDataArr($this->common_model->cs_db,'users','',$wherePassExist);
						//pr($chkPassExist);exit;
						if(count($chkPassExist) > 0)
						{
							$userData = array(
								'UD_UID' => $chkPassExist[0]['U_ID'],
								'UD_FNAME' => ucfirst($chkPassExist[0]['U_FNAME']),
								'UD_LNAME' => ucfirst($chkPassExist[0]['U_LNAME']),
								'UD_EMAIL' => $chkPassExist[0]['U_EMAIL'],
								'U_ROLE' => $chkPassExist[0]['U_ROLE'],
								'EVENTID' => $eventId
							);
							//pr($userData);exit;
							$this->session->set_userdata('user_session',$userData);
							$error = "Email is already registered with our system. please go ahead with next process.";
							$this->session->set_flashdata("error_msg", $error);
						}
						else
						{
							$error = "Email is already registered with our system. may be you forgot the password.";
							$this->session->set_flashdata("error_msg", $error);
							redirect("registration");
						}
					}
					else
					{
						$dateTimeNow = date("Y-m-d H:i:s");
						$userData = array(
							'U_LOGIN' => $email,
							'U_FNAME' => ucfirst($fname),
							'U_LNAME' => ucfirst($lname),
							'U_EMAIL' => $email,
							'U_PASSWD' => md5($this->input->post('upassword')),
							'U_ROLE' => "C",
							'U_APPROVED_ID' => "0",
							'U_CREATED' => $dateTimeNow,
							'U_ADDEDBY_ID' => "0",
							'U_ACTIVE' => "0"
						);					
						$insert = $this->common_model->insertData($this->common_model->cs_db,'users',$userData);
						//$insert = 2;
						if($insert != "" && $insert > 0)
						{
							$resdate = date("Ym");
							$userDetailData = array(
								'UD_UID' => $insert,
								'UD_REGNO' => "RES".$resdate.$insert,
								'UD_FNAME' => ucfirst($fname),
								'UD_LNAME' => ucfirst($lname),
								'UD_EMAIL' => $email
							);
							$insUserDtl = $this->common_model->insertData($this->common_model->cs_db,'users_details',$userDetailData);
							if($insert != "" && $insert > 0)
							{
								$userDetailData['EVENTID'] = $eventId;
								$this->session->set_userdata('user_session',$userDetailData);
								$this->load->model("user_registration");
								$mail = $this->user_registration->send_mail($userDetailData,"","registered");
								if($mail == "1")
								{
									$this->session->set_flashdata("error_msg", "You are registered.  please go ahead with next process.");
								}
								else
								{
									$this->session->set_flashdata("error_msg", "Mail sent failed !!");
								}
							}
							else
							{
								$this->session->set_flashdata("error_msg", "Error in registration !!!");
							}
						}
						else
						{
							$this->session->set_flashdata("error_msg", "Error in registration !!");
						}
					}
					$this->session->set_flashdata("stage","2");
				}
				redirect("registration/ticket");
				//$data["view"] = "registration/ticket";
				//$this->load->view("content", $data);
			}
		}
		else{
			$this->session->set_flashdata("stage","1");
			$data["view"] = "registration";
			$this->load->view("content", $data);
		}
	}

	public function ticket()
	{
		date_default_timezone_set('Asia/Kolkata');
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		//pr($_REQUEST);exit;
		if(isset($_REQUEST['op']) && ($_REQUEST['op']== "ticketinfo"))
		{
			$this->form_validation->set_rules('ucategory', 'Category', 'required'); 
			$this->form_validation->set_rules('uregType', 'Registration Type', 'required'); 
			if($this->form_validation->run() == FALSE)
			{
				$data["view"] = "ticket";
				$this->load->view("content", $data);
			}
			else
			{
				$totalOrder = "0";
				if(isset($_REQUEST['userid']) && ($_REQUEST['userid'] != ""))
					$userid = $_REQUEST['userid'];
				if(isset($_REQUEST['ucategory']) && ($_REQUEST['ucategory'] != ""))
					$ucategory = $_REQUEST['ucategory'];
				if(isset($_REQUEST['uregType']) && ($_REQUEST['uregType'] != ""))
					$uregType = $_REQUEST['uregType'];

				$feeStrArr = feeStructure($ucategory, $uregType);
				//pr($feeStrArr);exit;
				$orderData = array();
				$orderData['userid'] = $userid;
				$orderData['ucategory'] = $ucategory;
				$orderData['uregType'] = $uregType;
				$orderData['uregFee'] = $feeStrArr;
				
				$totalOrder = $totalOrder+$orderData['uregFee'];

				//pr($_REQUEST);exit;
				$flag = 1;
				if(isset($_REQUEST['acmpArr']) && (count($_REQUEST['acmpArr']) > 0) && $uregType == 'conference')
				{
					$acmpArr = $_REQUEST['acmpArr'];
					$accRegFee = feeStructure("accompanying","conference");
					foreach($acmpArr as $key => $val){
						$tData = explode(",",$val);
						if(count($tData) > 0)
						{
							if(isset($tData[0]) && $tData[0] == ""){
								$flag = 0;
								$this->session->set_flashdata("error_msg", "Name field is required");
							}
							elseif(!filter_var($tData[1], FILTER_VALIDATE_EMAIL)){
								$flag = 0;
								$this->session->set_flashdata("error_msg", "Email field is required \n Invalid email address");
							}
							if(!preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',$tData[2])){
								$flag = 0;
								$this->session->set_flashdata("error_msg", "Invalid phone number");
							}
							else{
								$accompanyData = array(
									'FULLNAME' => ucfirst($tData[0]),
									'EMAIL' => $tData[1],
									'PHONE' => $tData[2],
									'ACCREGFEE' => $accRegFee
								);
								$totalOrder = $totalOrder+$accompanyData['ACCREGFEE'];
								$orderData['accompanyData'][$key] = $accompanyData;
								//pr($accompanyData);exit;
							}
						}
					}
				}
				$orderData['totalOrder'] = $totalOrder;
				//pr($orderData);exit;
				$this->session->set_userdata('orderdata',$orderData);
				if($flag==1)
					echo "orderpage";
			}
		}
		else
		{
			$data["view"] = "ticket";
			$this->load->view("content", $data);
		}
	}

	public function order()
	{
		date_default_timezone_set('Asia/Kolkata');

		if($this->input->post('orderSubmit'))
		{
			$userdata = $this->session->userdata('user_session');
			$orderdata = $this->session->userdata('orderdata');

			if((isset($userdata) && count($userdata) > 0) && (isset($orderdata) && count($orderdata) > 0))
			{
				$catTypeArr = array("delegate"=>"D","pg"=>"PG","accompanying"=>"A");
				//echo $catTypeArr[$orderdata['ucategory']];exit;

				// insert date to order table for order person
				$dateTimeNow = date("Y-m-d H:i:s");
				$orderData = array(
					'ORD_ST_ID' => "5",
					'ORD_U_ID' => $userdata['UD_UID'],
					'ORD_EVENT_ID' => $userdata['EVENTID'],
					'ORD_TOTAL_AMT' => $orderdata['totalOrder'],
					'ORD_CREATED' => $dateTimeNow,
					'ORD_REFERENCE' => generateRandomString()
				);
				$orderInsert = $this->common_model->insertData($this->common_model->cs_db,'orders',$orderData);
				//$orderInsert = "-1"; 
				if($orderInsert != "" && $orderInsert > 0)
				{
					// insert date to order details table for order person
					$orderDtlData = array(
						'ORD_ID' => $orderInsert,
						'ORD_U_ID' => $userdata['UD_UID'],
						'ORD_T_ID' => "0",
						'ORD_DTL_QTY' => $userdata['EVENTID'],
						'ORD_DTL_AMT' => $orderdata['uregFee'],
						'ORD_T_NAME' => $orderdata['uregType'],
						'ORD_CAT_TYPE' => $catTypeArr[$orderdata['ucategory']]
					);
					$orderDtlInst = $this->common_model->insertData($this->common_model->cs_db,'order_details',$orderDtlData);
					if(isset($orderdata['accompanyData']) && count($orderdata['accompanyData']) > 0)
					{
						// insert data to accompany table for accompanying person
						foreach($orderdata['accompanyData'] as $accKey => $accVal)
						{
							$accData = array(
								'ACMP_U_ID' => $userdata['UD_UID'],
								'ACMP_ORD_ID' => $orderInsert,
								'ACMP_FULLNAME' => $accVal['FULLNAME'],
								'ACMP_EMAIL' => $accVal['EMAIL'],
								'ACMP_PHONE' => $accVal['PHONE'],
								'ACMP_CREATED' => $dateTimeNow
							);
							$accDataInst = $this->common_model->insertData($this->common_model->cs_db,'accompany',$accData);
						}
						// insert data to order details table for accompanying person
						foreach($orderdata['accompanyData'] as $accKey => $accVal)
						{
							$accOrderDtlData = array(
								'ORD_ID' => $orderInsert,
								'ORD_U_ID' => $userdata['UD_UID'],
								'ORD_T_ID' => "0",
								'ORD_DTL_QTY' => "1",
								'ORD_DTL_AMT' => $accVal['ACCREGFEE'],
								'ORD_T_NAME' => $orderdata['uregType'],
								'ORD_CAT_TYPE' => $catTypeArr['accompanying']
							);
							$accDtlInst = $this->common_model->insertData($this->common_model->cs_db,'order_details',$accOrderDtlData);
						}
					}
					// insert data to attendees table for order person
					$atndData = array(
						'ATD_U_ID' => $userdata['UD_UID'],
						'ATD_EMAIL' => $userdata['UD_EMAIL'],
						'ATD_FNAME' => $userdata['UD_FNAME'],
						'ATD_LNAME' => $userdata['UD_LNAME'],
						'ATD_EVT_ID' => $userdata['EVENTID'],
						'ATD_T_ID' => "0",
						'ATD_ORD_ID' => $orderInsert,						
						'ATD_CREATED' => $dateTimeNow,
						'ATD_T_NAME' => $orderdata['uregType'],
						'ATD_CAT_TYPE' => $catTypeArr[$orderdata['ucategory']]
					);
					$atndInst = $this->common_model->insertData($this->common_model->cs_db,'attendees',$atndData);
				}

				
				/*print_r($userdata);
				print_r($orderdata);

				exit;*/
				
				/** Start Payment transaction details*/
				$payment_data=array();
				$payment_data["tid"]= time();
				$payment_data["merchant_id"]= MERCHANT_ID;
				$payment_data["order_id"]= $orderInsert;
				$payment_data["amount"]= $orderdata['totalOrder'];
				$payment_data["currency"]= "INR";
				$payment_data["redirect_url"]= base_url().PAYMENT_REDIRECT_URL;
				$payment_data["cancel_url"]= base_url().PAYMENT_CANCEL_URL;
				$payment_data["language"]= "EN";

				$payment_data["billing_name"]= $userdata['UD_FNAME']." ".$userdata['UD_LNAME'];																
				/*$payment_data["billing_address"]= $userdata["a_address"];																
				$payment_data["billing_city"]= $userdata["a_city"];
				$payment_data["billing_state"]= $userdata["a_state"];																
				$payment_data["billing_zip"]= $userdata["a_zip"];																
				$payment_data["billing_country"]= $userdata["a_country"];																
				$payment_data["billing_tel"]= $session["c_phone"];	*/															
				$payment_data["billing_email"]= $userdata['UD_EMAIL'];																

				$payment_data["delivery_name"]= $userdata['UD_FNAME']." ".$userdata['UD_LNAME'];																
				/*$payment_data["delivery_address"]= $userdata["a_address"];																
				$payment_data["delivery_city"]= $userdata["a_city"];																
				$payment_data["delivery_state"]= $userdata["a_state"];																
				$payment_data["delivery_zip"]= $userdata["a_zip"];																
				$payment_data["delivery_country"]= $userdata["a_country"];	*/															
				$payment_data["delivery_tel"]= $userdata['UD_EMAIL'];


				$merchant_data=urldecode(http_build_query($payment_data));
				$data['access_code']=ACCESS_CODE;
				$data['payment_url']=PAYMENT_URL;
				$data['encrypted_data']=encrypt($merchant_data,WORKING_KEY);
				
				$data["view"] = "paymentForm";
				$this->load->view("content", $data);

				/** End of Payment transaction details*/



				/*$orderDtlArr = array_merge($userdata, $orderdata);				

				$this->load->model("user_registration");
				$mail = $this->user_registration->send_mail($orderDtlArr,"UROGYNAEC | Order Details","orderdetails");
				
				if($mail == "1")
				{
					$this->session->unset_userdata('user_session');
					$this->session->unset_userdata('orderdata');
					$this->session->set_flashdata("error_msg", "UROGYNAEC | Order Done");
					//redirect("registration/done");
				}
				else
				{
					$this->session->set_flashdata("error_msg", "Mail sent failed !!");
				}	*/			
			}
			else
			{
				$data["view"] = "registration";
				$this->load->view("content", $data);
			}
		}
		else
		{
			$data["view"] = "order";
			$this->load->view("content", $data);
		}
	}

	public function done()
	{
			$data["view"] = "done";
			$this->load->view("content", $data);
	}

	
}
