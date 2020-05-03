<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class PengadaanModel extends CI_Model 
{ 
    private $table = 'pengadaan'; 
    public $id_pengadaan; 
    public $tgl_pengadaan; 
    public $total_pengadaan; 
    public $status_pengadaan; 
    public $rule = [ 
        [ 
            'field' => 'status_pengadaan', 
            'label' => 'status_pengadaan', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $q = $this->db->query("SELECT MAX(RIGHT(id_pengadaan,2)) AS kd_max FROM pengadaan WHERE DATE(tgl_pengadaan)=CURDATE()")->row();
        if($q){
            $tmp = ((int)$q->kd_max)+1;
            $kd = sprintf("%02s", $tmp);
        }else{
            $kd = "01";
        }
        $idbaru = 'PO-'.date('Y-m-d').'-'.$kd;

        $now = date("Y-m-d");
        $this->id_pengadaan = $idbaru; 
        $this->tgl_pengadaan = $now; 
        $this->status_pengadaan = $request->status_pengadaan;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    
    public function update($request,$id_pengadaan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        if(!empty($request->tgl_pengadaan)){
            $updateData = [
                'tgl_pengadaan' =>$request->tgl_pengadaan,
                'status_pengadaan' =>$request->status_pengadaan,
                'adaan_edited_at' =>$now
            ]; 
        }else{
            $updateData = [
                'status_pengadaan' =>$request->status_pengadaan,
                'adaan_edited_at' =>$now
            ]; 
        }
        
        if($this->db->where('id_pengadaan',$id_pengadaan)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($id_pengadaan){ 
        if (empty($this->db->select('*')->where(array('id_pengadaan' => $id_pengadaan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'adaan_deleted_at' =>$now
        ]; 
        if($this->db->where('id_pengadaan',$id_pengadaan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }  
} 
?>