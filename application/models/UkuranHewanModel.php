<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class UkuranHewanModel extends CI_Model 
{ 
    private $table = 'ukuran_hewan'; 
    public $id_ukuran; 
    public $ukuran; 
    public $rule = [ 
        [ 
            'field' => 'ukuran', 
            'label' => 'ukuran', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->ukuran = $request->ukuran;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_ukuran) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'ukuran' =>$request->ukuran,
            'ukrn_edited_at' =>$now
        ]; 
        if($this->db->where('id_ukuran',$id_ukuran)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_ukuran){ 
        if (empty($this->db->select('*')->where(array('id_ukuran' => $id_ukuran))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'ukrn_deleted_at' =>$now
        ]; 
        if($this->db->where('id_ukuran',$id_ukuran)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }     
} 
?>