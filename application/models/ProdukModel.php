<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class ProdukModel extends CI_Model 
{ 
    private $table = 'produk'; 
    public $id_produk;
    public $id_supplier; 
    public $nama_produk; 
    public $foto_produk; 
    public $harga_beli_produk; 
    public $harga_jual_produk; 
    public $stok; 
    public $min_stok;
    public $produk_deleted_at;
    public $rule = [ 
        [ 
            'field' => 'id_supplier', 
            'label' => 'id_supplier', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'nama_produk', 
            'label' => 'nama_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'foto_produk', 
            'label' => 'foto_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'harga_beli_produk', 
            'label' => 'harga_beli_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'harga_jual_produk', 
            'label' => 'harga_jual_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'stok', 
            'label' => 'stok', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'min_stok', 
            'label' => 'min_stok', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $this->nama_produk = $request->nama_produk; 
        $this->foto_produk = $this->_uploadImage;
        $this->harga_beli_produk = $request->harga_beli_produk; 
        $this->harga_jual_produk = $request->harga_jual_produk;
        $this->stok = $request->stok; 
        $this->min_stok = $request->min_stok;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_produk) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'nama_produk' => $request->nama_produk,
            'foto_produk' => $this->_uploadImage,
            'harga_beli_produk' => $request->harga_beli_produk, 
            'harga_jual_produk' => $request->harga_jual_produk,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'produk_edited_at' =>$now
        ];
        if($this->db->where('id_produk',$id_produk)->update($this->table, $updateData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_produk){ 
        if (empty($this->db->select('*')->where(array('id_produk' => $id_produk))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'produk_deleted_at' =>$now
        ]; 
        if($this->db->where('id_produk',$id_produk)->update($this->table, $deleteData)){ 
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true];
    }  

    private function _uploadImage()
    {
        $config['upload_path']          = './upload/foto_produk/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $this->nama_produk;
        $config['overwrite']			= true;
        $config['max_size']             = 1024; // 1MB
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto_produk')) {
            return $this->upload->data("file_name");
        }else{
            return "default.jpg";
        }
    }
} 
?>