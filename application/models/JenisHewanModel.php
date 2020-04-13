<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class JenisHewanModel extends CI_Model 
{ 
    private $table = 'jenis_hewan'; 
    public $id_jenis; 
    public $jenis; 
    public $rule = [ 
        [ 
            'field' => 'jenis', 
            'label' => 'jenis', 
            'rules' => 'required|callback_is_unique_jenis' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->jenis = $request->jenis;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal Tambah','error'=>true]; 
    } 
    public function update($request,$id_jenis) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'jenis' =>$request->jenis,
            'jns_edited_at' =>$now
        ]; 
        if($this->db->where('id_jenis',$id_jenis)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($id_jenis){ 
        if (empty($this->db->select('*')->where(array('id_jenis' => $id_jenis))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'jns_deleted_at' =>$now
        ]; 
        if($this->db->where('id_jenis',$id_jenis)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }     

    public function is_unique_jenis($jenis){
        if (empty($this->db->select('*')->where(array('jenis' => $jenis,'jns_deleted_at'=>null))->get($this->table)->row())) 
        return true;
        else
        return false;
    }
} 
?>