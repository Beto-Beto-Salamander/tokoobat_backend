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
        ],
        [ 
            'field' => 'role_pegawai', 
            'label' => 'role_pegawai', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'username', 
            'label' => 'username', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'password', 
            'label' => 'password', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_pegawai = $request->nama_pegawai; 
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->tgllahir_pegawai = $request->tgllahir_pegawai; 
        $this->telp_pegawai = $request->telp_pegawai;
        $this->role_pegawai = $request->role_pegawai;
        $this->username = $request->username; 
        // $this->password = password_hash($request->password, PASSWORD_BCRYPT);
        $this->password = md5($request->password);

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_pegawai) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_pegawai' =>$request->nama_pegawai,
            'alamat_pegawai' =>$request->alamat_pegawai,
            'tgllahir_pegawai' =>$request->tgllahir_pegawai,
            'telp_pegawai' =>$request->telp_pegawai,
            'role_pegawai' =>$request->role_pegawai,
            'username' =>$request->username,
            'password' =>password_hash($request->password, PASSWORD_BCRYPT),
            'peg_edited_at' =>$now
        ]; 
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($id_pegawai){ 
        if (empty($this->db->select('*')->where(array('id_pegawai' => $id_pegawai))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'peg_deleted_at' =>$now,
        ]; 
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }     

    public function verify($request){
        $pegawai = $this->db->select('*')->where(array('username' => $request->username,'password' => md5($request->password)))->get($this->table)->row_array();
        // if(!empty($pegawai) && password_verify($request->password , $pegawai['password'])) {
    if(!empty($this->db->select('*')->where(array('username' => $request->username))->get($this->table)->row_array())){
            if(!empty($this->db->select('*')->where(array('username' => $request->username,'password' => md5($request->password)))->get($this->table)->row_array())){
                return ['msg'=>$pegawai,'error'=>false];
            }else{
                return ['msg'=>'Password salah','error'=>true];
            }
        } else {
            return ['msg'=>'Username tidak ditemukan','error'=>true];
        }
    }

    public function is_unique_pegawai($username){
        if (empty($this->db->select('*')->where(array('username' => $username,'peg_deleted_at'=>null))->get($this->table)->row())) 
        return true;
        else
        return false;
    }

    
} 
?>