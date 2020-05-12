<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class TransaksiProdukModel extends CI_Model 
{ 
    private $table = 'transaksi_produk'; 
    public $id_trans_produk; 
    public $id_pegawai; 
    public $peg_id_pegawai; 
    public $id_hewan; 
    public $tanggal_trans_produk;
    public $diskon_produk; 
    public $total_produk; 
    public $status_penjualan_produk;
    public $transproduk_created_by; 
    public $transproduk_edited_by; 
    public $transproduk_deleted_by;
    public $rule = [ 
        [ 
            'field' => 'id_pegawai', 
            'label' => 'id_pegawai', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'id_hewan', 
            'label' => 'id_hewan', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'status_penjualan_produk', 
            'label' => 'status_penjualan_produk', 
            'rules' => 'required' 
        ], 
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d");
        $q = $this->db->query("SELECT MAX(RIGHT(id_trans_produk,2)) AS kd_max FROM transaksi_produk WHERE DATE(tanggal_trans_produk)=CURDATE()");
        if($q){
            $tmp = ((int)$q->kd_max)+1;
            $kd = sprintf("%02s", $tmp);
        }else{
            $kd = "01";
        }
        $idbaru = 'PR-'.date('dmy').'-'.$kd;
        
        $this->id_trans_produk = $idbaru; 
        $this->id_pegawai = $request->id_pegawai;  
        $this->id_hewan = $request->id_hewan;
        $this->tanggal_trans_produk = $now; 
        $this->status_penjualan_produk = $request->status_penjualan_produk; 
        $this->transproduk_created_by = $request->transproduk_created_by; 

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_trans_produk) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        if(!empty($request->tgl_pengadaan)){
            $updateData = [
                'id_pegawai' =>$request->id_pegawai,
                'peg_id_pegawai' =>$request->peg_id_pegawai,
                'id_hewan' =>$request->id_hewan,
                'tanggal_trans_produk' =>$request->tanggal_trans_produk,
                'status_penjualan_produk' =>$request->status_penjualan_produk,
                'transproduk_edited_at' =>$now,
                'transproduk_edited_by' =>$$request->transproduk_edited_by
            ]; 
        }else{
            $updateData = [
                'id_pegawai' =>$request->id_pegawai,
                'peg_id_pegawai' =>$request->peg_id_pegawai,
                'id_hewan' =>$request->id_hewan,
                'status_penjualan_produk' =>$request->status_penjualan_produk,
                'transproduk_edited_at' =>$now,
                'transproduk_edited_by' =>$$request->transproduk_edited_by
            ]; 
        }
        if($this->db->where('id_trans_produk',$id_trans_produk)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($request,$id_trans_produk){ 
        if (empty($this->db->select('*')->where(array('id_trans_produk' => $id_trans_produk))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'transproduk_deleted_at' =>$now,
            'transproduk_deleted_by' =>$$request->transproduk_deleted_by
        ]; 
        if($this->db->where('id_trans_produk',$id_trans_produk)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }  
} 
?>