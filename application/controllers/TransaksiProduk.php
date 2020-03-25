<?php
use Restserver \Libraries\REST_Controller ; 

Class TransaksiProduk extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('TransaksiProdukModel'); 
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
            return $this->returnData($this->db->get_where('transaksi_produk',array('id_trans_produk'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('transaksi_produk',array('id_trans_produk' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->TransaksiProdukModel->rules(); 
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
                    'field' => 'tanggal_trans_produk', 
                    'label' => 'tanggal_trans_produk', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'status_penjualan_produk', 
                    'label' => 'status_penjualan_produk', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $trans_produk = new TransaksiProdukData(); 
            $trans_produk->id_pegawai = $this->post('id_pegawai'); 
            $trans_produk->id_hewan  = $this->post('id_hewan'); 
            $trans_produk->tanggal_trans_produk = $this->post('tanggal_trans_produk');
            $trans_produk->status_penjualan_produk = $this->post('status_penjualan_produk');
            $trans_produk->transproduk_created_by = $this->post('transproduk_created_by');

            $response = $this->TransaksiProdukModel->store($trans_produk);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $trans_produk = new TransaksiProdukData(); 
            $trans_produk->id_pegawai = $this->post('id_pegawai'); 
            $trans_produk->id_hewan  = $this->post('id_hewan'); 
            $trans_produk->tanggal_trans_produk = $this->post('tanggal_trans_produk');
            $trans_produk->status_penjualan_produk = $this->post('status_penjualan_produk');
            $trans_produk->transproduk_edited_by = $this->post('transproduk_edited_by');

            $response = $this->TransaksiProdukModel->update($trans_produk,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->TransaksiProdukModel->destroy($id); 
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
        // Successfull validation will return the decoded trans_produk data else returns false
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
Class TransaksiProdukData{ 
    public $tgl_trans_produk; 
    public $total_trans_produk; 
    public $status_trans_produk;
    public $transproduk_created_by; 
    public $transproduk_edited_by;
}