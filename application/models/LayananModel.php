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
            'rules' => 'required|callback_is_unique_layanan' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_layanan = $request->nama_layanan;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_layanan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_layanan' =>$request->nama_layanan,
            'lay_edited_at' =>$now
        ]; 
        if($this->db->where('id_layanan',$id_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
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
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }     

    public function is_unique_layanan($nama_layanan){
        if (empty($this->db->select('*')->where(array('nama_layanan' => $nama_layanan,'lay_deleted_at'=>null))->get($this->table)->row())) 
        return true;
        else
        return false;
    }
} 
?>