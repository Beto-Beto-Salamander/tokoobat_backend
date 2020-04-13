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
    public $rule = [ 
        [ 
            'field' => 'id_supplier', 
            'label' => 'id_supplier', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'nama_produk', 
            'label' => 'nama_produk', 
            'rules' => 'required|callback_is_unique_produk' 
        ], 
        // [ 
        //     'field' => 'foto_produk', 
        //     'label' => 'foto_produk', 
        //     'rules' => 'required' 
        // ], 
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
        $this->id_supplier = $request->id_supplier; 
        $this->nama_produk = $request->nama_produk; 
        $this->foto_produk = $this->uploadImage();
        $this->harga_beli_produk = $request->harga_beli_produk; 
        $this->harga_jual_produk = $request->harga_jual_produk;
        $this->stok = $request->stok; 
        $this->min_stok = $request->min_stok;

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_produk) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $getProduk=$this->db->select('*')->where(array('id_produk' => $id_produk))->get($this->table)->row();
        $this->id_supplier = $request->id_supplier; 
        $this->nama_produk = $request->nama_produk; 
        $this->foto_produk = $this->uploadImage();
        $this->harga_beli_produk = $request->harga_beli_produk; 
        $this->harga_jual_produk = $request->harga_jual_produk;
        $this->stok = $request->stok;
        $this->min_stok = $request->min_stok;
        if(!empty($_FILES["foto_produk"])){
            if ($getProduk->foto_produk != "default.jpg") {
                $filename = explode(".", $getProduk->foto_produk)[0];
                array_map('unlink', glob(FCPATH."./upload/foto_produk/$filename.*"));
            }
            $updateData = [
                'id_supplier' => $this->id_supplier,
                'nama_produk' => $this->nama_produk,
                'foto_produk' => $this->uploadImage(),
                'harga_beli_produk' => $this->harga_beli_produk, 
                'harga_jual_produk' => $this->harga_jual_produk,
                'stok' => $this->stok,
                'min_stok' => $this->min_stok,
                'produk_edited_at' =>$now
            ];
            
            
        }else{
            $updateData = [
                'id_supplier' => $this->id_supplier,
                'nama_produk' => $this->nama_produk,
                'harga_beli_produk' => $this->harga_beli_produk, 
                'harga_jual_produk' => $this->harga_jual_produk,
                'stok' => $this->stok,
                'min_stok' => $this->min_stok,
                'produk_edited_at' =>$now
            ];
        }
        
        if($this->db->where('id_produk',$id_produk)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($id_produk){ 
        $getProduk=$this->db->select('*')->where(array('id_produk' => $id_produk))->get($this->table)->row();
        if (empty($getProduk)) 
        return ['msg'=>'Id Not Found','error'=>true]; 
        
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $deleteData = [
            'produk_deleted_at' =>$now
        ]; 
        if($this->db->where('id_produk',$id_produk)->update($this->table, $deleteData)){ 
            if ($getProduk->foto_produk != "default.jpg") {
                $filename = explode(".", $getProduk->foto_produk)[0];
                array_map('unlink', glob(FCPATH."./upload/foto_produk/$filename.*"));
            }
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }  

    public function is_unique_produk($nama_produk){
        if (empty($this->db->select('*')->where(array('nama_produk' => $nama_produk,'produk_deleted_at'=>null))->get($this->table)->row())) 
        return true;
        else
        return false;
    }

    private function uploadImage()
    {
        $config['upload_path']          = './upload/foto_produk/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
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