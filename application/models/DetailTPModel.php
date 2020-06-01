<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailTPModel extends CI_Model 
{ 
    private $table = 'detail_trans_produk'; 
    public $id_detail_produk; 
    public $id_trans_produk; 
    public $id_produk; 
    public $jumlah_beli_produk; 
    public $subtotal_produk; 
    public $rule = [ 
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
        $latest=$this->latest_get();
        $this->db->select('(harga_jual_produk)*'.$request->jumlah_beli_produk.' as harga');
        $this->db->from('produk');
        $this->db->where('id_produk='.$request->id_produk);
        $query = $this->db->get();
        $total = $query->row();

        if(empty($request->id_trans_produk))
            $this->id_trans_produk = $latest->id_trans_produk;   
        else
            $this->id_trans_produk = $request->id_trans_produk; 
            

        $this->id_produk = $request->id_produk; 
        $this->jumlah_beli_produk = $request->jumlah_beli_produk;
        $this->subtotal_produk = $total->harga;
        if($this->db->insert($this->table, $this)){ 
            $this->setTotalTranslay($this->id_trans_produk);
            return ['msg'=>'Berhasil Tambah','error'=>false];
        } 
        return ['msg'=>'Gagal Tambah','error'=>true]; 
    } 
    public function update($request,$id_detail_produk) { 
        $this->db->select('(harga_jual_produk)*'.$request->jumlah_beli_produk.' as harga');
        $this->db->from('produk');
        $this->db->where('id_produk='.$request->id_produk);
        $query = $this->db->get();
        $total = $query->row();

        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_produk' =>$request->id_produk,
            'jumlah_beli_produk' =>$request->jumlah_beli_produk,
            'subtotal_produk' =>$total->harga
        ]; 
        if($this->db->where('id_detail_produk',$id_detail_produk)->update($this->table, $updateData)){ 
            $this->setTotalTranslay($request->id_trans_produk);
            return ['msg'=>'Berhasil Edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal Edit','error'=>true]; 
    } 

    public function destroy($id_detail_produk){ 
        $dataDetail=$this->db->select('*')->where(array('id_detail_produk' => $id_detail_produk))->get($this->table)->row();
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'transproduk_edited_at' =>$now
        ]; 

        if (empty($dataDetail)) 
            return ['msg'=>'Id tidak ditemukan','error'=>true]; 

        if($this->db->delete($this->table, array('id_detail_produk' => $id_detail_produk))){ 
            $this->db->where('id_trans_produk',$dataDetail->id_trans_produk)->update('transaksi_produk', $deleteData);
            $this->setTotalTranslay($dataDetail->id_trans_produk);
            return ['msg'=>'Berhasil Hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal Hapus','error'=>true]; 
    }   

    private function setTotalTranslay($id_trans_produk){
        $this->db->select('sum(subtotal_produk) as subtotal');
        $this->db->from('detail_trans_produk');
        $this->db->where(array('id_trans_produk'=>$id_trans_produk));
        $query = $this->db->get();
        $total = $query->row();

        $this->db->where('id_trans_produk',$id_trans_produk)->update('transaksi_produk', ['total_produk' =>$total->subtotal]);
    }

    private function latest_get(){
        $this->db->select('id_trans_produk from transaksi_produk where transproduk_created_at=(select max(transproduk_created_at) from transaksi_produk)');
        $query=$this->db->get();
        $data=$query->row();
        return $data; 
    }
} 
?>