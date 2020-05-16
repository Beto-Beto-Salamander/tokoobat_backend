<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class HewanModel extends CI_Model 
{ 
    private $table = 'hewan'; 
    public $id_hewan; 
    public $id_customer; 
    public $id_jenis;
    public $nama_hewan; 
    public $tgl_lahir_hewan; 
    public $hwn_created_by;
    public $hwn_edited_by;
    public $hwn_deleted_by;
    public $rule = [ 
        [ 
            'field' => 'id_customer', 
            'label' => 'id_customer', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'id_jenis', 
            'label' => 'id_jenis', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'nama_hewan', 
            'label' => 'nama_hewan', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'tgl_lahir_hewan', 
            'label' => 'tgl_lahir_hewan', 
            'rules' => 'required' 
        ], 
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->id_customer = $request->id_customer;
        $this->id_jenis = $request->id_jenis;
        $this->nama_hewan = $request->nama_hewan; 
        $this->tgl_lahir_hewan = $request->tgl_lahir_hewan; 
        $this->hwn_created_by = $request->hwn_created_by;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_hewan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_customer' =>$request->id_customer,
            'id_jenis' =>$request->id_jenis,
            'nama_hewan' =>$request->nama_hewan,
            'tgl_lahir_hewan' =>$request->tgl_lahir_hewan,
            'hwn_edited_by' =>$request->hwn_edited_by,
            'hwn_edited_at' =>$now
        ]; 
        if($this->db->where('id_hewan',$id_hewan)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($request, $id_hewan){ 
        if (empty($this->db->select('*')->where(array('id_hewan' => $id_hewan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'hwn_deleted_at' =>$now,
            'hwn_deleted_by' =>$request->hwn_deleted_by
        ]; 
        if($this->db->where('id_hewan',$id_hewan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }     
} 
?>