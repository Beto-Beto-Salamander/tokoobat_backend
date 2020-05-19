<?php
use Restserver \Libraries\REST_Controller ; 

Class HargaLayanan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('HargaLayananModel'); 
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
            $this->db->select("h.id_harga_layanan, h.id_layanan, concat(l.nama_layanan, ' ', j.jenis, ' ', u.ukuran) as nama_layanan, h.id_jenis, j.jenis, h.id_ukuran, u.ukuran, h.harga_layanan");
            $this->db->from('harga_layanan as h');
            $this->db->join('layanan as l', 'h.id_layanan = l.id_layanan');
            $this->db->join('jenis_hewan as j', 'h.id_jenis = j.id_jenis');
            $this->db->join('ukuran_hewan as u', 'h.id_ukuran = u.id_ukuran');
            $this->db->where(array('h.harga_deleted_at'=>null));
        }   
        else{
            $this->db->select("h.id_harga_layanan, h.id_layanan, concat(l.nama_layanan, ' ', j.jenis, ' ', u.ukuran) as nama_layanan, h.id_jenis, j.jenis, h.id_ukuran, u.ukuran, h.harga_layanan");
            $this->db->from('harga_layanan as h');
            $this->db->join('layanan as l', 'h.id_layanan = l.id_layanan');
            $this->db->join('jenis as j', 'h.id_jenis = j.id_jenis');
            $this->db->join('ukuran as u', 'h.id_ukuran = u.id_ukuran');
            $this->db->where(array('h.id_harga_layanan'=>$id,'h.harga_deleted_at'=>null));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);
    } 

    public function log_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            $this->db->select("h.id_harga_layanan, h.id_layanan,  concat(l.nama_layanan, ' ', j.jenis, ' ', u.ukuran) as nama_layanan, h.id_jenis, j.jenis, h.id_ukuran, u.ukuran, h.harga_layanan,
                                h.harga_created_at, h.harga_deleted_at, h.harga_edited_at");
            $this->db->from('harga_layanan as h');
            $this->db->join('layanan as l', 'h.id_layanan = l.id_layanan');
            $this->db->join('jenis_hewan as j', 'h.id_jenis = j.id_jenis');
            $this->db->join('ukuran_hewan as u', 'h.id_ukuran = u.id_ukuran');
        }   
        else{
            $this->db->select("h.id_harga_layanan, h.id_layanan,  concat(l.nama_layanan, ' ', j.jenis, ' ', u.ukuran) as nama_layanan, h.id_jenis, j.jenis, h.id_ukuran, u.ukuran, h.harga_layanan,
                                h.harga_created_at, h.harga_deleted_at, h.harga_edited_at");
            $this->db->from('harga_layanan as h');
            $this->db->join('layanan as l', 'h.id_layanan = l.id_layanan');
            $this->db->join('jenis_hewan as j', 'h.id_jenis = j.id_jenis');
            $this->db->join('ukuran_hewan as u', 'h.id_ukuran = u.id_ukuran');
            $this->db->where(array('h.id_harga_layanan'=>$id));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->HargaLayananModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_layanan', 
                    'label' => 'id_layanan', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'id_jenis', 
                    'label' => 'id_jenis', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'id_ukuran', 
                    'label' => 'id_ukuran', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'harga_layanan', 
                    'label' => 'harga_layanan', 
                    'rules' => 'required' 
                ]
                
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        }

        if($id == null){
            $harga_layanan = new HargaLayananData(); 
            $harga_layanan->id_layanan = $this->post('id_layanan');
            $harga_layanan->id_jenis = $this->post('id_jenis'); 
            $harga_layanan->id_ukuran  = $this->post('id_ukuran'); 
            $harga_layanan->harga_layanan = $this->post('harga_layanan');

            $response = $this->HargaLayananModel->store($harga_layanan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $harga_layanan = new HargaLayananData(); 
            $harga_layanan->id_layanan = $this->post('id_layanan');
            $harga_layanan->id_jenis = $this->post('id_jenis'); 
            $harga_layanan->id_ukuran  = $this->post('id_ukuran'); 
            $harga_layanan->harga_layanan = $this->post('harga_layanan');

            $response = $this->HargaLayananModel->update($harga_layanan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    }

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->HargaLayananModel->destroy($id); 
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
            // Successfull validation will return the decoded harga_layanan data else returns false
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
Class HargaLayananData{ 
    public $id_layanan; 
    public $id_jenis; 
    public $id_ukuran; 
    public $harga_layanan;
}