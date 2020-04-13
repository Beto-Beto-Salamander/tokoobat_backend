<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class CustomerModel extends CI_Model 
{ 
    private $table = 'customer'; 
    public $id_customer; 
    public $nama_customer; 
    public $alamat_customer; 
    public $tgllahir_customer; 
    public $telp_customer; 
    public $cust_created_by;
    public $cust_edited_by;
    public $cust_deleted_by;
    public $rule = [ 
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
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_customer = $request->nama_customer; 
        $this->alamat_customer = $request->alamat_customer;
        $this->tgllahir_customer = $request->tgllahir_customer; 
        $this->telp_customer = $request->telp_customer;
        $this->cust_created_by = $request->cust_created_by;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_customer) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_customer' =>$request->nama_customer,
            'alamat_customer' =>$request->alamat_customer,
            'tgllahir_customer' =>$request->tgllahir_customer,
            'telp_customer' =>$request->telp_customer,
            'cust_edited_by' =>$request->cust_edited_by,
            'cust_edited_at' =>$now
        ]; 
        if($this->db->where('id_customer',$id_customer)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($request, $id_customer){ 
        if (empty($this->db->select('*')->where(array('id_customer' => $id_customer))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'cust_deleted_by' =>$request->cust_deleted_by,
            'cust_deleted_at' =>$now
        ]; 
        if($this->db->where('id_customer',$id_customer)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }     

    public function verify($request){
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if(!empty($user) && password_verify($request->password , $user['password']) && $user['verified']==1) {
            return $user;
        } else {
            return false;
        }
    }
} 
?>