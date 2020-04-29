<?php
use Restserver \Libraries\REST_Controller ; 

Class Pengadaan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('PengadaanModel'); 
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
            $this->db->distinct();
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, (select count(id_pengadaan) from detail_pengadaan where id_pengadaan=p.id_pengadaan) as jumlah_jenis, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.adaan_deleted_at'=>null));
            $this->db->order_by('p.tgl_pengadaan','DESC');
            // $this->db->group_by('p.id_pengadaan');
        }   
        else{
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.id_pengadaan'=>$id,'p.adaan_deleted_at'=>null));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
    } 

    public function pending_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            $this->db->distinct();
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan,(select count(id_pengadaan) from detail_pengadaan where id_pengadaan=p.id_pengadaan) as jumlah_jenis, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.adaan_deleted_at'=>null, 'p.status_pengadaan'=>'Pending'));
            $this->db->order_by('p.tgl_pengadaan','DESC');
        }   
        else{
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.id_pengadaan'=>$id,'p.adaan_deleted_at'=>null,'p.status_pengadaan'=>'Pending'));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
    } 

    public function belumsampai_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            $this->db->distinct();
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, (select count(id_pengadaan) from detail_pengadaan where id_pengadaan=p.id_pengadaan) as jumlah_jenis, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.adaan_deleted_at'=>null, 'p.status_pengadaan'=>'Belum Sampai'));
            $this->db->order_by('p.tgl_pengadaan','DESC');
        }   
        else{
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.id_pengadaan'=>$id,'p.adaan_deleted_at'=>null,'p.status_pengadaan'=>'Belum Sampai'));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
    } 

    public function sampai_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            $this->db->distinct();
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, (select count(id_pengadaan) from detail_pengadaan where id_pengadaan=p.id_pengadaan) as jumlah_jenis, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.adaan_deleted_at'=>null, 'p.status_pengadaan'=>'Sampai'));
            $this->db->order_by('p.tgl_pengadaan','DESC');
        }   
        else{
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, p.status_pengadaan, s.id_supplier, s.nama_supplier');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.id_pengadaan'=>$id,'p.adaan_deleted_at'=>null,'p.status_pengadaan'=>'Sampai'));
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
            $this->db->distinct();
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, (select count(id_pengadaan) from detail_pengadaan where id_pengadaan=p.id_pengadaan) as jumlah_jenis, p.status_pengadaan, s.id_supplier, s.nama_supplier,
                                p.adaan_created_at, p.adaan_edited_at, p.adaan_deleted_at');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->order_by('p.tgl_pengadaan','DESC');
        }   
        else{
            $this->db->select('p.id_pengadaan, p.tgl_pengadaan, p.total_pengadaan, p.status_pengadaan, s.id_supplier, s.nama_supplier,
                                p.adaan_created_at, p.adaan_edited_at, p.adaan_deleted_at');
            $this->db->from('pengadaan as p');
            $this->db->join('detail_pengadaan as dp', 'p.id_pengadaan = dp.id_pengadaan');
            $this->db->join('produk as pro', 'dp.id_produk = pro.id_produk');
            $this->db->join('supplier as s', 'pro.id_supplier = s.id_supplier');
            $this->db->where(array('p.id_pengadaan'=>$id));
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->PengadaanModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'status_pengadaan', 
                    'label' => 'status_pengadaan', 
                    'rules' => 'required' 
                ] 
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        } 

        if($id == null){
            $pengadaan = new PengadaanData(); 
            $pengadaan->status_pengadaan = $this->post('status_pengadaan');

            $response = $this->PengadaanModel->store($pengadaan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $pengadaan = new PengadaanData(); 
            $pengadaan->tgl_pengadaan = $this->post('tgl_pengadaan'); 
            $pengadaan->status_pengadaan = $this->post('status_pengadaan');  

            $response = $this->PengadaanModel->update($pengadaan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 

        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->PengadaanModel->destroy($id); 
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
        // Successfull validation will return the decoded pengadaan data else returns false
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
Class PengadaanData{ 
    public $tgl_pengadaan; 
    public $status_pengadaan;
}