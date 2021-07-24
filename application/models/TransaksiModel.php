<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class TransaksiModel extends CI_Model 
{ 
    private $table = 'transaksi'; 
    public $id_transaksi; 
    public $tgl_transaksi;
    public $total_transaksi; 
    public $id_customer;
    public $status_transaksi;
    public $rule = [ 
        [ 
            'field' => 'id_customer', 
            'label' => 'id_customer', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'status_transaksi', 
            'label' => 'status_transaksi', 
            'rules' => 'required' 
        ], 
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $q = $this->db->query("SELECT MAX(RIGHT(id_transaksi,2)) AS kd_max FROM transaksi WHERE DATE(tgl_transaksi)=CURDATE()")->row();
        if($q){
            $tmp = ((int)$q->kd_max)+1;
            $kd = sprintf("%02s", $tmp);
        }else{
            $kd = "01";
        }
        $idbaru = 'PR-'.date('dmy').'-'.$kd;

        $now = date("Y-m-d");
        $this->id_transaksi = $idbaru; 
        $this->tgl_transaksi = $now; 
        $this->id_customer = $request->id_customer; 
        $this->status_transaksi = $request->status_transaksi;
     
      

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_transaksi) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
      
        if(!empty($request->tgl_pengadaan)){
            $updateData = [
                'id_customer' =>$request->id_customer,
                'tgl_transaksi' =>$request->tgl_transaksi,
                'id_customer' =>$request->id_customer,
                'status_transaksi' =>$request->status_transaksi,
                'transproduk_edited_at' =>$now,
            
            ]; 
        }else{
            $updateData = [
                'id_customer' =>$request->id_customer,
                'status_transaksi' =>$request->status_transaksi,
                'transproduk_edited_at' =>$now,
                
            ]; 
        }
        if($this->db->where('id_transaksi',$id_transaksi)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($id_transaksi){ 
        if (empty($this->db->select('*')->where(array('id_transaksi' => $id_transaksi))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'transproduk_deleted_at' =>$now,
           
        ]; 
        if($this->db->where('id_transaksi',$id_transaksi)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }  
} 
?>