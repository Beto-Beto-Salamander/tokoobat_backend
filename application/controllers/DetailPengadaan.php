<?php
use Restserver \Libraries\REST_Controller ; 

Class DetailPengadaan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('DetailPengadaanModel'); 
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
            return $this->returnData($this->db->get_where('detail_pengadaan',array('id_detail_pengadaan'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('detail_pengadaan',array('id_detail_pengadaan' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DetailPengadaanModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'tgl_detail_pengadaan', 
                    'label' => 'tgl_detail_pengadaan', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'total_detail_pengadaan', 
                    'label' => 'total_detail_pengadaan', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'status_detail_pengadaan', 
                    'label' => 'status_detail_pengadaan', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $detail_pengadaan = new DetailPengadaanData(); 
            $detail_pengadaan->tgl_detail_pengadaan = $this->post('tgl_detail_pengadaan'); 
            $detail_pengadaan->total_detail_pengadaan  = $this->post('total_detail_pengadaan'); 
            $detail_pengadaan->status_detail_pengadaan = $this->post('status_detail_pengadaan');

            $response = $this->DetailPengadaanModel->store($detail_pengadaan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $detail_pengadaan = new DetailPengadaanData(); 
            $detail_pengadaan->tgl_detail_pengadaan = $this->post('tgl_detail_pengadaan'); 
            $detail_pengadaan->total_detail_pengadaan  = $this->post('total_detail_pengadaan'); 
            $detail_pengadaan->status_detail_pengadaan = $this->post('status_detail_pengadaan');  

            $response = $this->DetailPengadaanModel->update($detail_pengadaan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->DetailPengadaanModel->destroy($id); 
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
        // Successfull validation will return the decoded detail_pengadaan data else returns false
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
Class DetailPengadaanData{ 
    public $tgl_detail_pengadaan; 
    public $total_detail_pengadaan; 
    public $status_detail_pengadaan;
    public $adaan_deleted_at; 
}