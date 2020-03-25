<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailPengadaanModel extends CI_Model 
{ 
    private $table = 'detail_pengadaan'; 
    public $id_detail_pengadaan; 
    public $id_pengadaan; 
    public $id_produk; 
    public $jml_pengadaan_produk; 
    public $subtotal_pengadaan; 
    public $rule = [ 
        [ 
            'field' => 'id_pengadaan', 
            'label' => 'id_pengadaan', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'id_produk', 
            'label' => 'id_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'jml_pengadaan_produk', 
            'label' => 'jml_pengadaan_produk', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->id_pengadaan = $request->id_pengadaan; 
        $this->id_produk = $request->id_produk; 
        $this->jml_pengadaan_produk = $request->jml_pengadaan_produk;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_detail_pengadaan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_pengadaan' =>$request->id_pengadaan,
            'id_produk' =>$request->id_produk,
            'jml_pengadaan_produk' =>$request->jml_pengadaan_produk,
        ]; 
        if($this->db->where('id_detail_pengadaan',$id_detail_pengadaan)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_detail_pengadaan){ 
        if (empty($this->db->select('*')->where(array('id_detail_pengadaan' => $id_detail_pengadaan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'adaan_deleted_at' =>$now
        ]; 
        if($this->db->where('id_detail_pengadaan',$id_detail_pengadaan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }  
} 
?>