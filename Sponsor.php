<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sponsor extends CI_Controller {

	function __construct(){
		
		parent::__construct();
        $this->user_session = $this->session->userdata('user_session');
        date_default_timezone_set('Asia/Kolkata');
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		$this->current_url = admin_path()."sponsor/"; 

	}

	public function index()
	{
		$allSponsorDetails=$this->common_model->getAllSponsorDetails();
	   	$data['view'] = "index";
        $data['allSponsorDetails'] = $allSponsorDetails;
		$this->load->view('admin/content', $data);
	}

	
	public function ajax_list($limit=0)
	{
		$post = $this->input->post();
		$i = 0;
		$admin_path=admin_path();
		//echo $admin_path;
		$columns = array(
			array( 'db' => 'u.U_FNAME',  'dt' => $i++ ),
			array( 'db' => 'u.U_EMAIL',  'dt' => $i++ ),
			array( 'db' => 'os.ORG_TICKETS',  'dt' => $i++ ),
			array( 'db' => 'os.ORG_U_ID',
					'dt' => $i++,
					'formatter' => function( $d, $row ) {
						$op = array();
							$op[] = '<a href="javascript:void(0);" data-href="/admin/sponsor/editSponsor/'.$d.'" class="loadModal btn btn-xs btn-primary">Edit</a>';
							$op[] = '<a href="javascript:void(0);"  data-href="/admin/sponsor/cancelSponsor/'.$d.'" class="loadModal btn btn-xs btn-danger">Delete</a>';
						return implode(" | ",$op);
					})
			);
		$join = array(array('users u', 'os.ORG_U_ID=u.U_ID','left'));
		$where = array();
		$where["u.U_ROLE"] = "S";
		$where["u.U_ACTIVE"] = "1";
		$where["os.ORG_DELETED"] = "0";
		echo json_encode( SSP::simple( $post, "organization_sponsors os", "os.ORG_U_ID", $columns,$join,$where ) );exit;
	}

	 public function addSponsor()
	{
	    $html='';
	   $html.=$this->load->view('admin/sponsor/addSponsor','',TRUE);
	   echo $html;
	}
    public function editSponsor($user_id)
	{
	    //$ticketDetails=$this->common_model->getUserTicketDetails();
		$userSponsorDetails=$this->common_model->getuserSponsorDetails($user_id);
        $userSponsorDetails=$userSponsorDetails[0];
        $data['user_id'] = $user_id;
       // print_r($userSponsorDetails);
        $data['userSponsorDetails'] = $userSponsorDetails;
	   $html='';
		$html.=$this->load->view('admin/sponsor/editSponsor',$data,TRUE);
	   echo $html;
	}
    public function cancelSponsor($user_id)
	{
		$userSponsorDetails=$this->common_model->getuserSponsorDetails($user_id);
        $userSponsorDetails=$userSponsorDetails[0];
        $data['user_id'] = $user_id;
       // print_r($userSponsorDetails);
        $data['userSponsorDetails'] = $userSponsorDetails;
	    $html='';
		$html.=$this->load->view('admin/sponsor/cancelSponsor',$data,TRUE);
	    echo $html;
	}
	public function deletesponsor(){
		$post=$this->input->post();
		$where=array('user_id'=>$post['user_id']);
		$UpdateData = array(
		'ORG_DELETED'=> '1'
		);
		$where=array('ORG_U_ID'=>$post['user_id']);
		$updateId=$this->common_model->updateData($this->common_model->cs_db,'organization_sponsors',$UpdateData,$where);

		$userArr = array(
		'U_ACTIVE'=>0
		);
		$where=array('U_ID'=>$post['user_id'],'U_ROLE'=>'S');
		$updateId=$this->common_model->updateData($this->common_model->cs_db,"users",$userArr,$where);

		$userArr = array(
		'U_ADDEDBY_ID'=>0
		);
		$where=array('U_ADDEDBY_ID'=>$post['user_id'],'U_ROLE'=>'C');
		$updateId=$this->common_model->updateData($this->common_model->cs_db,"users",$userArr,$where);

		if($updateId > 0)
		{

			 echo json_encode( array('status' => 'success','message' => 'Sponsor Successfully Deleted.','redirectUrl'=>$this->current_url) ); exit;
		}else{
			echo json_encode( array('status' => 'error','messages' => 'something worng'));    exit;
		}
	}

    function postAddSponsor(){

            $post=$this->input->post();
			$this->form_validation->set_rules('first_name', 'First Name', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('no_tickets', 'No of Ticket', 'required');
             if(isset($post['user_id'])){
                if($this->input->post('password')){
    				$this->form_validation->set_rules('password', 'Password', 'required');
    			}
                if($this->input->post('password')!=""){
                  $this->form_validation->set_rules('new_password_confirmation', 'Confirm Password', 'required|matches[new_password]');
    					$this->form_validation->set_rules('new_password', 'New Password', 'required|matches[new_password_confirmation]');

                }
            }else{
              	$this->form_validation->set_rules('password', 'Password', 'required');

            }


             if($this->form_validation->run() == FALSE)
			{
				 echo json_encode( array('status' => 'error','messages' => $this->form_validation->error_array()) );                    exit;
			}else{


    		  if(isset($post['user_id'])){
    			$sponserInfo =$this->common_model->sponsorInfo(trim($post['user_id']));
    		  }
			if(isset($sponserInfo[0]['U_ID']) &&  !empty($sponserInfo[0]['U_ID']))
			{
			    $currGmtDate=date('Y-m-d H:i:s');
			    $user_id=$sponserInfo[0]['U_ID'];
                $where = "U_EMAIL = '".$post['email']."' AND U_ROLE='S' AND U_ID!='".$user_id."'";
                $usersdata = $this->common_model->selectDataArr($this->common_model->cs_db,"users", '*', $where);
                   //print_r($usersdata);       exit();
                   //echo $this->common_model->last_query();exit;
                if(count($usersdata)>0){
                   echo json_encode( array('status' => 'error','messages' => array('email'=>'Email address already exists')));    exit;
                }
                if($this->input->post('password')!=""){
			       $old_password_hash = md5($this->input->post('password'));
        		  	$where=array('U_ID'=>$post['user_id']);
        			$user = $this->common_model->selectDataArr($this->common_model->cs_db,"users", '*', $where);
        			$user=$user[0];
                   // pr($user);
        		    $old_password_db_hash = $user['U_PASSWD'];

        		   if($old_password_hash != $old_password_db_hash)
        		   {
        			  echo json_encode( array('status' => 'error','messages' => array('password'=>'Password is not correct')));    exit;
        		   }
                }
				//update data

                	$inputArr=array();
				$inputArr=array('U_FNAME'=>$post['first_name'],'U_LNAME'=>$post['last_name'],'U_EMAIL'=>$post['email'],'U_UPDATED'=> $currGmtDate);
					if(isset($post['new_password']) &&  $post['new_password']!=''){
						$Pssdata = array('U_PASSWD' => md5($this->input->post('new_password')));
						$inputArr= array_merge($inputArr,$Pssdata) ;
					}
					$where=array('U_ID'=>$post['user_id']);
					$updateId=$this->common_model->updateData($this->common_model->cs_db,"users",$inputArr,$where);


                $UpdateData = array(
			   'ORG_TICKETS'=> $post['no_tickets']
				);
                $where=array('ORG_U_ID'=>$user_id);
				$updateId=$this->common_model->updateData($this->common_model->cs_db,'organization_sponsors',$UpdateData,$where);

					if($updateId > 0)
					{
						 echo json_encode( array('status' => 'success','message' => 'Sponsor Successfully Updated.','redirectUrl'=>$this->current_url) );                         exit;
					}else{
                     echo json_encode( array('status' => 'error','messages' => 'something worng'));    exit;
				    }

			}else{
			         $where = "(U_EMAIL = '".$post['email']."' OR  U_LOGIN = '".$post['email']."') AND U_ROLE='S'";
                    $usersdata = $this->common_model->selectDataArr($this->common_model->cs_db,"users", '*', $where);
                    if(count($usersdata)>0){
                        echo json_encode( array('status' => 'error','messages' => array('email'=>'Email address already exists')));    exit;
                    }
		        //insert users data
			    $password=$post['password'];
                $currGmtDate=date('Y-m-d H:i:s');
			    $Data = array(
				'U_FNAME' => $post['first_name'],
				'U_LNAME' =>$post['last_name'],
				'U_EMAIL' =>$post['email'],
                'U_LOGIN' =>$post['email'],
				'U_PASSWD' =>md5($password),
				'U_ROLE' =>'S',
                'U_CREATED' =>$currGmtDate,
                'U_ADDEDBY_ID' =>$this->user_session['U_ID'],
                'U_ACTIVE' =>'1',
				);
				$insId=$this->common_model->insertData($this->common_model->cs_db,'users',$Data);
				if($insId > 0)
				{

						   // Insert Order Attendess per tickets
						$orgData = array(
						'ORG_U_ID' =>$insId,
						'ORG_TICKETS' =>$post['no_tickets'],
					   'ORG_CREATED'=> $currGmtDate,
                       'ORG_TYPE'=>'S',
						);
						$lastId=$this->common_model->insertData($this->common_model->cs_db,'organization_sponsors',$orgData);
                	/*sending mail to user and terabitz support*/
				   /*	if(!empty($toEmail))
					{

							$subject="Your ticket for the event Priya 's Interenational confirance";
							$Data=array('useremail'=>$userInfo,'userInfo'=>$userInfo);
							$emailTpl =$this->load->view('admin/sponsor/sponsorEmail.php',$Data,true);
							$mailSent = sendEmail($toEmail,$subject,$emailTpl,'priya.khilosiya@kraffsoft.com','Priya Khilosiya');
							if ($mailSent) {
								$data["mailsentTouser"] = "1";
							} else {
								$data['mailsentTouser']="0";
							}
					} */
						/*sending mail to user and terabitz support*/

                    echo json_encode( array('status' => 'success','message' => 'Sponsor Added Succesfully','redirectUrl'=>$this->current_url));exit;

                }else{
                    echo json_encode( array('status' => 'error','messages' => 'something worng'));  exit;

                }

           }
        }
    }
}