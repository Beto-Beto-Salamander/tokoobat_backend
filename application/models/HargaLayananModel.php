<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class HargaLayananModel extends CI_Model 
{ 
    private $table = 'harga_layanan'; 
    public $id_harga_layanan; 
    public $id_layanan; 
    public $id_jenis;
    public $id_ukuran;
    public $harga_layanan;
    
    public $rule = [ 
        [ 
            'field' => 'id_layanan', 
            'label' => 'id_layanan', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'id_jenis', 
            'label' => 'id_jenis', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'id_ukuran', 
            'label' => 'id_ukuran', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'harga_layanan', 
            'label' => 'harga_layanan', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->id_layanan = $request->id_layanan;
        $this->id_jenis = $request->id_jenis;
        $this->id_ukuran = $request->id_ukuran;
        $this->id_harga_layanan = $request->id_harga_layanan;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_harga_layanan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_layanan' => $request->id_layanan,
            'id_jenis' => $request->id_jenis,
            'id_ukuran' => $request->id_ukuran,
            'id_harga_layanan' => $request->id_harga_layanan,
            'harga_deleted_at' =>$now
        ]; 
        if($this->db->where('id_harga_layanan',$id_harga_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_harga_layanan){ 
        if (empty($this->db->select('*')->where(array('id_harga_layanan' => $id_harga_layanan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'harga_deleted_at' =>$now
        ]; 
        if($this->db->where('id_harga_layanan',$id_harga_layanan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }     
} 
?>