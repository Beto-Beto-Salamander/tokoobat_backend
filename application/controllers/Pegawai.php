<?php
use Restserver \Libraries\REST_Controller ; 

Class Pegawai extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('PegawaiModel'); 
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
            return $this->returnData($this->db->get_where('pegawai',array('peg_deleted_at'=>null))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('pegawai',array('id_pegawai' => $id,'peg_deleted_at'=>null))->result(), false);
        }
            
    } 

    public function verify_post()
    {
        $pegawai = new PegawaiData();
        $pegawai->username = $this->post('username');   
        $pegawai->password = $this->post('password');

        if ($result = $this->PegawaiModel->verify($pegawai)) { 
            $response = ['id_pegawai' => $result['id_pegawai'], 'nama_pegawai' => $result['nama_pegawai']];
            return $this->response($response);
        } else {
            return $this->response('Failed');
        }
    }

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->PegawaiModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'nama_pegawai', 
                    'label' => 'nama_pegawai', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'alamat_pegawai', 
                    'label' => 'alamat_pegawai', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'tgllahir_pegawai', 
                    'label' => 'tgllahir_pegawai', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'telp_pegawai', 
                    'label' => 'telp_pegawai', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'role_pegawai', 
                    'label' => 'role_pegawai', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'username', 
                    'label' => 'username', 
                    'rules' => 'required|is_unique[pegawai.username]' 
                ],
                [ 
                    'field' => 'password', 
                    'label' => 'password', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        }

        if($id == null){
            $pegawai = new PegawaiData(); 
            $pegawai->nama_pegawai = $this->post('nama_pegawai'); 
            $pegawai->alamat_pegawai  = $this->post('alamat_pegawai'); 
            $pegawai->tgllahir_pegawai = $this->post('tgllahir_pegawai');
            $pegawai->telp_pegawai = $this->post('telp_pegawai');
            $pegawai->role_pegawai  = $this->post('role_pegawai'); 
            $pegawai->username = $this->post('username');
            $pegawai->password = $this->post('password');

            $response = $this->PegawaiModel->store($pegawai);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $pegawai = new PegawaiData(); 
            $pegawai->nama_pegawai = $this->post('nama_pegawai'); 
            $pegawai->alamat_pegawai  = $this->post('alamat_pegawai'); 
            $pegawai->tgllahir_pegawai = $this->post('tgllahir_pegawai');
            $pegawai->telp_pegawai = $this->post('telp_pegawai');  
            $pegawai->role_pegawai  = $this->post('role_pegawai'); 
            $pegawai->username = $this->post('username');
            $pegawai->password = $this->post('password');

            $response = $this->PegawaiModel->update($pegawai,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->PegawaiModel->destroy($id); 
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
        // Successfull validation will return the decoded pegawai data else returns false
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
Class PegawaiData{ 
    public $nama_pegawai; 
    public $alamat_pegawai; 
    public $tgllahir_pegawai;
    public $telp_pegawai;
    public $role_pegawai; 
    public $username;
    public $password;
}