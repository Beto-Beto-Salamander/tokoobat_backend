<?php
use Restserver \Libraries\REST_Controller ; 

Class TransaksiLayanan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('TransaksiLayananModel'); 
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
            return $this->returnData($this->db->get_where('transaksi_layanan',array('translay_deleted_at'=>null))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('transaksi_layanan',array('id_trans_layanan' => $id,'translay_deleted_at'=>null))->result(), false);
        }
            
    } 

    public function log_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->get_where('transaksi_layanan')->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('transaksi_layanan',array('id_trans_layanan' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->TransaksiLayananModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_pegawai', 
                    'label' => 'id_pegawai', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'id_hewan', 
                    'label' => 'id_hewan', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'tanggal_trans_layanan', 
                    'label' => 'tanggal_trans_layanan', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'status_layanan', 
                    'label' => 'status_layanan', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $trans_layanan = new TransaksiLayananData(); 
            $trans_layanan->id_pegawai = $this->post('id_pegawai'); 
            $trans_layanan->id_hewan  = $this->post('id_hewan'); 
            $trans_layanan->tanggal_trans_layanan = $this->post('tanggal_trans_layanan');
            $trans_layanan->status_layanan = $this->post('status_layanan');
            $trans_layanan->translay_created_by = $this->post('translay_created_by');

            $response = $this->TransaksiLayananModel->store($trans_layanan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $trans_layanan = new TransaksiLayananData(); 
            $trans_layanan->id_pegawai = $this->post('id_pegawai'); 
            $trans_layanan->id_hewan  = $this->post('id_hewan'); 
            $trans_layanan->tanggal_trans_layanan = $this->post('tanggal_trans_layanan');
            $trans_layanan->status_layanan = $this->post('status_layanan');
            $trans_layanan->translay_edited_by = $this->post('translay_edited_by'); 

            $response = $this->TransaksiLayananModel->update($trans_layanan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 
        $translay = new TransaksiLayananData();
        $translay->translay_deleted_by = $this->post('translay_deleted_by');
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->TransaksiLayananModel->destroy($translay,$id); 
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
        // Successfull validation will return the decoded trans_layanan data else returns false
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
Class TransaksiLayananData{ 
    public $id_pegawai; 
    public $peg_id_pegawai; 
    public $id_hewan; 
    public $tanggal_trans_layanan; 
    public $status_trans_layanan;
    public $translay_created_by; 
    public $translay_edited_by;
}