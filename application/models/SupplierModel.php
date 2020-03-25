<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class SupplierModel extends CI_Model 
{ 
    private $table = 'supplier'; 
    public $id_supplier; 
    public $nama_supplier; 
    public $alamat_supplier; 
    public $tgllahir_supplier; 
    public $telp_supplier; 
    public $sup_deleted_at;
    public $rule = [ 
        [ 
            'field' => 'nama_supplier', 
            'label' => 'nama_supplier', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'alamat_supplier', 
            'label' => 'alamat_supplier', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'telp_supplier', 
            'label' => 'telp_supplier', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_supplier = $request->nama_supplier; 
        $this->alamat_supplier = $request->alamat_supplier; 
        $this->telp_supplier = $request->telp_supplier;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_supplier) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_supplier' =>$request->nama_supplier,
            'alamat_supplier' =>$request->alamat_supplier,
            'telp_supplier' =>$request->telp_supplier,
            'sup_edited_at' =>$now
        ]; 
        if($this->db->where('id_supplier',$id_supplier)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_supplier){ 
        if (empty($this->db->select('*')->where(array('id_supplier' => $id_supplier))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'sup_deleted_at' =>$now
        ]; 
        if($this->db->where('id_supplier',$id_supplier)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }  
} 
?>