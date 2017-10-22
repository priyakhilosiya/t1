<?php
class common_model extends CI_Model{

	public $commentsEnable=false;

	public function  __construct(){
		parent::__construct();
		
		$this->cs_db = $this->load->database("default",true);
	}
	/*
	| -------------------------------------------------------------------
	| Select data
	| -------------------------------------------------------------------
	|
	| general function to get result by passing nesessary parameters
	|
	*/
	public function selectDataArr($db,$table, $fields='*', $where='', $order_by="", $order_type="", $group_by="", $limit="", $rows="", $type='')
	{
		$siteConf = $this->config->item("siteConf");
		$db->select($fields);
		$db->from($table);
		if ($where != "") {
			$db->where($where);
		}

		if ($order_by != '') {
			$db->order_by($order_by,$order_type);
		}

		if ($group_by != '') {
			$db->group_by($group_by);
		}

		if ($limit > 0 && $rows == "") {
			$db->limit($limit);
		}
		if ($rows > 0) {
			$db->limit($rows, $limit);
		}
		$query = $db->get();
		//echo $db->last_query();exit;
		if ($type == "rowcount") {
			$data = $query->num_rows();
		}else{
			$data = $query->result_array();
		}
		
		$query->free_result();

		return $data;
	}

	public function selectRowDataArr($db,$table, $fields='*', $where='', $order_by="", $order_type="", $group_by="", $limit="", $rows="", $type='')
	{
		$siteConf = $this->config->item("siteConf");
		$db->select($fields);
		$db->from($table);
		if ($where != "") {
			$db->where($where);
		}

		if ($order_by != '') {
			$db->order_by($order_by,$order_type);
		}

		if ($group_by != '') {
			$db->group_by($group_by);
		}

		if ($limit > 0 && $rows == "") {
			$db->limit($limit);
		}
		if ($rows > 0) {
			$db->limit($rows, $limit);
		}
		$query = $db->get();
		//echo $db->last_query();exit;
		if ($type == "rowcount") {
			$data = $query->num_rows();
		}else{
			$data = $query->row_array();
		}
		
		$query->free_result();

		return $data;
	}

	public function getUserDetails($id)
	{
		
		$data=array();
		$fromtable="users";
		$fields='*';
		$orderby='U_ID';

		$this->db->select('*');
		$this->db->from($fromtable);
		$this->db->where('U_ID',$id);
	    $query = $this->db->get();
		$data = $query->result_array();
        if(count($data) > 0)
			{
					$data=$data[0];
			}
       	return $data;
	}

	public function getAlluserattendeeDetails(){

      	$data=array();
		$fromtable="attendees ad";
		$orderby='ad.ATD_CREATED';
		$orderType='DESC';
		if($this->user_session['U_ROLE']=='S'){
			
			$this->db->where('u.U_ADDEDBY_ID=',$this->user_session['U_ID']);
		}
        $this->db->where('u.U_ROLE=','C');
		$this->db->where('ad.ATD_IS_CANCELED=','0');
        $this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('order_details od', 'ad.ATD_ORD_ID=od.ORD_ID','left');
		$this->db->join('orders ord', 'ord.ORD_ID=ad.ATD_ORD_ID','left');
		$this->db->join('users u', 'u.U_ID=ad.ATD_U_ID','left');
        $this->db->order_by($orderby,$orderType);
		$this->db->where('od.ORD_CAT_TYPE!=','A');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;

		$data = $query->result_array();
		return $data;
	}

	public function getexportattendeeDetails(){

      	$data=array();
		$fromtable="attendees ad";
		$orderby='ad.ATD_CREATED';
		$orderType='DESC';
		if($this->user_session['U_ROLE']=='S'){
			
			$this->db->where('u.U_ADDEDBY_ID=',$this->user_session['U_ID']);
		}
        $this->db->where('u.U_ROLE=','C');
        $this->db->select('ad.ATD_FNAME,ad.ATD_LNAME,ad.ATD_EMAIL,ad.ATD_T_NAME,ord.ORD_REFERENCE,ad.ATD_CREATED');
		$this->db->from($fromtable);
		$this->db->join('order_details od', 'ad.ATD_ORD_ID=od.ORD_ID','left');
		$this->db->join('orders ord', 'ord.ORD_ID=ad.ATD_ORD_ID','left');
		$this->db->join('users u', 'u.U_ID=ad.ATD_U_ID','left');
        $this->db->order_by($orderby,$orderType);
		$this->db->where('od.ORD_CAT_TYPE!=','A');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;

		$data = $query->result_array();
		return $data;
	}

