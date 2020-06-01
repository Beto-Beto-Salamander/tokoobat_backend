<?php
use Restserver \Libraries\REST_Controller ; 

Class TransaksiLayanan extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization"); 
        parent::__construct(); 
        $this->load->model('TransaksiLayananModel'); 
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
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.translay_deleted_at'=>null));
            $this->db->order_by('tl.translay_created_at','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }   
        else{
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.id_trans_layanan'=>$id,'tl.translay_deleted_at'=>null));
            $this->db->order_by('tl.tanggal_trans_layanan','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);  
    } 

    public function belumselesai_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.translay_deleted_at'=>null,'tl.status_layanan'=>'Belum Selesai'));
            $this->db->order_by('tl.translay_created_at','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }   
        else{
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.id_trans_layanan'=>$id,'tl.translay_deleted_at'=>null,'tl.status_layanan'=>'Belum Selesai'));
            $this->db->order_by('tl.tanggal_trans_layanan','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);  
    } 

    public function selesai_get($id=null){ 
        // $data = $this->verify_request();
        $status = parent::HTTP_OK;
        // if($data['status'] == 401){
        //     return $this->returnData($data['msg'], true);
        // }
        if($id==null){
            // $this->db->distinct();
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.translay_deleted_at'=>null,'tl.status_layanan'=>'Selesai'));
            $this->db->order_by('tl.translay_created_at','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }   
        else{
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.id_trans_layanan'=>$id,'tl.translay_deleted_at'=>null,'tl.status_layanan'=>'Selesai'));
            $this->db->order_by('tl.tanggal_trans_layanan','DESC');
            $this->db->group_by('tl.id_trans_layanan');
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
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tl.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.translay_deleted_at'=>null,'tl.status_layanan'=>'Lunas'));
            $this->db->order_by('tl.translay_created_at','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }   
        else{
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tl.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.id_trans_layanan'=>$id,'tl.translay_deleted_at'=>null,'tl.status_layanan'=>'Lunas'));
            $this->db->order_by('tl.tanggal_trans_layanan','DESC');
            $this->db->group_by('tl.id_trans_layanan');
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
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tl.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->order_by('tl.translay_created_at','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }   
        else{
            $this->db->select('tl.id_trans_layanan, tl.id_pegawai as id_cs, cs.nama_pegawai as nama_cs, tl.peg_id_pegawai as id_kasir, 
                                kasir.nama_pegawai as nama_kasir, tl.id_hewan, h.nama_hewan, h.id_customer, c.nama_customer,
                                tl.tanggal_trans_layanan, tl.diskon_layanan, tl.total_layanan, tl.status_layanan,
                                tl.translay_created_at, tl.translay_edited_at, tl.translay_deleted_at,
                                tl.translay_created_by, tl.translay_edited_by, tl.translay_deleted_by');
            $this->db->from('transaksi_layanan as tl');
            $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
            $this->db->join('pegawai as cs', 'tl.id_pegawai = cs.id_pegawai');
            $this->db->join('pegawai as kasir', 'tl.peg_id_pegawai = kasir.id_pegawai');
            $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
            $this->db->join('customer as c', 'h.id_customer = c.id_customer');
            $this->db->where(array('tl.id_trans_layanan'=>$id));
            $this->db->order_by('tl.tanggal_trans_layanan','DESC');
            $this->db->group_by('tl.id_trans_layanan');
        }
        $query=$this->db->get();
        $data=$query->result_array();
        return $this->returnData($data, false);  
    } 

    public function groomingselesai_get(){
        $basic  = new \Nexmo\Client\Credentials\Basic('0241ff8d', 'o5KN3ZEqeBjJG8bs');
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => '6281233677490',
            'from' => 'hapie',
            'text' => 'hapie'
        ]);
    }

    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->TransaksiLayananModel->rules(); 
        if($id == null){ 
            array_push($rule,
                [ 
                    'field' => 'id_hewan', 
                    'label' => 'id_hewan', 
                    'rules' => 'required' 
                ], 
                [ 
                    'field' => 'status_layanan', 
                    'label' => 'status_layanan', 
                    'rules' => 'required' 
                ]
            ); 
        } 
        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->response($this->form_validation->error_array()); 
        } 
        if($id == null){
            $trans_layanan = new TransaksiLayananData(); 
            $trans_layanan->id_pegawai = $this->post('id_pegawai'); 
            $trans_layanan->id_hewan  = $this->post('id_hewan'); 
            $trans_layanan->status_layanan = $this->post('status_layanan');
            $trans_layanan->translay_created_by = $this->post('translay_created_by');

            $response = $this->TransaksiLayananModel->store($trans_layanan);
            return $this->returnData($response['msg'], $response['error']); 
        }else{ 
            $trans_layanan = new TransaksiLayananData(); 
            $trans_layanan->id_hewan  = $this->post('id_hewan'); 
            $trans_layanan->tanggal_trans_layanan = $this->post('tanggal_trans_layanan');
            $trans_layanan->status_layanan = $this->post('status_layanan');
            $trans_layanan->translay_edited_by = $this->post('translay_edited_by'); 

            $response = $this->TransaksiLayananModel->update($trans_layanan,$id); 
            return $this->returnData($response['msg'], $response['error']);
        } 
       
        
    } 

    public function delete_post($id = null){ 
        $translay = new TransaksiLayananData();
        $translay->translay_deleted_by = $this->post('translay_deleted_by');
        if($id == null){ 
            return $this->returnData('Id Parameter Not Found', true); 
        } 
        $response = $this->TransaksiLayananModel->destroy($translay,$id); 
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
        // Successfull validation will return the decoded trans_layanan data else returns false
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
Class TransaksiLayananData{ 
    public $id_pegawai; 
    public $peg_id_pegawai; 
    public $id_hewan; 
    public $tanggal_trans_layanan; 
    public $status_trans_layanan;
    public $translay_created_by; 
    public $translay_edited_by;
}