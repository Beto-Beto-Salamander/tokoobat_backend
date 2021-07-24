<?php
use Restserver\Libraries\REST_Controller ; 

Class DetailTransaksi extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('DetailTransaksiModel'); 
        $this->load->library('PHPMailer_Library'); 
        $this->load->library('form_validation'); $this->load->helper(['jwt','authorization']);
    } 

    public function index_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        $this->db->select('dt.id_detail_transaksi, dt.id_transaksi, dt.id_produk, p.nama_produk, p.harga_jual_produk,
        dt.jml_transaksi_produk, dt.subtotal_transaksi');
        $this->db->from('detail_transaksi as dt');
        $this->db->join('produk as p', 'dt.id_produk = p.id_produk');
        $this->db->where(array('dt.id_transaksi'=>$id));
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);   
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DetailTransaksiModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_produk', 
                    'label' => 'id_produk', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'jml_transaksi_produk', 
                    'label' => 'jml_transaksi_produk', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $detail_transaksi = new DetailTransaksiData(); 
            $detail_transaksi->id_transaksi = $this->post('id_transaksi'); 
            $detail_transaksi->id_produk  = $this->post('id_produk'); 
            $detail_transaksi->jml_transaksi_produk = $this->post('jml_transaksi_produk');

            $response = $this->DetailTransaksiModel->store($detail_transaksi);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $detail_transaksi = new DetailTransaksiData(); 
            $detail_transaksi->id_produk  = $this->post('id_produk'); 
            $detail_transaksi->jml_transaksi_produk = $this->post('jml_transaksi_produk');  

            $response = $this->DetailTransaksiModel->update($detail_transaksi,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function index_delete($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->DetailTransaksiModel->destroy($id); 
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
Class DetailTransaksiData{ 
    public $id_transaksi; 
    public $id_produk; 
    public $jml_transaksi_produk;
}