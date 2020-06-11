<?php
use Restserver \Libraries\REST_Controller ; 

Class TransaksiProduk extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('TransaksiProdukModel'); 
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
            $this->db->select('tp.id_trans_produk, tp.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, 
                                tp.id_hewan, h.nama_hewan, j.jenis, h.id_customer, c.nama_customer,
                                c.alamat_customer, c.telp_customer,
                                tp.tanggal_trans_produk, tp.diskon_produk, tp.total_produk, tp.status_penjualan_produk,
                                tp.transproduk_created_at, tp.transproduk_edited_at, tp.transproduk_deleted_at,
                                tp.transproduk_created_by, tp.transproduk_edited_by, tp.transproduk_deleted_by');
            $this->db->from('transaksi_produk as tp');
            $this->db->join('detail_trans_produk as dtp', 'tp.id_trans_produk = dtp.id_trans_produk');
            $this->db->join('pegawai as cs', 'tp.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tp.id_hewan = h.id_hewan','left');
            $this->db->join('jenis_hewan as j', 'h.id_jenis = j.id_jenis','left');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer', 'left');
            $this->db->where(array('tp.transproduk_deleted_at'=>null, 'tp.status_penjualan_produk'=>'Belum Lunas'));
            $this->db->order_by('tp.transproduk_created_at','DESC');
            $this->db->group_by('tp.id_trans_produk');
        }   
        else{
            $this->db->select('tp.id_trans_produk, tp.id_pegawai as id_cs, cs.nama_pegawai as nama_cs,
                                tp.id_hewan, h.nama_hewan, j.jenis, h.id_customer, c.nama_customer,
                                c.alamat_customer, c.telp_customer,
                                tp.tanggal_trans_produk, tp.diskon_produk, tp.total_produk, tp.status_penjualan_produk,
                                tp.transproduk_created_at, tp.transproduk_edited_at, tp.transproduk_deleted_at,
                                tp.transproduk_created_by, tp.transproduk_edited_by, tp.transproduk_deleted_by');
            $this->db->from('transaksi_produk as tp');
            $this->db->join('detail_trans_produk as dtp', 'tp.id_trans_produk = dtp.id_trans_produk');
            $this->db->join('pegawai as cs', 'tp.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tp.id_hewan = h.id_hewan', 'left');
            $this->db->join('jenis_hewan as j', 'h.id_jenis = j.id_jenis');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tp.id_trans_produk'=>$id,'tp.transproduk_deleted_at'=>null));
            $this->db->order_by('tp.tanggal_trans_produk','DESC');
            $this->db->group_by('tp.id_trans_produk');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 

    public function lunas_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('tp.id_trans_produk, tp.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tp.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tp.id_hewan, h.nama_hewan, j.jenis, h.id_customer, c.nama_customer,
                                c.alamat_customer, c.telp_customer,
                                tp.tanggal_trans_produk, tp.diskon_produk, tp.total_produk, tp.status_penjualan_produk,
                                tp.transproduk_created_at, tp.transproduk_edited_at, tp.transproduk_deleted_at,
                                tp.transproduk_created_by, tp.transproduk_edited_by, tp.transproduk_deleted_by');
            $this->db->from('transaksi_produk as tp');
            $this->db->join('detail_trans_produk as dtp', 'tp.id_trans_produk = dtp.id_trans_produk');
            $this->db->join('pegawai as cs', 'tp.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tp.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tp.id_hewan = h.id_hewan', 'left');
            $this->db->join('jenis_hewan as j', 'h.id_jenis = j.id_jenis');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tp.transproduk_deleted_at'=>null, 'tp.status_penjualan_produk'=>'Lunas'));
            $this->db->order_by('tp.transproduk_created_at','DESC');
            $this->db->group_by('tp.id_trans_produk');
        }   
        else{
            $this->db->select('tp.id_trans_produk, tp.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tp.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tp.id_hewan, h.nama_hewan, j.jenis, h.id_customer, c.nama_customer,
                                c.alamat_customer, c.telp_customer,
                                tp.tanggal_trans_produk, tp.diskon_produk, tp.total_produk, tp.status_penjualan_produk,
                                tp.transproduk_created_at, tp.transproduk_edited_at, tp.transproduk_deleted_at,
                                tp.transproduk_created_by, tp.transproduk_edited_by, tp.transproduk_deleted_by');
            $this->db->from('transaksi_produk as tp');
            $this->db->join('detail_trans_produk as dtp', 'tp.id_trans_produk = dtp.id_trans_produk');
            $this->db->join('pegawai as cs', 'tp.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tp.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tp.id_hewan = h.id_hewan', 'left');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tp.id_trans_produk'=>$id,'tp.transproduk_deleted_at'=>null));
            $this->db->order_by('tp.tanggal_trans_produk','DESC');
            $this->db->group_by('tp.id_trans_produk');
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
            // $this->db->distinct();
            $this->db->select('tp.id_trans_produk, tp.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tp.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tp.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tp.tanggal_trans_produk, tp.diskon_produk, tp.total_produk, tp.status_penjualan_produk,
                                tp.transproduk_created_at, tp.transproduk_edited_at, tp.transproduk_deleted_at,
                                tp.transproduk_created_by, tp.transproduk_edited_by, tp.transproduk_deleted_by');
            $this->db->from('transaksi_produk as tp');
            $this->db->join('detail_trans_produk as dtp', 'tp.id_trans_produk = dtp.id_trans_produk');
            $this->db->join('pegawai as cs', 'tp.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tp.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tp.id_hewan = h.id_hewan', 'left');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->order_by('tp.transproduk_created_at','DESC');
            $this->db->group_by('tp.id_trans_produk');
        }   
        else{
            $this->db->select('tp.id_trans_produk, tp.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tp.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tp.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tp.tanggal_trans_produk, tp.diskon_produk, tp.total_produk, tp.status_penjualan_produk,
                                tp.transproduk_created_at, tp.transproduk_edited_at, tp.transproduk_deleted_at,
                                tp.transproduk_created_by, tp.transproduk_edited_by, tp.transproduk_deleted_by');
            $this->db->from('transaksi_produk as tp');
            $this->db->join('detail_trans_produk as dtp', 'tp.id_trans_produk = dtp.id_trans_produk');
            $this->db->join('pegawai as cs', 'tp.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tp.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tp.id_hewan = h.id_hewan', 'left');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tp.id_trans_produk'=>$id));
            $this->db->order_by('tp.tanggal_trans_produk','DESC');
            $this->db->group_by('tp.id_trans_produk');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false); 
            
    } 

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->TransaksiProdukModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_hewan', 
                    'label' => 'id_hewan', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'status_penjualan_produk', 
                    'label' => 'status_penjualan_produk', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        } 

        if($id == null){
            $trans_produk = new TransaksiProdukData(); 
            $trans_produk->id_pegawai = $this->post('id_pegawai'); 
            $trans_produk->id_hewan  = $this->post('id_hewan'); 
            $trans_produk->status_penjualan_produk = $this->post('status_penjualan_produk');
            $trans_produk->transproduk_created_by = $this->post('transproduk_created_by');

            $response = $this->TransaksiProdukModel->store($trans_produk);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $trans_produk = new TransaksiProdukData(); 
            $trans_produk->id_hewan  = $this->post('id_hewan'); 
            $trans_produk->tanggal_trans_produk = $this->post('tanggal_trans_produk');
            $trans_produk->status_penjualan_produk = $this->post('status_penjualan_produk');
            $trans_produk->transproduk_edited_by = $this->post('transproduk_edited_by');

            $response = $this->TransaksiProdukModel->update($trans_produk,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 
        $transproduk = new TransaksiProdukData;
        $transproduk->transproduk_deleted_by = $this->post('transproduk_deleted_by');
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->TransaksiProdukModel->destroy($transproduk,$id); 
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
        // Successfull validation will return the decoded trans_produk data else returns false
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
Class TransaksiProdukData{ 
    public $tgl_trans_produk; 
    public $total_trans_produk; 
    public $status_trans_produk;
    public $transproduk_created_by; 
    public $transproduk_edited_by;
    public $transproduk_deleted_by;
}