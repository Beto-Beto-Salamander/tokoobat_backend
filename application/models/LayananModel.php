<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class LayananModel extends CI_Model 
{ 
    private $table = 'layanan'; 
    public $id_layanan; 
    public $nama_layanan; 
    public $rule = [ 
        [ 
            'field' => 'nama_layanan', 
            'label' => 'nama_layanan', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_layanan = $request->nama_layanan;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_layanan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_layanan' =>$request->nama_layanan,
            'lay_edited_at' =>$now
        ]; 
        if($this->db->where('id_layanan',$id_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_layanan){ 
        if (empty($this->db->select('*')->where(array('id_layanan' => $id_layanan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'lay_deleted_at' =>$now
        ]; 
        if($this->db->where('id_layanan',$id_layanan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }     
} 
?>