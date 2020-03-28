<?php
use Restserver \Libraries\REST_Controller ; 

Class DetailTP extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('DetailTPModel'); 
        $this->load->library('form_validation'); $this->load->helper(['jwt','authorization']);
    } 

    public function index_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->get_where('detail_trans_produk',array('id_detail_produk'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('detail_trans_produk',array('id_detail_produk' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DetailTransaksiProdukModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_trans_produk', 
                    'label' => 'id_trans_produk', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'id_harga_produk', 
                    'label' => 'id_harga_produk', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'jumlah_beli_produk', 
                    'label' => 'jumlah_beli_produk', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $detail_trans_produk = new DetailTransaksiProdukData(); 
            $detail_trans_produk->id_trans_produk = $this->post('id_trans_produk'); 
            $detail_trans_produk->id_harga_produk  = $this->post('id_harga_produk'); 
            $detail_trans_produk->jumlah_beli_produk = $this->post('jumlah_beli_produk');

            $response = $this->DetailTransaksiProdukModel->store($detail_trans_produk);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $detail_trans_produk = new DetailTransaksiProdukData(); 
            $detail_trans_produk->id_trans_produk = $this->post('id_trans_produk'); 
            $detail_trans_produk->id_harga_produk  = $this->post('id_harga_produk'); 
            $detail_trans_produk->jumlah_beli_produk = $this->post('jumlah_beli_produk');  

            $response = $this->DetailTransaksiProdukModel->update($detail_trans_produk,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->DetailTransaksiProdukModel->destroy($id); 
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
        // Successfull validation will return the decoded detail_trans_produk data else returns false
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
Class DetailTransaksiProdukData{ 
    public $id_trans_produk; 
    public $id_harga_produk; 
    public $jumlah_beli_produk;
}