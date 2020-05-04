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

    public function index_get($id){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        $this->db->select('dp.id_detail_pengadaan, dp.id_pengadaan, dp.id_produk, pro.nama_produk, pro.harga_beli_produk, dp.jml_pengadaan_produk, dp.satuan, dp.subtotal_pengadaan');
        $this->db->from('detail_pengadaan as dp');
        $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
        $this->db->where(array('dp.id_pengadaan'=>$id));
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);     
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DetailPengadaanModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_produk', 
                    'label' => 'id_produk', 
                    'rules' => 'required' 
                ],
                
                [ 
                    'field' => 'satuan', 
                    'label' => 'satuan', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'jml_pengadaan_produk', 
                    'label' => 'jml_pengadaan_produk', 
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
            $detail_pengadaan->id_pengadaan = $this->post('id_pengadaan'); 
            $detail_pengadaan->id_produk  = $this->post('id_produk'); 
            $detail_pengadaan->satuan  = $this->post('satuan'); 
            $detail_pengadaan->jml_pengadaan_produk = $this->post('jml_pengadaan_produk');

            $response = $this->DetailPengadaanModel->store($detail_pengadaan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $detail_pengadaan = new DetailPengadaanData(); 
            $detail_pengadaan->id_produk  = $this->post('id_produk'); 
            $detail_pengadaan->satuan  = $this->post('satuan'); 
            $detail_pengadaan->jml_pengadaan_produk = $this->post('jml_pengadaan_produk');  

            $response = $this->DetailPengadaanModel->update($detail_pengadaan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function index_delete($id = null){ 

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
    public $id_pengadaan; 
    public $id_produk; 
    public $jml_pengadaan_produk;
}