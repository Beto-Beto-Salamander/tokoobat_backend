<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DeviceModel extends CI_Model 
{ 
    private $table = 'device'; 
    public $id_device; 
    public $token; 
    public $rule = [ 
        [ 
            'field' => 'token', 
            'label' => 'token', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->token = $request->token;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_device) { 
        $updateData = [
            'token' =>$request->token
        ]; 
        if($this->db->where('id_device',$id_device)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($id_device){ 
        if (empty($this->db->select('*')->where(array('id_device' => $id_device))->get($this->table)->row())) 
            return ['msg'=>'Id Not Found','error'=>true]; 
        if($this->db->delete($this->table, array('id_device' => $id_device))){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }     

    public function is_unique_device($token){
        if (empty($this->db->select('*')->where(array('token' => $token,'lay_deleted_at'=>null))->get($this->table)->row())) 
        return true;
        else
        return false;
    }
} 
?>