	public function getAllRegisteredUserDetails(){

        $data=array();
		$fromtable="users u";
		$orderby='u.U_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ROLE=','C');
        $this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('order_details od', 'u.U_ID=od.ORD_U_ID', 'left');
        $this->db->join('tickets tc', 'od.ORD_T_ID=tc.T_ID', 'left');
        $this->db->join('orders ord', 'ord.ORD_U_ID=u.U_ID', 'left');
		$this->db->where('u.U_PASSWD!=','');
        $this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

    public function getuserattendeeDetails($order_id,$user_id){

      	$data=array();
		$fromtable="attendees ad";
		$orderby='ad.ATD_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ROLE=','C');
        $this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('order_details od', 'ad.ATD_ORD_ID=od.ORD_ID','left');
		$this->db->join('orders ord', 'ord.ORD_ID=ad.ATD_ORD_ID','left');
		$this->db->join('users u', 'u.U_ID=ad.ATD_U_ID','left');
		$this->db->where('u.U_ID=',$user_id);
		$this->db->where('ord.ORD_ID=',$order_id);
        $this->db->order_by($orderby,$orderType);
		$this->db->where('od.ORD_CAT_TYPE!=','A');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$data = $query->result_array();
		return $data;
	}

	public function getAttendeedetails($att_id){

		$data=array();
		$fromtable="attendees ad";
		$orderby='ad.ATD_CREATED';
		$orderType='DESC';
        $this->db->select('*');
		$this->db->from($fromtable);
		$this->db->where('ad.ATD_ID=',$att_id);
		$this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$data = $query->result_array();
		return $data;
		
	}
	public function getAttendeecnt(){

		$data=array();
		$fromtable="attendees ad";
		$orderby='ad.ATD_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ROLE=','C');
    	$this->db->where('U_ADDEDBY_ID=', $this->user_session['U_ID']);
		$this->db->where('ATD_IS_CANCELED=',0);

        $this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('users u', 'u.U_ID=ad.ATD_U_ID','left');
		$this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		$data = $query->result_array();
		//print_r($data);
		$cnt=count($data);
		return $cnt;
	}

	public function getorderDetails($order_id){

      	$data=array();
		$fromtable="attendees ad";
		$orderby='ad.ATD_CREATED';
		$orderType='DESC';
		$this->db->where('ad.ATD_IS_CANCELED=','0');
		$this->db->where('ord.ORD_ID=',$order_id);
        $this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('order_details od', 'ad.ATD_ORD_ID=od.ORD_ID','left');
		$this->db->join('orders ord', 'ord.ORD_ID=ad.ATD_ORD_ID','left');
		 $this->db->order_by($orderby,$orderType);
		$this->db->where('od.ORD_CAT_TYPE!=','A');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;

		$data = $query->result_array();
		return $data;
	}


	public function getaccompanyDetails($order_id){

      	$data=array();
		$fromtable="accompany ac";
		$orderby='ac.ACMP_CREATED';
		$orderType='DESC';
		//$this->db->where('ad.ATD_IS_CANCELED=','0');
		$this->db->where('ac.ACMP_ORD_ID=',$order_id);
        $this->db->select('*');
		$this->db->from($fromtable);
		 $this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		
		$data = $query->result_array();
		return $data;
	}




	
	
	public function getUserTicketDetails()
	{
		$data=array();
		$fromtable="tickets tc";
		$orderby='tc.T_CREATED';
		$orderType='DESC';
        $this->db->where('tc.T_U_ID',$this->user_session['U_ID']);
		$this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('ticket_status ts', 'tc.T_ST_ID=ts.ST_ID', 'left');
		$this->db->where('tc.T_DELETED !=','1');
        $this->db->where('tc.T_ST_ID =','4');
		$this->db->where('tc.T_IS_PAUSED =','0');
		$this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

    public function getTicketDetails()
	{
		$data=array();
		$fromtable="tickets tc";
		$orderby='tc.T_CREATED';
		$orderType='DESC';
        $this->db->where('tc.T_U_ID',$this->user_session['U_ID']);
		$this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('ticket_status ts', 'tc.T_ST_ID=ts.ST_ID', 'left');
		$this->db->where('tc.T_DELETED !=','1');
        $this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

     public function attendeeInfo($user_id,$order_id)
	{
		$data=array();
		$fromtable="users u";
		$orderby='u.U_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ID',$user_id);
        $this->db->where('od.ORD_ID',$order_id);
		$this->db->select('*');
		$this->db->from($fromtable);
		$this->db->join('order_details od', 'u.U_ID=od.ORD_U_ID', 'left');
		$this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}



	public function getAllSponsorDetails(){

        $data=array();
		$fromtable="users u";
		$orderby='u.U_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ROLE=','S');
		$this->db->where('u.U_ACTIVE=','1');
		$this->db->where('as.ORG_DELETED=','0');
        $this->db->select('*');
		$this->db->from($fromtable);
		 $this->db->join('organization_sponsors as', 'as.ORG_U_ID=u.U_ID');
        $this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;

		$data = $query->result_array();
		return $data;
	}

	public function getuserSponsorDetails($user_id){
		 $data=array();
		$fromtable="users u";
		$orderby='u.U_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ROLE=','S');
        $this->db->where('u.U_ID',$user_id);
        $this->db->select('*');
		$this->db->from($fromtable);
		 $this->db->join('organization_sponsors as', 'as.ORG_U_ID=u.U_ID');
        $this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;

		$data = $query->result_array();
		return $data;
	}

    public function sponsorInfo($user_id){
        $data=array();
		$fromtable="users u";
		$orderby='u.U_CREATED';
		$orderType='DESC';
        $this->db->where('u.U_ROLE=','S');
        $this->db->where('u.U_ID',$user_id);
        $this->db->select('*');
		$this->db->from($fromtable);
		 $this->db->join('organization_sponsors as', 'as.ORG_U_ID=u.U_ID');
        $this->db->order_by($orderby,$orderType);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;

		$data = $query->result_array();
		return $data;


    }
	
	public function selectData($db,$table, $fields='*', $where='', $order_by="", $order_type="", $group_by="", $limit="", $rows="", $type='')
	{
		$db->select($fields,false);
		$db->from($table);
		if ($where != "") {
			$db->where($where);
		}

		if ($order_by != '') {
			$db->order_by($order_by,$order_type);
		}

		if ($group_by != '') {
			$db->group_by($group_by);
		}

		if ($limit > 0 && $rows == "") {
			$db->limit($limit);
		}
		if ($rows > 0) {
			$db->limit($rows, $limit);
		}


		$query = $db->get();

		if ($type == "rowcount") {
			$data = $query->num_rows();
		}else{
			$data = $query->result();
		}

		#echo "<pre>"; print_r($db->queries); exit;
		$query->free_result();

		return $data;
	}

	public function getCount($db,$table,$where=""){
		$data = $this->selectData($db,$table, '*', $where, "", "", "", "", "", 'rowcount');
		return $data;
	}


	/*
	| -------------------------------------------------------------------
	| Insert data
	| -------------------------------------------------------------------
	|
	| general function to insert data in table
	|
	*/
	public function insertData($db,$table, $data)
	{
		$result = $db->insert($table, $data);
		if($result == 1){
			return $db->insert_id();
		}else{
			return false;
		}
	}


	/*
	| -------------------------------------------------------------------
	| Update data
	| -------------------------------------------------------------------
	|
	| general function to update data
	|
	*/
	public function updateData($db,$table, $data, $where, $flag =true)
	{
		$db->where($where);
		
		foreach ($data as $key=>$val) {
			$db->set($key, $val, $flag);
		}

		if($db->update($table)){
			return 1;
		}else{
			return 0;
		}
	}

	/*
	| -------------------------------------------------------------------
	| Delere data
	| -------------------------------------------------------------------
	|
	| general function to delete the records
	|
	*/
	public function deleteData($db,$table, $data)
	{
		if($db->delete($table, $data)){
			return 1;
		}else{
			return 0;
		}
	}

	/*
	| -------------------------------------------------------------------
	| check unique fields
	| -------------------------------------------------------------------
	|
	*/
	public function isUnique($db,$table, $field, $value,$where = "")
	{
		$db->select('*');
		$db->from($table);
		$db->where($field,$value);
		if ($where != "")
			$db->where($where);
		$query = $db->get();
		$data = $query->num_rows();
		$query->free_result();
		return ($data > 0)?FALSE:TRUE;
	}

}
?>
