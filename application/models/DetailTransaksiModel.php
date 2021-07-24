<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailTransaksiModel extends CI_Model 
{ 
    private $table = 'detail_transaksi'; 
    public $id_detail_transaksi; 
    public $id_transaksi; 
    public $id_produk; 
    public $jml_transaksi_produk; 
    public $subtotal_transaksi; 
    public $rule = [ 
        [ 
            'field' => 'id_produk', 
            'label' => 'id_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'jml_transaksi_produk', 
            'label' => 'jml_transaksi_produk', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $latest=$this->latest_get();
        $this->db->select('(harga_jual_produk)*'.$request->jml_transaksi_produk.' as harga');
        $this->db->from('produk');
        $this->db->where('id_produk='.$request->id_produk);
        $query = $this->db->get();
        $total = $query->row();

        if(empty($request->id_transaksi))
            $this->id_transaksi = $latest->id_transaksi;   
        else
            $this->id_transaksi = $request->id_transaksi; 
            

        $this->id_produk = $request->id_produk; 
        $this->jml_transaksi_produk = $request->jml_transaksi_produk;
        $this->subtotal_transaksi = $total->harga;
        if($this->db->insert($this->table, $this)){ 
            $this->setTotalTransaksi($this->id_transaksi);
            return ['msg'=>'Berhasil Tambah','error'=>false];
        } 
        return ['msg'=>'Gagal Tambah','error'=>true]; 
    } 
    public function update($request,$id_detail_transaksi) { 
        $this->db->select('(harga_jual_produk)*'.$request->jml_transaksi_produk.' as harga');
        $this->db->from('produk');
        $this->db->where('id_produk='.$request->id_produk);
        $query = $this->db->get();
        $total = $query->row();

        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_produk' =>$request->id_produk,
            'jml_transaksi_produk' =>$request->jml_transaksi_produk,
            'subtotal_transaksi' =>$total->harga
        ]; 
        if($this->db->where('id_detail_transaksi',$id_detail_transaksi)->update($this->table, $updateData)){ 
            $this->setTotalTransaksi($request->id_transaksi);
            return ['msg'=>'Berhasil Edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal Edit','error'=>true]; 
    } 

    public function destroy($id_detail_transaksi){ 
        $dataDetail=$this->db->select('*')->where(array('id_detail_transaksi' => $id_detail_transaksi))->get($this->table)->row();
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'transproduk_edited_at' =>$now
        ]; 

        if (empty($dataDetail)) 
            return ['msg'=>'Id tidak ditemukan','error'=>true]; 

        if($this->db->delete($this->table, array('id_detail_transaksi' => $id_detail_transaksi))){ 
            $this->db->where('id_transaksi',$dataDetail->id_transaksi)->update('transaksi', $deleteData);
            $this->setTotalTransaksi($dataDetail->id_transaksi);
            return ['msg'=>'Berhasil Hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal Hapus','error'=>true]; 
    }   

    private function setTotalTransaksi($id_transaksi){
        $this->db->select('sum(subtotal_transaksi) as subtotal');
        $this->db->from('detail_transaksi');
        $this->db->where(array('id_transaksi'=>$id_transaksi));
        $query = $this->db->get();
        $total = $query->row();

        $this->db->where('id_transaksi',$id_transaksi)->update('transaksi', ['total_transaksi' =>$total->subtotal]);
    }

    private function latest_get(){
        $this->db->select('id_transaksi from transaksi where transproduk_created_at=(select max(transproduk_created_at) from transaksi)');
        $query=$this->db->get();
        $data=$query->row();
        return $data; 
    }
} 
?>