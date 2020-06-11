<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class TransaksiLayananModel extends CI_Model 
{ 
    private $table = 'transaksi_layanan'; 
    public $id_trans_layanan; 
    public $id_pegawai; 
    public $peg_id_pegawai; 
    public $id_hewan; 
    public $tanggal_trans_layanan;
    public $diskon_layanan; 
    public $total_layanan; 
    public $status_layanan;
    public $translay_created_by; 
    public $translay_edited_by; 
    public $translay_deleted_by;
    public $rule = [ 
        [ 
            'field' => 'id_hewan', 
            'label' => 'id_hewan', 
            'rules' => 'required' 
        ], 
        [ 
            'field' => 'status_layanan', 
            'label' => 'status_layanan', 
            'rules' => 'required' 
        ], 
    ]; 
    public function Rules() { return $this->rule; } 
    
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $q = $this->db->query("SELECT MAX(RIGHT(id_trans_layanan,2)) AS kd_max FROM transaksi_layanan WHERE DATE(tanggal_trans_layanan)=CURDATE()")->row();
        if($q){
            $tmp = ((int)$q->kd_max)+1;
            $kd = sprintf("%02s", $tmp);
        }else{
            $kd = "01";
        }
        $idbaru = 'LY-'.date('dmy').'-'.$kd;

        $now = date("Y-m-d");
        $this->id_trans_layanan = $idbaru; 
        $this->id_pegawai = $request->id_pegawai; 
        $this->id_hewan = $request->id_hewan;
        $this->tanggal_trans_layanan = $now; 
        $this->status_layanan = $request->status_layanan; 
        $this->translay_created_by = $request->translay_created_by; 

        if($this->db->insert($this->table, $this)){ 
            return ['msg'=>'Berhasil tambah','error'=>false];
        } 
        return ['msg'=>'Gagal tambah','error'=>true]; 
    } 
    public function update($request,$id_trans_layanan) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s");
        
        if(!empty($request->tgl_pengadaan)){
            $updateData = [
                'id_hewan' =>$request->id_hewan,
                'tanggal_trans_layanan' =>$request->tanggal_trans_layanan,
                'status_layanan' =>$request->status_layanan,
                'translay_edited_at' =>$now,
                'translay_edited_by' =>$request->translay_edited_by
            ]; 
        }else{
            $updateData = [
                'id_hewan' =>$request->id_hewan,
                'status_layanan' =>$request->status_layanan,
                'translay_edited_at' =>$now,
                'translay_edited_by' =>$request->translay_edited_by
            ]; 
        }
        if($this->db->where('id_trans_layanan',$id_trans_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Berhasil edit','error'=>false]; 
        } 
        return ['msg'=>'Gagal edit','error'=>true]; 
    } 

    public function destroy($request,$id_trans_layanan){ 
        if (empty($this->db->select('*')->where(array('id_trans_layanan' => $id_trans_layanan))->get($this->table)->row())) 
        return ['msg'=>'Id Not Found','error'=>true];
        date_default_timezone_set('Asia/Jakarta');
        $now = date("Y-m-d H:i:s"); 
        $deleteData = [
            'translay_deleted_at' =>$now,
            'translay_deleted_by' =>$request->translay_deleted_by
        ]; 
        if($this->db->where('id_trans_layanan',$id_trans_layanan)->update($this->table, $deleteData)){ 
            return ['msg'=>'Berhasil hapus','error'=>false]; 
        } 
        return ['msg'=>'Gagal hapus','error'=>true];
    }  

    public function konfirmasiselesai($id_trans_layanan){ 
        $this->db->select('c.telp_customer');
        $this->db->from('transaksi_layanan as tl');
        $this->db->join('detail_trans_layanan as dtl', 'tl.id_trans_layanan = dtl.id_trans_layanan');
        $this->db->join('hewan as h', 'tl.id_hewan = h.id_hewan');
        $this->db->join('customer as c', 'h.id_customer = c.id_customer');
        $this->db->where(array('tl.id_trans_layanan'=>$id_trans_layanan));
        $query=$this->db->get();
        $transaksi=$query->row();
        $nomor="62".substr($transaksi->telp_customer, 1);

        $this->db->select("hl.id_layanan, concat(l.nama_layanan, ' ', j.jenis, ' ', u.ukuran) as layanan");
        $this->db->from('detail_trans_layanan as dtl');
        $this->db->join('harga_layanan as hl', 'dtl.id_harga_layanan = hl.id_harga_layanan');
        $this->db->join('layanan as l', 'hl.id_layanan = l.id_layanan');
        $this->db->join('jenis_hewan as j', 'hl.id_jenis = j.id_jenis');
        $this->db->join('ukuran_hewan as u', 'hl.id_ukuran = u.id_ukuran');
        $this->db->where(array('dtl.id_trans_layanan'=>$id_trans_layanan));
        $query=$this->db->get();
        $detaillayanan=$query->result();

        $updateData = [
            'status_layanan' =>'Selesai'
        ]; 

        foreach ($detaillayanan as $row){
            if($row->id_layanan == 1)
            {
                $basic  = new \Nexmo\Client\Credentials\Basic('0241ff8d', 'o5KN3ZEqeBjJG8bs');
                $client = new \Nexmo\Client($basic);
        
                $message = $client->message()->send([
                    'to' => $nomor,
                    'from' => 'Kouvee',
                    'text' => 'Pelanggan yang terhormat, layanan '.$row->layanan.' telah selesai. Silahkan lakukan pembayaran di kasir Kouvee PetShop'
                ]);
            } 
        }
        if($this->db->where('id_trans_layanan',$id_trans_layanan)->update($this->table, $updateData)){ 
            return ['msg'=>'Konfirmasi Berhasil','error'=>false]; 
        } 
        return ['msg'=>'Konfirmasi gagal','error'=>true];
    } 
} 
?>