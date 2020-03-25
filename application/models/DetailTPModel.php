<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailProdukModel extends CI_Model 
{ 
    private $table = 'detail_trans_produk'; 
    public $id_detail_produk; 
    public $id_trans_produk; 
    public $id_produk; 
    public $jumlah_beli_produk; 
    public $subtotal_produk; 
    public $rule = [ 
        [ 
            'field' => 'id_trans_produk', 
            'label' => 'id_trans_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'id_produk', 
            'label' => 'id_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'jumlah_beli_produk', 
            'label' => 'jumlah_beli_produk', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->id_trans_produk = $request->id_trans_produk; 
        $this->id_produk = $request->id_produk; 
        $this->jumlah_beli_produk = $request->jumlah_beli_produk;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_detail_produk) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_trans_produk' =>$request->id_trans_produk,
            'id_produk' =>$request->id_produk,
            'jumlah_beli_produk' =>$request->jumlah_beli_produk,
        ]; 
        if($this->db->where('id_detail_produk',$id_detail_produk)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_detail_produk){ 
        if (empty($this->db->select('*')->where(array('id_detail_produk' => $id_detail_produk))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'adaan_deleted_at' =>$now
        ]; 
        if($this->db->where('id_detail_produk',$id_detail_produk)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }  
} 
?>