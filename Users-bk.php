<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller
{
    
    function __construct()
    {
        
        parent::__construct();
        $this->user_session = $this->session->userdata('user_session');
        date_default_timezone_set('Asia/Kolkata');
        $this->load->helper(array(
            'form'
        ));
        $this->load->library('form_validation');
		$this->current_url = admin_path()."users/";   
    }
    public function index()
    {
		$ticketDetails         = TicketStructure();        
        $allUserDetails         = $this->common_model->getAlluserattendeeDetails();
		$data['ticketDetails'] = $ticketDetails;        
        $data['view']           = "index";
        $data['allUserDetails'] = $allUserDetails;
		 if ($this->user_session['U_ROLE'] == 'S') {
				$attendeecnt=$this->common_model->getAttendeecnt();
				$data['attendeecnt'] = $attendeecnt;
				$userSponsorDetails=$this->common_model->getuserSponsorDetails($this->user_session['U_ID']);
				$userSponsorDetails=$userSponsorDetails[0];
				$data['userSponsorDetails'] = $userSponsorDetails;
         }
		  $data['userRole']=$this->user_session['U_ROLE'];
		
		 $this->load->view('admin/content', $data);
    }
    
    public function ajax_list($limit = 0)
    {
        $post = $this->input->post();
        $i    = 0;
        $columns = array(
            array(
                'db' => 'c.cat_name',
                'dt' => $i++
            ),
            array(
                'db' => 'c.cat_title',
                'dt' => $i++
            ),
            array(
                'db' => 'c.cat_theme',
                'dt' => $i++
            ),
            array(
                'db' => 'c.cat_id',
                'dt' => $i++,
                'formatter' => function($d, $row)
                {
                    $op = array();
                    if (hasAccess("category", "details"))
                        $op[] = '<a href="category/edit/' . $d . '" onclick="editCategory(' . $d . ');">Edit</a>';
                    $op[] = '<a href="javascript:void(0);" onclick="deleteCategory(' . $d . ');">Delete</a>';
                    return implode(" | ", $op);
                }
            )
        );
        //echo "<pre>";print_r($columns);exit;
        $join    = array();
        $where   = array();
        //	$where["u.role"] = "B";
        echo json_encode(SSP::simple($post, "faq_category c", "c.cat_id", $columns, $join, $where));
        exit;
    }
    
    
    public function edit()
    {
        $this->load->helper(array(
            'form'
        ));
        $this->load->library('form_validation');
        $post = $this->input->post();
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'required');
        }
        if ($this->input->post('password') != "") {
            $this->form_validation->set_rules('new_password_confirmation', 'Confirm Password', 'required|matches[new_password]');
            $this->form_validation->set_rules('new_password', 'New Password', 'required|matches[new_password_confirmation]');
            
        }
        
        $errors = $this->form_validation->error_array();
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'status' => 'error',
                'messages' => $this->form_validation->error_array()
            ));
            exit;
            
            
        } else {
            $currGmtDate = date('Y-m-d H:i:s');
            if ($this->input->post('password') != "") {
                $old_password_hash    = md5($this->input->post('password'));
                $where                = array(
                    'U_ID' => $post['user_id']
                );
                $user                 = $this->common_model->selectDataArr($this->common_model->cs_db, "users", '*', $where);
                $user                 = $user[0];
                // pr($user);
                $old_password_db_hash = $user['U_PASSWD'];
                
                if ($old_password_hash != $old_password_db_hash) {
                    echo json_encode(array(
                        'status' => 'error',
                        'messages' => array(
                            'password' => 'Password is not correct'
                        )
                    ));
                    exit;
                }
            }
            
            $inputArr = array();
            $inputArr = array(
                'U_FNAME' => $post['first_name'],
                'U_LNAME' => $post['last_name'],
                'U_EMAIL' => $post['email'],
                'U_UPDATED' => $currGmtDate
            );
            if (isset($post['new_password']) && $post['new_password'] != '') {
                $Pssdata  = array(
                    'U_PASSWD' => md5($this->input->post('new_password'))
                );
                $inputArr = array_merge($inputArr, $Pssdata);
            }
            $where    = array(
                'U_ID' => $post['user_id']
            );
            $updateId = $this->common_model->updateData($this->common_model->cs_db, "users", $inputArr, $where);
            if ($updateId > 0) {
                echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Successfully Saved Details'
                ));
                exit;
            }
        }
    }
    
    
    public function addattendee()
    {
        //$ticketDetails=$this->common_model->getUserTicketDetails();
        $ticketDetails         = TicketStructure();
        $data['ticketDetails'] = $ticketDetails;
        $html                  = '';
        $html .= $this->load->view('admin/users/addattendee', $data, TRUE);
        echo $html;
    }
    public function editAttendee($order_id, $user_id)
    {
		 $ticketDetails               = TicketStructure();
        $data['ticketDetails']       = $ticketDetails;
        $userAttendeeDetails         = $this->common_model->getuserattendeeDetails($order_id, $user_id);
        $userAttendeeDetails         = $userAttendeeDetails[0];
        $data['order_id']            = $order_id;
        $data['user_id']             = $user_id;
        // print_r($userAttendeeDetails);
        $data['userAttendeeDetails'] = $userAttendeeDetails;
        $html                        = '';
        $html .= $this->load->view('admin/users/editAttendee', $data, TRUE);
        echo $html;
    }
    
    public function inviteAttendees()
    {
        $html = '';
        $html .= $this->load->view('admin/users/inviteAttendees', '', TRUE);
        echo $html;
    }
    public function messageAll()
    {
        
        $ticketDetails               = TicketStructure();
        $data['ticketDetails']       = $ticketDetails;
        $html                        = '';
        $html .= $this->load->view('admin/users/messageAll', $data, TRUE);
        echo $html;
    }
    public function messageAttendee($att_id)
    {
        
        $ticketDetails               = TicketStructure();
        $data['ticketDetails']       = $ticketDetails;
        $AttendeeDetails         = $this->common_model->getAttendeedetails($att_id);
        $AttendeeDetails         = $AttendeeDetails[0];
        $data['att_id']             = $att_id;
		$data['email']             = $AttendeeDetails['ATD_EMAIL'];
        $data['AttendeeDetails'] = $AttendeeDetails;
        $html                        = '';
        $html .= $this->load->view('admin/users/messageAttendee', $data, TRUE);
        echo $html;
    }
    public function resendTicket()
    {
        $html = '';
        $html .= $this->load->view('admin/users/resendTicket', '', TRUE);
        echo $html;
    }
    public function downloadPdf()
    {
        $html = '';
        $html .= $this->load->view('admin/users/downloadPdf', '', TRUE);
        echo $html;
    }
    public function cancelAttendee($att_id)
    {
		$AttendeeDetails         = $this->common_model->getAttendeedetails($att_id);
        $AttendeeDetails         = $AttendeeDetails[0];
        $data['att_id']          = $att_id;
		$data['email']           = $AttendeeDetails['ATD_EMAIL'];
		$data['AttendeeDetails'] = $AttendeeDetails;
		 $html = '';
        $html .= $this->load->view('admin/users/cancelAttendee', $data, TRUE);
        echo $html;
    }
    
    public function orderView($order_id)
    {
		$orderDetails         = $this->common_model->getorderDetails($order_id);
		$orderDetails=$orderDetails[0];
		$data['orderDetails'] = $orderDetails;
        $html = '';
        $html .= $this->load->view('admin/users/orderView', $data, TRUE);
        echo $html;
    }
	public function orderReceived($order_id){
		$currGmtDate = date('Y-m-d H:i:s');
		$orderUpdateData = array(
			'ORD_UPDATED' => $currGmtDate,
			'ORD_PAYMENT_RECEIVED'=>1
		);
		$where           = array(
			'ORD_ID' => $order_id
		);
		$updateId        = $this->common_model->updateData($this->common_model->cs_db, 'orders', $orderUpdateData, $where);
		if ($updateId > 0) {
			 echo json_encode(array(
				'status' => 'success',
				'message' => 'Order Payment succesfully updated',
				'redirectUrl'=>$this->current_url
			));
			exit;
		} else {
			echo json_encode(array(
				'status' => 'error',
				'messages' => 'something worng'
			));
			exit;
		}

	}

	public function viewAccompany($order_id)
	{
		$accmpDetails         = $this->common_model->getaccompanyDetails($order_id);
		$data['accmpDetails'] = $accmpDetails;		
		$html = '';
        $html .= $this->load->view('admin/users/accompanyView', $data, TRUE);
        echo $html;
	}
    public function postAddattendee()
    {
        $post = $this->input->post();
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('ticket_id', 'Ticket', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'status' => 'error',
                'messages' => $this->form_validation->error_array()
            ));
            exit;
        } else {
            
            
            if (isset($post['user_id']) && $post['user_id'] != "" && $post['order_id'] != "") {
                $attendeeInfo = $this->common_model->attendeeInfo(trim($post['user_id']), trim($post['order_id']));
            }
            if (isset($attendeeInfo[0]['U_ID']) && !empty($attendeeInfo[0]['U_ID']) && isset($attendeeInfo[0]['ORD_ID']) && !empty($attendeeInfo[0]['ORD_ID'])) {
                $currGmtDate = date('Y-m-d H:i:s');
                $user_id     = $attendeeInfo[0]['U_ID'];
                $order_id    = $attendeeInfo[0]['ORD_ID'];
                $where       = "U_EMAIL = '" . $post['email'] . "' AND U_ROLE='C' AND U_ID!='" . $user_id . "'";
                
                //update data
                $orderUpdateData = array(
                    'ORD_UPDATED' => $currGmtDate
                );
                $where           = array(
                    'ORD_U_ID' => $user_id,
                    'ORD_ID' => $order_id
                );
                $updateId        = $this->common_model->updateData($this->common_model->cs_db, 'orders', $orderUpdateData, $where);
                
                $orderDeatilsUpdateData = array(
                    'ORD_T_NAME' => $post['ticket_id']
                    //'ORD_CAT_TYPE'=>$post['cat_type']
                );
                $where                  = array(
                    'ORD_U_ID' => $user_id,
                    'ORD_ID' => $order_id
                );
                $updateId               = $this->common_model->updateData($this->common_model->cs_db, 'order_details', $orderDeatilsUpdateData, $where);
                
                $attUpdateData = array(
                    'ATD_T_NAME' => $post['ticket_id'],
                    'ATD_CAT_TYPE' => $post['cat_type'],
                    'ATD_UPDATED' => $currGmtDate,
                    'ATD_FNAME' => $post['first_name'],
                    'ATD_LNAME' => $post['last_name'],
                    'ATD_EMAIL' => $post['email']
                );
                $whereatt      = array(
                    'ATD_U_ID' => $user_id,
                    'ATD_ORD_ID' => $order_id
                );
                $updateId      = $this->common_model->updateData($this->common_model->cs_db, 'attendees', $attUpdateData, $whereatt);
                
                if ($updateId > 0) {
					 echo json_encode(array(
                        'status' => 'success',
                        'message' => 'Successfully Updated Attendee',
						'redirectUrl'=>$this->current_url
                    ));
                    exit;
                } else {
                    echo json_encode(array(
                        'status' => 'error',
                        'messages' => 'something worng'
                    ));
                    exit;
                }
                
            } else {
                $where     = "U_EMAIL = '" . $post['email'] . "' AND U_ROLE='C'";
                $usersdata = $this->common_model->selectDataArr($this->common_model->cs_db, "users", '*', $where);
                if (count($usersdata) > 0) {
                    echo json_encode(array(
                        'status' => 'error',
                        'messages' => array(
                            'email' => 'This Email address is already exists'
                        )
                    ));
                    exit;
                }
                //insert users data
                //$newpassword = random_string('alnum', 8);
                if ($this->user_session['U_ROLE'] == 'S') {
                    
                    $addedBy = $this->user_session['U_ID'];
                } else {
                    $addedBy = 0;
                }
                $newpassword  = 'password';
                $currGmtDate  = date('Y-m-d H:i:s');
                $attendeeData = array(
                    'U_FNAME' => $post['first_name'],
                    'U_LNAME' => $post['last_name'],
                    'U_EMAIL' => $post['email'],
                    'U_LOGIN' => $post['email'],
                    'U_PASSWD' => md5($newpassword),
                    'U_ROLE' => 'C',
                    'U_CREATED' => $currGmtDate,
                    'U_ADDEDBY_ID' => $addedBy,
                    'U_ACTIVE' => '1'
                );
                $insId        = $this->common_model->insertData($this->common_model->cs_db, 'users', $attendeeData);
                if ($insId > 0) {
                    //insert users details data
                    $attendeeDetailsData = array(
                        'UD_FNAME' => $post['first_name'],
                        'UD_LNAME' => $post['last_name'],
                        'UD_UID' => $insId,
                        'UD_REGNO' => "RES" . $currGmtDate . $insId
                    );
                    $deatilsId           = $this->common_model->insertData($this->common_model->cs_db, 'users_details', $attendeeDetailsData);
                    
                    // Insert Order data  for tickets
                    
                    $ordersData = array(
                        'ORD_U_ID' => $insId,
                        'ORD_EVENT_ID' => 1,
                        'ORD_TOTAL_AMT' => 0,
                        'ORD_ST_ID' => 1,
                        'ORD_CREATED' => $currGmtDate,
                        'ORD_REFERENCE' => generateRandomString()
                    );
                    $orderId    = $this->common_model->insertData($this->common_model->cs_db, 'orders', $ordersData);
                    if ($orderId > 0) {
                        // Insert Order details data  for tickets
                        $ordersDetailsData = array(
                            'ORD_ID' => $orderId,
                            'ORD_T_NAME' => $post['ticket_id'],
                            'ORD_DTL_QTY' => 1,
                            'ORD_U_ID' => $insId,
                            'ORD_DTL_AMT' => 0,
                            'ORD_CAT_TYPE' => $post['cat_type']
                        );
                        $orderdetailsId    = $this->common_model->insertData($this->common_model->cs_db, 'order_details', $ordersDetailsData);
                        // Insert Order Attendess per tickets
                        $attendData        = array(
                            'ATD_U_ID' => $insId,
                            'ATD_EVT_ID' => 1,
                            'ATD_T_NAME' => $post['ticket_id'],
                            'ATD_ORD_ID' => $orderId,
                            'ATD_CREATED' => $currGmtDate,
                            'ATD_CAT_TYPE' => $post['cat_type'],
                            'ATD_FNAME' => $post['first_name'],
                            'ATD_EMAIL' => $post['email'],
                            'ATD_LNAME' => $post['last_name']
                        );
                        $attendeeId        = $this->common_model->insertData($this->common_model->cs_db, 'attendees', $attendData);
                        
                    }
                    if ($orderId != '') {
                        $where1['U_ID'] = $insId;
                        $selectfields   = '*';
                        $userInfo       = $this->common_model->selectDataArr($this->common_model->cs_db, 'users', $selectfields, $where1);
                        //echo "<pre>";print_r($userInfo);exit;
                        $toEmail        = $userInfo[0]['U_EMAIL'];
                    }
                    /*sending mail to user and terabitz support*/
                    if (isset($post['email_ticket']) && !empty($toEmail) && $post['email_ticket']==1 && $post['email_ticket']!='') {
                        
                        $subject  = "Your ticket for the event Priya 's Interenational confirance";
                        $Data     = array(
                            'useremail' => $userInfo,
                            'userInfo' => $userInfo
                        );
                        $emailTpl = $this->load->view('admin/users/attendeesEmail.php', $Data, true);
                        $mailSent = sendEmail($toEmail, $subject, $emailTpl, 'priya.khilosiya@kraffsoft.com', 'Priya Khilosiya');
                        if ($mailSent) {
                            $data["mailsentTouser"] = "1";
                        } else {
                            $data['mailsentTouser'] = "0";
                        }
                    }
					/*sending mail to user and terabitz support*/
                   
					/* start ticket of attendee*/

					/*$ticketTpl = $this->load->view('admin/users/attendeeTicket.php','', true);
					//$middleContentHtml="This is testing Priya This is testing Priya This is testing Priya This is testing Priya";
					$this->load->library('wkpdfhtml');
					$pdf = new Wkpdfhtml();
					$pdf->add_html($ticketTpl);
					$Ticketpdf=$pdf->renderPdf('1','1');*/
					/* end of ticket of attendee*/
					 
                    echo json_encode(array(
                        'status' => 'success',
                        'message' => 'Attenddes Added Succesfully',
						//'redirectUrl'=>$this->current_url
                    ));
                    exit;
                    
                } else {
                    echo json_encode(array(
                        'status' => 'error',
                        'messages' => 'something worng'
                    ));
                    exit;
                    
                }
                
            }
        }
    }
    
    public function postAllAttendeeEmailMessage()
    {	$post = $this->input->post();
		$where1['ATD_IS_CANCELED'] = 0;
		if($post['recipients']!="" && $post['recipients']!="all" ){
			$where1['ATD_T_NAME']=$post['recipients'];
        } 
		$selectfields   = '*';
		$attendeeInfo       = $this->common_model->selectDataArr($this->common_model->cs_db, 'attendees', $selectfields, $where1);
		
		foreach($attendeeInfo as $atd=>$val){
        // Sending email
			if($post['subject']!='')
			$subject  = $post['subject'];
			 if($post['message']!='')
			$message  = $post['message'];
			
			$data['message']=$message;
			$data['UserEmailid']=$this->user_session['U_EMAIL'];
			$data['UserName']=$this->user_session['U_FNAME']." ".$this->user_session['U_LNAME'];
				
			$toEmail        = $val['ATD_EMAIL'];
		   if (!empty($toEmail)) {
				$emailTpl = $this->load->view('admin/users/messageAttendeeEmail.php', $data, true);
				//echo $emailTpl;exit;
				$mailSent = sendEmail($toEmail, $subject, $emailTpl, $this->user_session['U_EMAIL'], $this->user_session['U_FNAME']." ".$this->user_session['U_LNAME']);
				if ($mailSent) {
					echo json_encode(array(
							'status' => 'success',
							'message' => 'Message Sent  Succesfully'
						));
						exit;
				} else {
					echo json_encode(array(
							'status' => 'error',
							'messages' => 'Message Not Sent'
						));
						exit;
				}
			}
		}
        
    }
    
    public function postAttendeeEmailMessage()
    {
        $post = $this->input->post();
		if($post['email']!='')
			$toEmail=$post['email'];
        /*sending mail to user and terabitz support*/
        if (!empty($toEmail)) {
            if($post['subject']!='')
            $subject  = $post['subject'];
			 if($post['message']!='')
            $message  = $post['message'];
            
			$data['message']=$message;
			$data['UserEmailid']=$this->user_session['U_EMAIL'];
			$data['UserName']=$this->user_session['U_FNAME']." ".$this->user_session['U_LNAME'];
            $emailTpl = $this->load->view('admin/users/messageAttendeeEmail.php', $data, true);
			//echo $emailTpl;exit;
            $mailSent = sendEmail($toEmail, $subject, $emailTpl, $this->user_session['U_EMAIL'], $this->user_session['U_FNAME']." ".$this->user_session['U_LNAME']);
            if ($mailSent) {
                echo json_encode(array(
                        'status' => 'success',
                        'message' => 'Message Sent  Succesfully'
                    ));
                    exit;
            } else {
                echo json_encode(array(
                        'status' => 'error',
                        'messages' => 'Message Not Sent'
                    ));
                    exit;
            }
        }
        
    }
	public function export($formate='xls'){
		$result = $this->common_model->getexportattendeeDetails();
		$key=array('No.','Name', 'Email', 'Ticket', 'Order ref','Created at');

		if($formate=='csv'){
			arrayTocsv($key,$result);
		}elseif($formate=='xls'){
			$this->load->library('excel');
			$R = new Excel;
			$R->stream('test',$result);
		
		}elseif($formate=='html'){
			arrayTohtml($key,$result);

		}elseif($formate=='print'){

		}

	}

	public function postCancel($att_id,$order_id){
		$post = $this->input->post();
		
		 $currGmtDate = date('Y-m-d H:i:s');
		 $attUpdateData = array(
				'ATD_IS_CANCELED' => '1',
				'ATD_UPDATED' => $currGmtDate,
			);
			 if(isset($post['refund_attendee']) && $post['refund_attendee']!='' && $post['refund_attendee']=='1'){
				 $refdata  = array(
						'ATD_IS_REFUNDED' => '1',
					);
					$attUpdateData = array_merge($attUpdateData, $refdata);
			 }
			$whereatt      = array(
				'ATD_ID' => $att_id,
				'ATD_ORD_ID' => $order_id
			);
			$updateId      = $this->common_model->updateData($this->common_model->cs_db, 'attendees', $attUpdateData, $whereatt);
			if(isset($post['refund_attendee']) &&  $post['refund_attendee']!='' && $post['refund_attendee']=='1'){
				$ordersData = array(
					'ORD_TOTAL_AMT' => 0,
					'ORD_ST_ID' => 2,
					'ORD_UPDATED' => $currGmtDate,
					'ORD_CANCELED' => 1,
					'ORD_REFUNDED' => 1,
				);

				$where      = array(
					'ORD_ID' => $order_id
				);
				$orderupId    = $this->common_model->updateData($this->common_model->cs_db, 'orders', $ordersData, $where);
				if ($orderupId > 0) {
					// Insert Order details data  for tickets
					$ordersDetailsData = array(
						'ORD_ID' => $order_id,
						'ORD_DTL_AMT' => 0,
					);
					$where      = array(
						'ORD_ID' => $order_id
					);
					$orderdetailsId    = $this->common_model->updateData($this->common_model->cs_db, 'order_details', $ordersDetailsData, $where);
			
				}
			}
			if(!isset($post['notify_attendee']) && $updateId> 0){
				echo json_encode(array(
								'status' => 'success',
								'message' => 'Attendee cancel  Succesfully',
								'redirectUrl'=>$this->current_url
							));
							exit;
			}
			if(isset($post['notify_attendee']) && $post['notify_attendee']!='' && $post['notify_attendee']=='1'){
				$toEmail=$post['attendee_email'];
				/*sending mail to user and terabitz support*/
				if (!empty($toEmail)) {
					$subject  = "You're ticket has been cancelled";					
					
					$data['UserEmailid']=$this->user_session['U_EMAIL'];
					$data['UserName']=$this->user_session['U_FNAME']." ".$this->user_session['U_LNAME'];
					$emailTpl = $this->load->view('admin/users/cancelAttendeeEmail.php', $data, true);
					//echo $emailTpl;exit;
					$mailSent = sendEmail($toEmail, $subject, $emailTpl, $this->user_session['U_EMAIL'], $this->user_session['U_FNAME']." ".$this->user_session['U_LNAME']);
					if ($mailSent) {
						echo json_encode(array(
								'status' => 'success',
								'message' => 'Attendee cancel  Succesfully',
								'redirectUrl'=>$this->current_url
							));
							exit;
					} else {
						echo json_encode(array(
								'status' => 'error',
								'messages' => 'Attendee not cancel'
							));
							exit;
					}
				}
			}
	}
    
}