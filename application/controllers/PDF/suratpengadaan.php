<?php
Class SuratPengadaan extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->library('pdf');
    }
    function _remap($param) {
        $this->index($param);
    }
    function index($param){
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'SEKOLAH MENENGAH KEJURUSAN NEEGRI 2 LANGSA'.$param,0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'DAFTAR SISWA KELAS IX JURUSAN REKAYASA PERANGKAT LUNAK',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(20,6,'NIM',1,0);
        $pdf->Cell(85,6,'NAMA MAHASISWA',1,0);
        $pdf->Cell(27,6,'NO HP',1,0);
        $pdf->Cell(25,6,'TANGGAL LHR',1,1);
        $pdf->SetFont('Arial','',10);
        $customer = $this->db->get('customer')->result();
        foreach ($customer as $row){
            $pdf->Cell(20,6,$row->nama_customer,1,0);
            $pdf->Cell(85,6,$row->alamat_customer,1,0);
            $pdf->Cell(27,6,$row->telp_customer,1,0);
            $pdf->Cell(25,6,$row->tgllahir_customer,1,1); 
        }
        $pdf->Output();
    }
}