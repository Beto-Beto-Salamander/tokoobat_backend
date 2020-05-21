<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailTLModel extends CI_Model 
{ 
    private $table = 'detail_trans_layanan'; 
    public $id_detail_layanan; 
    public $id_trans_layanan; 
    public $id_harga_layanan; 
    public $jumlah_beli_layanan; 
    public $subtotal_layanan; 
    public $rule = [ 
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
        $latest=$this->latest_get();
        $this->db->select('(harga_layanan)*'.$request->jumlah_beli_layanan.' as harga');
        $this->db->from('harga_layanan');
        $this->db->where('id_harga_layanan='.$request->id_harga_layanan);
        $query = $this->db->get();
        $total = $query->row();

        if(empty($request->id_trans_layanan))
            $this->id_trans_layanan = $latest->id_trans_layanan;   
        else
            $this->id_trans_layanan = $request->id_trans_layanan; 
            

        $this->id_harga_layanan = $request->id_harga_layanan; 
        $this->jumlah_beli_layanan = $request->jumlah_beli_layanan;
        $this->subtotal_layanan = $total->harga;
        if($this->db->insert($this->table, $this)){ 
            $this->setTotalTranslay($this->id_trans_layanan);
            return ['msg'=>'Berhasil Tambah','error'=>false];
        } 
        return ['msg'=>'Gagal Tambah','error'=>true]; 
    } 
    public function update($request,$id_detail_layanan) { 
        $this->db->select('(harga_layanan)*'.$request->jumlah_beli_layanan.' as harga');
        $this->db->from('harga_layanan');
        $this->db->where('id_harga_layanan='.$request->id_harga_layanan);
        $query = $this->db->get();
        $total = $query->row();

        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_harga_layanan' =>$request->id_harga_layanan,
            'jumlah_beli_layanan' =>$request->jumlah_beli_layanan,
            'subtotal_layanan' =>$total->harga
        ]; 
        if($this->db->where('id_detail_layanan',$id_detail_layanan)->update($this->table, $updateData)){ 
            $this->setTotalTranslay($request->id_trans_layanan);
            return ['msg'=>'Berhasil Edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal Edit','error'=>true]; 
    } 

    public function destroy($id_detail_layanan){ 
        $dataDetail=$this->db->select('*')->where(array('id_detail_layanan' => $id_detail_layanan))->get($this->table)->row();
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'translay_edited_at' =>$now
        ]; 

        if (empty($dataDetail)) 
            return ['msg'=>'Id tidak ditemukan','error'=>true]; 

        if($this->db->delete($this->table, array('id_detail_layanan' => $id_detail_layanan))){ 
            $this->db->where('id_trans_layanan',$dataDetail->id_trans_layanan)->update('transaksi_layanan', $deleteData);
            $this->setTotalTranslay($dataDetail->id_trans_layanan);
            return ['msg'=>'Berhasil Hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal Hapus','error'=>true]; 
    }   

    private function setTotalTranslay($id_trans_layanan){
        $this->db->select('sum(subtotal_layanan) as subtotal');
        $this->db->from('detail_trans_layanan');
        $this->db->where(array('id_trans_layanan'=>$id_trans_layanan));
        $query = $this->db->get();
        $total = $query->row();

        $this->db->where('id_trans_layanan',$id_trans_layanan)->update('transaksi_layanan', ['total_layanan' =>$total->subtotal]);
    }

    private function latest_get(){
        $this->db->select('id_trans_layanan from transaksi_layanan where translay_created_at=(select max(translay_created_at) from transaksi_layanan)');
        $query=$this->db->get();
        $data=$query->row();
        return $data; 
    }
} 
?>