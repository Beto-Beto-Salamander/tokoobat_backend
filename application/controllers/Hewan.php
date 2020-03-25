<?php
use Restserver \Libraries\REST_Controller ; 

Class Hewan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('HewanModel'); 
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
            return $this->returnData($this->db->get_where('hewan',array('id_hewan'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('hewan',array('id_hewan' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->HewanModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_customer', 
                    'label' => 'id_customer', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'nama_hewan', 
                    'label' => 'nama_hewan', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'tgllahir_hewan', 
                    'label' => 'tgllahir_hewan', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        }

        if($id == null){
            $hewan = new HewanData(); 
            $hewan->id_customer = $this->post('id_customer');
            $hewan->nama_hewan = $this->post('nama_hewan'); 
            $hewan->tgllahir_hewan = $this->post('tgllahir_hewan');
            $hewan->hwn_created_by = $this->post('hwn_created_by'); 

            $response = $this->HewanModel->store($hewan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $hewan = new HewanData(); 
            $hewan->id_customer = $this->post('id_customer'); 
            $hewan->nama_hewan = $this->post('nama_hewan'); 
            $hewan->tgllahir_hewan = $this->post('tgllahir_hewan');
            $hewan->hwn_edited_by = $this->post('hwn_edited_by');

            $response = $this->HewanModel->update($hewan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
    } 

    public function delete_post($id = null){ 
        $hewan = new HewanData(); 
        $hewan->hwn_deleted_by = $this->post('hwn_deleted_by');

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->HewanModel->destroy($hewan,$id); 
        return $this->returnData($response['msg'], $response['error']); 
    } 
    public function returnData($msg,$error){ 
        $response['error']=$error; 
        $response['message']=$msg; 
        return $this->response($response); 
    } 

    private function verify_request()
    {
    // Get all the headers
        $headers = $this->input->request_headers();
        if(!empty($headers['Authorization'])){
            $header = $headers['Authorization'];
        }else{
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            return $response;
        }
    // $token = explode(" ",$header)[1];
    try {
        // Validate the token
        // Successfull validation will return the decoded hewan data else returns false
        $data = AUTHORIZATION::validateToken($header);
        if ($data === false) {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            // $this->response($response, $status);
            // exit();
        } else {
            $response = ['status' => 200 , 'msg' => $data];
        }
        return $response;
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            return $response;
        }
    }
} 
Class HewanData{ 
    public $id_customer;
    public $nama_hewan; 
    public $tgllahir_hewan;
    public $hwn_deleted_at; 
    public $hwn_created_by; 
    public $hwn_edited_by;
    public $hwn_deleted_by;
}