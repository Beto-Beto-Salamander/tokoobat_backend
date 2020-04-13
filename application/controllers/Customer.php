<?php
use Restserver \Libraries\REST_Controller ; 

Class Customer extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('CustomerModel'); 
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
            return $this->returnData($this->db->get_where('customer',array('cust_deleted_at'=>null))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('customer',array('id_customer' => $id,'cust_deleted_at' => null))->result(), false);
        }
            
    } 

    public function log_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->order_by('cust_deleted_at','ASC')->get_where('customer')->result(), false);
        }   
        else{
            return $this->returnData($this->db->order_by('cust_deleted_at','ASC')->get_where('customer',array('id_customer' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->CustomerModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'nama_customer', 
                    'label' => 'nama_customer', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'alamat_customer', 
                    'label' => 'alamat_customer', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'tgllahir_customer', 
                    'label' => 'tgllahir_customer', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'telp_customer', 
                    'label' => 'telp_customer', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        }

        // $customer = new CustomerData(); 
        // $customer->nama_customer = $this->post('nama_customer'); 
        // $customer->alamat_customer  = $this->post('alamat_customer'); 
        // $customer->tgllahir_customer = $this->post('tgllahir_customer');
        // $customer->telp_customer = $this->post('telp_customer'); 
        // $customer->cust_deleted_at = $this->post('cust_edited_by');
        // $customer->cust_created_by = $this->post('cust_edited_by'); 
        // $customer->cust_edited_by = $this->post('cust_edited_by');
        // $customer->cust_deleted_by = $this->post('cust_edited_by');

        // if($id == null){ 
        //     $mail = new PHPMailer\PHPMailer\PHPMailer();
        //     $linkVerif = 'http://localhost/PAWTubesUAS/BackendTubes-master/index.php/customer/verifycustomer?email='.$emailcustomer;
        //     $mail-> IsSMTP();
        //     $mail-> SMTPSecure = 'tsl';
        //     $mail-> Host = 'smtp.gmail.com';
        //     $mail-> SMTPAuth = true;
        //     $mail-> Customername = "barbarbershop.contact@gmail.com";
        //     $mail-> Password = "ZAQ123wsx*";
        //     $mail-> Port = 587;

        //     $mail-> setFrom('noreply@barbarbe.com', 'Barbarbershop');
        //     $mail-> addAddress($emailcustomer, $namecustomer);
        //     $mail-> Subject = "[BARBARBERSHOP EMAIL VERIFICATION]";
        //     $mail->isHTML(true);
        //     $mail->Body = 'Thank you for joining us!<br>Please click the link below to complete your customer registration
        //     <br><br><a href= "'.$linkVerif.'">Click here to verify</a><br><br>With Love,<br>Admin Barbarbershop';

        //     if($mail->send()){
        //         $response = $this->CustomerModel->store($customer);
        //          return $this->returnData($response['msg'], $response['error']); 
        //     } else {
        //         return $this->returnData("Failed to send email","false");
        //     }
        // }else{ 
        //     $response = $this->CustomerModel->update($customer,$id); 
        // } 

        if($id == null){
            $customer = new CustomerData(); 
            $customer->nama_customer = $this->post('nama_customer'); 
            $customer->alamat_customer  = $this->post('alamat_customer'); 
            $customer->tgllahir_customer = $this->post('tgllahir_customer');
            $customer->telp_customer = $this->post('telp_customer');
            $customer->cust_created_by = $this->post('cust_created_by'); 

            $response = $this->CustomerModel->store($customer);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $customer = new CustomerData(); 
            $customer->nama_customer = $this->post('nama_customer'); 
            $customer->alamat_customer  = $this->post('alamat_customer'); 
            $customer->tgllahir_customer = $this->post('tgllahir_customer');
            $customer->telp_customer = $this->post('telp_customer');  
            $customer->cust_edited_by = $this->post('cust_edited_by');

            $response = $this->CustomerModel->update($customer,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function verifycustomer_get(){
        $email=$this->get('email');
        $response=$this->CustomerModel->verifycustomer($email);
        return $response;
    }

    public function delete_post($id = null){ 
        $customer = new CustomerData(); 
        $customer->cust_deleted_by = $this->post('cust_deleted_by');
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->CustomerModel->destroy($customer,$id); 
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
        // Successfull validation will return the decoded customer data else returns false
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
Class CustomerData{ 
    public $nama_customer; 
    public $alamat_customer; 
    public $tgllahir_customer;
    public $telp_customer; 
    public $cust_created_by; 
    public $cust_edited_by;
    public $cust_deleted_by;
}