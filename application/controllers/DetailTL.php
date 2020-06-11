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
        $space=" ";
        $this->db->select("dtl.id_detail_layanan, dtl.id_trans_layanan, dtl.id_harga_layanan, 
                            concat(l.nama_layanan, ' ', j.jenis, ' ', u.ukuran) as nama_layanan, hl.harga_layanan,
                            dtl.jumlah_beli_layanan, dtl.subtotal_layanan");
        $this->db->from('detail_trans_layanan as dtl');
        $this->db->join('harga_layanan as hl', 'dtl.id_harga_layanan = hl.id_harga_layanan');
        $this->db->join('layanan as l', 'hl.id_layanan = l.id_layanan');
        $this->db->join('jenis_hewan as j', 'hl.id_jenis = j.id_jenis');
        $this->db->join('ukuran_hewan as u', 'hl.id_ukuran = u.id_ukuran');
        $this->db->where(array('dtl.id_trans_layanan'=>$id));
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);  
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->DetailTLModel->rules(); 
        if($id == null){ 
            array_push($rule,
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

            $response = $this->DetailTLModel->store($detail_trans_layanan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $detail_trans_layanan = new DetailTransaksiLayananData(); 
            $detail_trans_layanan->id_harga_layanan  = $this->post('id_harga_layanan'); 
            $detail_trans_layanan->jumlah_beli_layanan = $this->post('jumlah_beli_layanan');  

            $response = $this->DetailTLModel->update($detail_trans_layanan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function index_delete($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->DetailTLModel->destroy($id); 
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