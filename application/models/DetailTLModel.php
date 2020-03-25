<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailLayananModel extends CI_Model 
{ 
    private $table = 'detail_trans_layanan'; 
    public $id_detail_layanan; 
    public $id_trans_layanan; 
    public $id_harga_layanan; 
    public $jumlah_beli_layanan; 
    public $subtotal_layanan; 
    public $rule = [ 
        [ 
            'field' => 'id_trans_layanan', 
            'label' => 'id_trans_layanan', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'id_harga_layanan', 
            'label' => 'id_harga_layanan', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'jumlah_beli_layanan', 
            'label' => 'jumlah_beli_layanan', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->id_trans_layanan = $request->id_trans_layanan; 
        $this->id_harga_layanan = $request->id_harga_layanan; 
        $this->jumlah_beli_layanan = $request->jumlah_beli_layanan;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_detail_layanan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_trans_layanan' =>$request->id_trans_layanan,
            'id_harga_layanan' =>$request->id_harga_layanan,
            'jumlah_beli_layanan' =>$request->jumlah_beli_layanan,
        ]; 
        if($this->db->where('id_detail_layanan',$id_detail_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_detail_layanan){ 
        if (empty($this->db->select('*')->where(array('id_detail_layanan' => $id_detail_layanan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'adaan_deleted_at' =>$now
        ]; 
        if($this->db->where('id_detail_layanan',$id_detail_layanan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }  
} 
?>