<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LapModel extends CI_Model{
     
    public function produk(){
        $this->db->select('DISTINCT YEAR(tgl_transaksi) as Tahun')
        ->from('transaksi');
            return $this->db->get()->result();
    }
    
    public function pengadaan(){
        $this->db->select('DISTINCT YEAR(tgl_pengadaan) as Tahun')
        ->from('pengadaan');
            return $this->db->get()->result();
    }
    
 
    public function produkBulan(){
        $this->db->select('DISTINCT MONTHNAME(tgl_transaksi) as Bulan')
        ->from('transaksi');
            return $this->db->get()->result();
    }
    
    public function pengadaanBulan(){
        $this->db->select('DISTINCT MONTHNAME(tgl_pengadaan) as Bulan')
        ->from('pengadaan');
            return $this->db->get()->result();
    }
    
 
}
?>