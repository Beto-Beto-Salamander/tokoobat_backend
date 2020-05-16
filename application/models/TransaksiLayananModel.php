<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class TransaksiLayananModel extends CI_Model 
{ 
    private $table = 'transaksi_layanan'; 
    public $id_trans_layanan; 
    public $id_pegawai; 
    public $peg_id_pegawai; 
    public $id_hewan; 
    public $tanggal_trans_layanan;
    public $diskon_layanan; 
    public $total_layanan; 
    public $status_layanan;
    public $translay_created_by; 
    public $translay_edited_by; 
    public $translay_deleted_by;
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
            'field' => 'tanggal_trans_layanan', 
            'label' => 'tanggal_trans_layanan', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'status_layanan', 
            'label' => 'status_layanan', 
            'rules' => 'required' 
        ], 
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $q = $this->db->query("SELECT MAX(RIGHT(id_trans_layanan,2)) AS kd_max FROM transaksi_layanan WHERE DATE(tanggal_trans_layanan)=CURDATE()");
        if($q){
            $tmp = ((int)$q->kd_max)+1;
            $kd = sprintf("%02s", $tmp);
        }else{
            $kd = "01";
        }
        $idbaru = 'LY-'.date('dmy').'-'.$kd;

        $now = date("Y-m-d");
        $this->id_trans_layanan = $idbaru; 
        $this->id_pegawai = $request->id_pegawai; 
        $this->id_hewan = $request->id_hewan;
        $this->tanggal_trans_layanan = $now; 
        $this->status_layanan = $request->status_layanan; 
        $this->translay_created_by = $request->translay_created_by; 

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_trans_layanan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        
        if(!empty($request->tgl_pengadaan)){
            $updateData = [
                'id_pegawai' =>$request->id_pegawai,
                'peg_id_pegawai' =>$request->peg_id_pegawai,
                'id_hewan' =>$request->id_hewan,
                'tanggal_trans_layanan' =>$request->tanggal_trans_layanan,
                'status_layanan' =>$request->status_layanan,
                'translay_edited_at' =>$now,
                'translay_edited_by' =>$$request->translay_edited_by
            ]; 
        }else{
            $updateData = [
                'id_pegawai' =>$request->id_pegawai,
                'peg_id_pegawai' =>$request->peg_id_pegawai,
                'id_hewan' =>$request->id_hewan,
                'status_layanan' =>$request->status_layanan,
                'translay_edited_at' =>$now,
                'translay_edited_by' =>$$request->translay_edited_by
            ]; 
        }
        if($this->db->where('id_trans_layanan',$id_trans_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($request,$id_trans_layanan){ 
        if (empty($this->db->select('*')->where(array('id_trans_layanan' => $id_trans_layanan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'translay_deleted_at' =>$now,
            'translay_deleted_by' =>$request->translay_deleted_by
        ]; 
        if($this->db->where('id_trans_layanan',$id_trans_layanan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }  
} 
?>