<?php
use Restserver \Libraries\REST_Controller ; 

Class Produk extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('ProdukModel'); 
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
            $this->db->select('p.id_produk, p.id_supplier, s.nama_supplier, p.nama_produk, p.foto_produk, p.harga_beli_produk, p.harga_jual_produk, p.stok, p.min_stok');
            $this->db->from('produk as p');
            $this->db->join('supplier as s', 'p.id_supplier = s.id_supplier');
            $this->db->where(array('produk_deleted_at'=>null));
            // return $this->returnData($this->db->get_where('produk',array('produk_deleted_at'=>null))->result(), false);
        }   
        else{
            $this->db->select('p.id_produk, p.id_supplier, s.nama_supplier, p.nama_produk, p.foto_produk, p.harga_beli_prodk, p.harga_jual_produk, p.stok, p.min_stok');
            $this->db->from('produk as p');
            $this->db->join('supplier as s', 'p.id_supplier = s.id_supplier');
            $this->db->where(array('id_produk' => $id,'produk_deleted_at'=>null));
            // return $this->returnData($this->db->get_where('produk',array('id_produk' => $id,'produk_deleted_at'=>null))->result(), false);
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
            $this->db->select('p.id_produk, p.id_supplier, s.nama_supplier, p.nama_produk, p.foto_produk, p.harga_beli_produk, p.harga_jual_produk, p.stok, p.min_stok, p.produk_created_at, p.produk_edited_at, p.produk_deleted_at');
            $this->db->from('produk as p');
            $this->db->join('supplier as s', 'p.id_supplier = s.id_supplier');
            $this->db->order_by('produk_deleted_at','ASC');
        }   
        else{
            $this->db->select('p.id_produk, p.id_supplier, s.nama_supplier, p.nama_produk, p.foto_produk, p.harga_beli_produk, p.harga_jual_produk, p.stok, p.min_stok, p.produk_created_at, p.produk_edited_at, p.produk_deleted_at');
            $this->db->from('produk as p');
            $this->db->join('supplier as s', 'p.id_supplier = s.id_supplier');
            $this->db->where(array('id_produk' => $id));
            $this->db->order_by('produk_deleted_at','ASC');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->ProdukModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_supplier', 
                    'label' => 'id_supplier', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'nama_produk', 
                    'label' => 'nama_produk', 
                    'rules' => 'required' 
                ], 
                // [ 
                //     'field' => 'foto_produk', 
                //     'label' => 'foto_produk', 
                //     'rules' => 'required' 
                // ],
                [ 
                    'field' => 'harga_beli_produk', 
                    'label' => 'harga_beli_produk', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'harga_jual_produk', 
                    'label' => 'harga_jual_produk', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'stok', 
                    'label' => 'stok', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'min_stok', 
                    'label' => 'min_stok', 
                    'rules' => 'required' 
                ]
                
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        }

        if($id == null){
            $produk = new ProdukData(); 
            $produk->id_supplier = $this->post('id_supplier');
            $produk->nama_produk = $this->post('nama_produk'); 
            $produk->foto_produk  = $this->post('foto_produk'); 
            $produk->harga_beli_produk = $this->post('harga_beli_produk');
            $produk->harga_jual_produk = $this->post('harga_jual_produk');
            $produk->stok = $this->post('stok'); 
            $produk->min_stok  = $this->post('min_stok');

            $response = $this->ProdukModel->store($produk);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $produk = new ProdukData(); 
            $produk->id_supplier = $this->post('id_supplier');
            $produk->nama_produk = $this->post('nama_produk'); 
            $produk->foto_produk  = $this->post('foto_produk'); 
            $produk->harga_beli_produk = $this->post('harga_beli_produk');
            $produk->harga_jual_produk = $this->post('harga_jual_produk');
            $produk->stok = $this->post('stok'); 
            $produk->min_stok  = $this->post('min_stok');

            $response = $this->ProdukModel->update($produk,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    }

    public function delete_post($id = null){ 
        $produk = new ProdukData(); 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->ProdukModel->destroy($id); 
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
            // Successfull validation will return the decoded produk data else returns false
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
Class ProdukData{ 
    public $id_supplier; 
    public $nama_produk; 
    public $foto_produk; 
    public $harga_jual_produk;
    public $harga_beli_produk;
    public $stok; 
    public $min_stok; 
}