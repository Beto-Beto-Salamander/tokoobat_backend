<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class DetailPengadaanModel extends CI_Model 
{ 
    private $table = 'detail_pengadaan'; 
    public $id_detail_pengadaan; 
    public $id_pengadaan; 
    public $id_produk; 
    public $jml_pengadaan_produk; 
    public $subtotal_pengadaan; 
    public $rule = [ 
        [ 
            'field' => 'id_produk', 
            'label' => 'id_produk', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'satuan', 
            'label' => 'satuan', 
            'rules' => 'required' 
        ],
        [ 
            'field' => 'jml_pengadaan_produk', 
            'label' => 'jml_pengadaan_produk', 
            'rules' => 'required' 
        ]
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        $latest=$this->latest_get();
        $this->db->select('(harga_beli_produk)*'.$request->jml_pengadaan_produk.' as harga');
        $this->db->from('produk');
        $this->db->where('id_produk='.$request->id_produk);
        $query = $this->db->get();
        $total = $query->row();

        if($request->id_pengadaan==0)
            $this->id_pengadaan = $latest->id_pengadaan; 
        else
            $this->id_pengadaan = $request->id_pengadaan; 

        $this->id_produk = $request->id_produk; 
        $this->jml_pengadaan_produk = $request->jml_pengadaan_produk;
        $this->satuan = $request->satuan;
        $this->subtotal_pengadaan = $total->harga;
        if($this->db->insert($this->table, $this)){ 
            $this->setTotalPengadaan($latest->id_pengadaan);
            return ['msg'=>'Success','error'=>false];
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 
    public function update($request,$id_detail_pengadaan) { 
        $this->db->select('(harga_beli_produk)*'.$request->jml_pengadaan_produk.' as harga');
        $this->db->from('produk');
        $this->db->where('id_produk='.$request->id_produk);
        $query = $this->db->get();
        $total = $query->row();

        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        $updateData = [
            'id_produk' =>$request->id_produk,
            'jml_pengadaan_produk' =>$request->jml_pengadaan_produk,
            'satuan' =>$request->satuan,
            'subtotal_pengadaan' =>$total->harga
        ]; 
        if($this->db->where('id_detail_pengadaan',$id_detail_pengadaan)->update($this->table, $updateData)){ 
            $this->setTotalPengadaan($request->id_pengadaan);
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    } 

    public function destroy($id_detail_pengadaan){ 
        $dataDetail=$this->db->select('*')->where(array('id_detail_pengadaan' => $id_detail_pengadaan))->get($this->table)->row();
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'adaan_edited_at' =>$now
        ]; 

        if (empty($dataDetail)) 
            return ['msg'=>'Id tidak ditemukan','error'=>true]; 

        if($this->db->delete($this->table, array('id_detail_pengadaan' => $id_detail_pengadaan))){ 
            $this->db->where('id_pengadaan',$dataDetail->id_pengadaan)->update('pengadaan', $deleteData);
            $this->setTotalPengadaan($dataDetail->id_pengadaan);
            return ['msg'=>'Success','error'=>false]; 
        } 
        return ['msg'=>'Failed','error'=>true]; 
    }  

    private function setTotalPengadaan($id_pengadaan){
        $this->db->select('sum(subtotal_pengadaan) as subtotal');
        $this->db->from('detail_pengadaan');
        $this->db->where('id_pengadaan='.$id_pengadaan);
        $query = $this->db->get();
        $total = $query->row();

        $this->db->where('id_pengadaan',$id_pengadaan)->update('pengadaan', ['total_pengadaan' =>$total->subtotal]);
    }

    private function latest_get(){
        $this->db->select('id_pengadaan from pengadaan where adaan_created_at=(select max(adaan_created_at) from pengadaan)');
        $query=$this->db->get();
        $data=$query->row();
        return $data; 
    }
} 
?>