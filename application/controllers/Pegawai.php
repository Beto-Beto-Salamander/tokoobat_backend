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
            return $this->returnData($this->db->get_where('pegawai',array('id_pegawai'))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('pegawai',array('id_pegawai' => $id))->result(), false);
        }
            
    } 

    public function verify_post()
    {
        $pegawai = new PegawaiData();
        $pegawai->email = $this->post('email');   
        $pegawai->password = $this->post('password');

        if ($result = $this->PegawaiModel->verify($pegawai)) { 
            $response = ['id' => $result['id'], 'full_name' => $result['full_name'], 'email' => $result['email']];
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
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        }

        // $pegawai = new PegawaiData(); 
        // $pegawai->nama_pegawai = $this->post('nama_pegawai'); 
        // $pegawai->alamat_pegawai  = $this->post('alamat_pegawai'); 
        // $pegawai->tgllahir_pegawai = $this->post('tgllahir_pegawai');
        // $pegawai->telp_pegawai = $this->post('telp_pegawai'); 
        // $pegawai->peg_deleted_at = $this->post('peg_edited_by');
        // $pegawai->peg_created_by = $this->post('peg_edited_by'); 
        // $pegawai->peg_edited_by = $this->post('peg_edited_by');
        // $pegawai->peg_deleted_by = $this->post('peg_edited_by');

        // if($id == null){ 
        //     $mail = new PHPMailer\PHPMailer\PHPMailer();
        //     $linkVerif = 'http://localhost/PAWTubesUAS/BackendTubes-master/index.php/pegawai/verifypegawai?email='.$emailpegawai;
        //     $mail-> IsSMTP();
        //     $mail-> SMTPSecure = 'tsl';
        //     $mail-> Host = 'smtp.gmail.com';
        //     $mail-> SMTPAuth = true;
        //     $mail-> Pegawainame = "barbarbershop.contact@gmail.com";
        //     $mail-> Password = "ZAQ123wsx*";
        //     $mail-> Port = 587;

        //     $mail-> setFrom('noreply@barbarbe.com', 'Barbarbershop');
        //     $mail-> addAddress($emailpegawai, $namepegawai);
        //     $mail-> Subject = "[BARBARBERSHOP EMAIL VERIFICATION]";
        //     $mail->isHTML(true);
        //     $mail->Body = 'Thank you for joining us!<br>Please click the link below to complete your pegawai registration
        //     <br><br><a href= "'.$linkVerif.'">Click here to verify</a><br><br>With Love,<br>Admin Barbarbershop';

        //     if($mail->send()){
        //         $response = $this->PegawaiModel->store($pegawai);
        //          return $this->returnData($response['msg'], $response['error']); 
        //     } else {
        //         return $this->returnData("Failed to send email","false");
        //     }
        // }else{ 
        //     $response = $this->PegawaiModel->update($pegawai,$id); 
        // } 

        if($id == null){
            $pegawai = new PegawaiData(); 
            $pegawai->nama_pegawai = $this->post('nama_pegawai'); 
            $pegawai->alamat_pegawai  = $this->post('alamat_pegawai'); 
            $pegawai->tgllahir_pegawai = $this->post('tgllahir_pegawai');
            $pegawai->telp_pegawai = $this->post('telp_pegawai');
            $pegawai->peg_created_by = $this->post('peg_created_by'); 

            $response = $this->PegawaiModel->store($pegawai);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $pegawai = new PegawaiData(); 
            $pegawai->nama_pegawai = $this->post('nama_pegawai'); 
            $pegawai->alamat_pegawai  = $this->post('alamat_pegawai'); 
            $pegawai->tgllahir_pegawai = $this->post('tgllahir_pegawai');
            $pegawai->telp_pegawai = $this->post('telp_pegawai');  
            $pegawai->peg_edited_by = $this->post('peg_edited_by');

            $response = $this->PegawaiModel->update($pegawai,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function verifypegawai_get(){
        $email=$this->get('email');
        $response=$this->PegawaiModel->verifypegawai($email);
        return $response;
    }

    public function index_delete($id = null){ 
        $pegawai = new PegawaiData(); 
        $pegawai->peg_deleted_by = $this->post('peg_deleted_by');

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->PegawaiModel->destroy($pegawai,$id); 
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
    public $peg_deleted_at; 
    public $peg_created_by; 
    public $peg_edited_by;
    public $peg_deleted_by;
}