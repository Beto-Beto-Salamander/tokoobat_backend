<?php
use Restserver \Libraries\REST_Controller ; 

Class JenisHewan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('JenisHewanModel'); 
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
            return $this->returnData($this->db->get_where('jenis_hewan',array('id_jenis'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('jenis_hewan',array('id_jenis' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->JenisHewanModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'jenis', 
                    'label' => 'jenis', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        }

        if($id == null){
            $jenis_hewan = new JenisHewanData(); 
            $jenis_hewan->jenis = $this->post('jenis'); 

            $response = $this->JenisHewanModel->store($jenis_hewan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $jenis_hewan = new JenisHewanData(); 
            $jenis_hewan->jenis = $this->post('jenis');  

            $response = $this->JenisHewanModel->update($jenis_hewan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
    } 

    public function index_delete($id = null){ 
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->JenisHewanModel->destroy($id); 
        return $this->returnData($response['msg'], $response['error']); 
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

    public function returnData($msg,$error){ 
        $response['error']=$error; 
        $response['message']=$msg; 
        return $this->response($response); 
    } 
} 
Class JenisHewanData{ 
    public $jenis; 
    public $jns_deleted_at;
}