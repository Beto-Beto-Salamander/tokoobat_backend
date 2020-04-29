<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DeviceModel extends CI_Model 
{ 
    private $table = 'device'; 
    public $id_device; 
    public $role; 
    public $token; 
    public $rule = [ 
        [ 
            'field' => 'role', 
            'label' => 'role', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'token', 
            'label' => 'token', 
            'rules' => 'required|is_unique[device.token]' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->role = $request->role;
        $this->token = $request->token;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_device) { 
        $updateData = [
            'role' =>$request->role,
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
} 
?>