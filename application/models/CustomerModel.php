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
    public $cust_deleted_at;
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
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_customer) { 
        $updateData = [
            'nama_customer' =>$request->nama_customer,
            'alamat_customer' =>$request->alamat_customer,
            'tgllahir_customer' =>$request->tgllahir_customer,
            'telp_customer' =>$request->telp_customer,
            'cust_edited_by' =>$request->cust_edited_by
        ]; 
        if($this->db->where('id_customer',$id_customer)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($request, $id_customer){ 
        if (empty($this->db->select('*')->where(array('id_customer' => $id_customer))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        $deleteData = [
            'cust_deleted_at' =>'2020-03-02',
            'cust_deleted_by' =>$request->cust_deleted_by
        ]; 
        if($this->db->where('id_customer',$id_customer)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }     

    public function verify($request){
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if(!empty($user) && password_verify($request->password , $user['password']) && $user['verified']==1) {
            return $user;
        } else {
            return false;
        }
    }

    public function verifyuser($email){
        $where=['email'=>$email];
        $update=['verified'=>1];
        $this->db->where($where)->update($this->table,$update);
            
    }

    // private function _uploadImage()
    // {
    //     $config['upload_path']          = './upload/profile_pict/';
    //     $config['allowed_types']        = 'gif|jpg|png';
    //     $config['file_name']            = $this->full_name;
    //     $config['overwrite']			= true;
    //     $config['max_size']             = 1024; // 1MB
    //     // $config['max_width']            = 1024;
    //     // $config['max_height']           = 768;

    //     $this->load->library('upload', $config);

    //     if ($this->upload->do_upload('profile_pict')) {
    //         return $this->upload->data("file_name");
    //     }else{
    //         return "default.jpg";
    //     }
    // }
} 
?>