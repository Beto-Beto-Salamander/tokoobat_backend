<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class PegawaiModel extends CI_Model 
{ 
    private $table = 'pegawai'; 
    public $id_pegawai; 
    public $nama_pegawai; 
    public $alamat_pegawai; 
    public $tgllahir_pegawai; 
    public $telp_pegawai; 
    public $peg_deleted_at;
    public $peg_created_by;
    public $peg_edited_by;
    public $peg_deleted_by;
    public $rule = [ 
        [ 
            'field' => 'nama_pegawai', 
            'label' => 'nama_pegawai', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'alamat_pegawai', 
            'label' => 'alamat_pegawai', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'tgllahir_pegawai', 
            'label' => 'tgllahir_pegawai', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'telp_pegawai', 
            'label' => 'telp_pegawai', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_pegawai = $request->nama_pegawai; 
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->tgllahir_pegawai = $request->tgllahir_pegawai; 
        $this->telp_pegawai = $request->telp_pegawai;
        $this->peg_created_by = $request->peg_created_by;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_pegawai) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_pegawai' =>$request->nama_pegawai,
            'alamat_pegawai' =>$request->alamat_pegawai,
            'tgllahir_pegawai' =>$request->tgllahir_pegawai,
            'telp_pegawai' =>$request->telp_pegawai,
            'peg_edited_at' =>$now
        ]; 
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($request, $id_pegawai){ 
        if (empty($this->db->select('*')->where(array('id_pegawai' => $id_pegawai))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'peg_deleted_at' =>$now,
        ]; 
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }     

    public function verify($request){
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if(!empty($user) && password_verify($request->password , $user['password']) && $user['verified']==1) {
            return $user;
        } else {
            return false;
        }
    }

    public function verifyuser($email){
        $where=['email'=>$email];
        $update=['verified'=>1];
        $this->db->where($where)->update($this->table,$update);
            
    }

    // private function _uploadImage()
    // {
    //     $config['upload_path']          = './upload/profile_pict/';
    //     $config['allowed_types']        = 'gif|jpg|png';
    //     $config['file_name']            = $this->full_name;
    //     $config['overwrite']			= true;
    //     $config['max_size']             = 1024; // 1MB
    //     // $config['max_width']            = 1024;
    //     // $config['max_height']           = 768;

    //     $this->load->library('upload', $config);

    //     if ($this->upload->do_upload('profile_pict')) {
    //         return $this->upload->data("file_name");
    //     }else{
    //         return "default.jpg";
    //     }
    // }
} 
?>