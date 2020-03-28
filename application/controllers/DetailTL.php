<?php
use Restserver \Libraries\REST_Controller ; 

Class DetailTL extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('DetailTLModel'); 
        $this->load->library('form_validation'); $this->load->helper(['jwt','authorization']);
    } 

    public function index_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->get_where('detail_trans_layanan',array('id_detail_layanan'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('detail_trans_layanan',array('id_detail_layanan' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DetailTransaksiLayananModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_trans_layanan', 
                    'label' => 'id_trans_layanan', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'id_harga_layanan', 
                    'label' => 'id_harga_layanan', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'jumlah_beli_layanan', 
                    'label' => 'jumlah_beli_layanan', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $detail_trans_layanan = new DetailTransaksiLayananData(); 
            $detail_trans_layanan->id_trans_layanan = $this->post('id_trans_layanan'); 
            $detail_trans_layanan->id_harga_layanan  = $this->post('id_harga_layanan'); 
            $detail_trans_layanan->jumlah_beli_layanan = $this->post('jumlah_beli_layanan');

            $response = $this->DetailTransaksiLayananModel->store($detail_trans_layanan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $detail_trans_layanan = new DetailTransaksiLayananData(); 
            $detail_trans_layanan->id_trans_layanan = $this->post('id_trans_layanan'); 
            $detail_trans_layanan->id_harga_layanan  = $this->post('id_harga_layanan'); 
            $detail_trans_layanan->jumlah_beli_layanan = $this->post('jumlah_beli_layanan');  

            $response = $this->DetailTransaksiLayananModel->update($detail_trans_layanan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->DetailTransaksiLayananModel->destroy($id); 
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
        // Successfull validation will return the decoded detail_trans_layanan data else returns false
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
Class DetailTransaksiLayananData{ 
    public $id_trans_layanan; 
    public $id_harga_layanan; 
    public $jumlah_beli_layanan;
}