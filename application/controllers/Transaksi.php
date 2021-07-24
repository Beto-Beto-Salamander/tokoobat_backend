<?php
use Restserver\Libraries\REST_Controller ; 

Class Transaksi extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('TransaksiModel'); 
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
            // $this->db->distinct();
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi, (select count(id_transaksi) from detail_transaksi where id_transaksi=t.id_transaksi) as jumlah_jenis,t.id_customer,c.nama_customer,c.alamat_customer, t.status_transaksi,
                                t.transproduk_created_at, t.transproduk_edited_at, t.transproduk_deleted_at');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            $this->db->where(array('t.transproduk_deleted_at'=>null));
            $this->db->order_by('t.transproduk_created_at','DESC');
            $this->db->group_by('t.id_transaksi');
        }   
        else{
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi,t.id_customer, t.status_transaksi');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 'm.id_customer = c.id_customer');
            $this->db->where(array('t.id_transaksi'=>$id,'t.transproduk_deleted_at'=>null));
            $this->db->group_by('t.id_transaksi');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 

    public function pending_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTt_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi, (select count(id_transaksi) from detail_transaksi where id_transaksi=t.id_transaksi) as jumlah_jenis,t.id_customer,c.nama_customer,c.alamat_customer, t.status_transaksi,
            t.transproduk_created_at, t.transproduk_edited_at, t.transproduk_deleted_at');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 't.id_customer = c.id_customer');
            $this->db->where(array('t.transproduk_deleted_at'=>null, 't.status_transaksi'=>'Pending'));
            $this->db->order_by('t.transproduk_created_at','DESC');
            $this->db->group_by('t.id_transaksi');
        }   
        else{
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi,t.id_customer, t.status_transaksi');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 'm.id_customer = c.id_customer');
            $this->db->where(array('t.id_transaksi'=>$id,'t.transproduk_deleted_at'=>null,'t.status_transaksi'=>'Pending'));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 
    public function belumsampai_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTt_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi, (select count(id_transaksi) from detail_transaksi where id_transaksi=t.id_transaksi) as jumlah_jenis,t.id_customer,c.nama_customer,c.alamat_customer, t.status_transaksi,
            t.transproduk_created_at, t.transproduk_edited_at, t.transproduk_deleted_at');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 't.id_customer = c.id_customer');
            $this->db->where(array('t.transproduk_deleted_at'=>null, 't.status_transaksi'=>'Belum Sampai'));
            $this->db->order_by('t.transproduk_created_at','DESC');
            $this->db->group_by('t.id_transaksi');
          }   
        else{
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi,t.id_customer, t.status_transaksi');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            $this->db->where(array('t.id_transaksi'=>$id,'t.transproduk_deleted_at'=>null,'t.status_transaksi'=>'Belum Sampai'));
    }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 
    public function sampai_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTt_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi, (select count(id_transaksi) from detail_transaksi where id_transaksi=t.id_transaksi) as jumlah_jenis,t.id_customer,c.nama_customer,c.alamat_customer, t.status_transaksi,
                                t.transproduk_created_at, t.transproduk_edited_at, t.transproduk_deleted_at');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 'h.id_customer = c.id_customer', 'left');
            $this->db->where(array('t.transproduk_deleted_at'=>null, 't.status_transaksi'=>'Sampai'));
            $this->db->order_by('t.transproduk_created_at','DESC');
            $this->db->group_by('t.id_transaksi');
        }   
        else{
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi,t.id_customer, t.status_transaksi');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('t.id_transaksi'=>$id,'t.transproduk_deleted_at'=>null));
            $this->db->group_by('t.id_transaksi');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 

    public function log_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTt_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi, (select count(id_transaksi) from detail_transaksi where id_transaksi=t.id_transaksi) as jumlah_jenis,t.id_customer,c.nama_customer,c.alamat_customer, t.status_transaksi,
                                t.transproduk_created_at, t.transproduk_edited_at, t.transproduk_deleted_at');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->order_by('t.transproduk_created_at','DESC');
           
        }   
        else{
            $this->db->select('t.id_transaksi,t.tgl_transaksi, t.total_transaksi,t.id_customer, t.status_transaksi');
            $this->db->from('transaksi as t');
            $this->db->join('detail_transaksi as dt', 't.id_transaksi = dt.id_transaksi');
            $this->db->join('produk as pro', 'dt.id_produk = pro.id_produk');
            $this->db->join('customer as c', 't.id_customer = c.id_customer');
            // $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('t.id_transaksi'=>$id));
        
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->TransaksiModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_customer', 
                    'label' => 'id_customer', 
                    'rules' => 'required' 
                ],
                [ 
                    'field' => 'status_transaksi', 
                    'label' => 'status_transaksi', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        } 

        if($id == null){
            $transaksi = new TransaksiData(); 
            $transaksi->status_transaksi = $this->post('status_transaksi');
            $transaksi->id_customer = $this->post('id_customer');
          

            $response = $this->TransaksiModel->store($transaksi);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $transaksi = new TransaksiData(); 
            $transaksi->tgl_transaksi = $this->post('tgl_transaksi');
            $transaksi->id_customer = $this->post('id_customer');
            $transaksi->status_transaksi = $this->post('status_transaksi');
       

            $response = $this->TransaksiModel->update($transaksi,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 
       
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->TransaksiModel->destroy($id); 
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
            $status = parent::HTt_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            return $response;
        }
    // $token = explode(" ",$header)[1];
    try {
        // Validate the token
        // Successfull validation will return the decoded trans_produk data else returns false
        $data = AUTHORIZATION::validateToken($header);
        if ($data === false) {
            $status = parent::HTt_UNAUTHORIZED;
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
            $status = parent::HTt_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            return $response;
        }
    }
} 
Class TransaksiData{ 
    public $tgl_transaksi; 
    public $id_customer;
    public $status_transaksi;
}