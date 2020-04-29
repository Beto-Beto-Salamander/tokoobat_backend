<?php
use Restserver \Libraries\REST_Controller ; 

Class Device extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('DeviceModel'); 
        $this->load->library('PHPMailer_Library'); 
        $this->load->library('form_validation'); $this->load->helper(['jwt','authorization']);
    } 

    public function index_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->get_where('device')->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('device',array('id_device' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DeviceModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'role', 
                    'label' => 'role', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'token', 
                    'label' => 'token', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            // return $this->returnData($this->form_validation->error_array(), true); 
            return $this->response($this->form_validation->error_array()); 
        }

        if($id == null){
            $device = new DeviceData(); 
            $device->role = $this->post('role'); 
            $device->token = $this->post('token'); 

            $response = $this->DeviceModel->store($device);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $device = new DeviceData(); 
            $device->role = $this->post('role'); 
            $device->token = $this->post('token');  

            $response = $this->DeviceModel->update($device,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
    } 

    public function delete_post($id = null){ 
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->DeviceModel->destroy($id); 
        return $this->returnData($response['msg'], $response['error']); 
    } 

    public function returnData($msg,$error){ 
        $response['error']=$error; 
        $response['message']=$msg; 
        return $this->response($response); 
    } 
} 
Class DeviceData{ 
    public $role; 
    public $token; 
}