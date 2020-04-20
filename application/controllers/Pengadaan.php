<?php
use Restserver \Libraries\REST_Controller ; 

Class Pengadaan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('PengadaanModel'); 
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
            return $this->returnData($this->db->get_where('pengadaan',array('adaan_deleted_at'=>null))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('pengadaan',array('id_pengadaan' => $id,'adaan_deleted_at'=>null))->result(), false);
        }
            
    } 

    public function log_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->order_by('adaan_deleted_at','ASC')->get_where('pengadaan')->result(), false);
        }   
        else{
            return $this->returnData($this->db->order_by('adaan_deleted_at','ASC')->get_where('pengadaan',array('id_pengadaan' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->PengadaanModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'status_pengadaan', 
                    'label' => 'status_pengadaan', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        } 

        if($id == null){
            $pengadaan = new PengadaanData(); 
            $pengadaan->status_pengadaan = $this->post('status_pengadaan');

            $response = $this->PengadaanModel->store($pengadaan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $pengadaan = new PengadaanData(); 
            $pengadaan->tgl_pengadaan = $this->post('tgl_pengadaan'); 
            $pengadaan->status_pengadaan = $this->post('status_pengadaan');  

            $response = $this->PengadaanModel->update($pengadaan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->PengadaanModel->destroy($id); 
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
        // Successfull validation will return the decoded pengadaan data else returns false
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
Class PengadaanData{ 
    public $tgl_pengadaan; 
    public $status_pengadaan;
}