<?php
use Restserver \Libraries\REST_Controller ; 

Class Supplier extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('SupplierModel'); 
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
            return $this->returnData($this->db->get_where('supplier',array('sup_deleted_at'=>null))->result(), false);
        }   
        else{
            return $this->returnData($this->db->get_where('supplier',array('id_supplier' => $id,'sup_deleted_at'=>null))->result(), false);
        }
            
    } 

    public function log_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            return $this->returnData($this->db->order_by('sup_deleted_at','ASC')->get_where('supplier')->result(), false);
        }   
        else{
            return $this->returnData($this->db->order_by('sup_deleted_at','ASC')->get_where('supplier',array('id_supplier' => $id))->result(), false);
        }
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->SupplierModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'nama_supplier', 
                    'label' => 'nama_supplier', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'alamat_supplier', 
                    'label' => 'alamat_supplier', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'telp_supplier', 
                    'label' => 'telp_supplier', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 

        if($id == null){
            $supplier = new SupplierData(); 
            $supplier->nama_supplier = $this->post('nama_supplier'); 
            $supplier->alamat_supplier  = $this->post('alamat_supplier'); 
            $supplier->telp_supplier = $this->post('telp_supplier');

            $response = $this->SupplierModel->store($supplier);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $supplier = new SupplierData(); 
            $supplier->nama_supplier = $this->post('nama_supplier'); 
            $supplier->alamat_supplier  = $this->post('alamat_supplier'); 
            $supplier->telp_supplier = $this->post('telp_supplier');  

            $response = $this->SupplierModel->update($supplier,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->SupplierModel->destroy($id); 
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
        // Successfull validation will return the decoded supplier data else returns false
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
Class SupplierData{ 
    public $nama_supplier; 
    public $alamat_supplier; 
    public $telp_supplier;
}