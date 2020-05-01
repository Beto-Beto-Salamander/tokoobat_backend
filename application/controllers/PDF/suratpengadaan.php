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
        $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        $detailpengadaan = $this->db->get('detail_pengadaan')->result();
        $pengadaan = $this->db->get('pengadaan')->result();
        $produk = $this->db->get('produk')->result();
        $supplier = $this->db->get('supplier')->result();
        foreach ($detailpengadaan as $row){
            if($row->id_pengadaan == $param)
            {
                foreach ($produk as $loop){
                    if($loop->id_produk == $row->id_produk)
                    {
                        foreach ($supplier as $ulang){
                            if($loop->id_supplier == $ulang->id_supplier)
                            {
                                $nama_supplier = $ulang->nama_supplier;
                                $alamat_supplier = $ulang->alamat_supplier;
                                $no_telp = $ulang->telp_supplier;
                            } 
                        }
                    } 
                }
            } 
        }
        foreach ($pengadaan as $row){
            if($row->id_pengadaan == $param)
            {
                $id_pengadaan = $row->id_pengadaan;
                $tgl = $row->adaan_created_at;
            } 
        }
        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        //setlocale(LC_TIME, 'id');
        //$month_name = date('F', mktime(0, 0, 0, $nowMonth));
        $id_p = sprintf("%02d", $id_pengadaan);
        $newDate = date("Y-m-d", strtotime($tgl));
        // setting jenis font yang akan digunakan
        $pdf->Image(APPPATH.'controllers/PDF/Logo/logo.jpg',20,10,-300);
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouveelogo.png',20,25,-800);
        $pdf->Cell(10,50,'',0,1);
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kotak.jpg',5,80,-700);
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'SURAT PEMESANAN',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(140);
        $pdf->Cell(30,8,'NO : PO-'.$newDate.'-'.$id_p,0,1);
        $pdf->Cell(140);
        $pdf->Cell(30,8,'Tanggal    :   '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(30,6,'Kepada Yth :',0,1);
        $pdf->Cell(30,6,$nama_supplier,0,1);
        $pdf->Cell(30,6,$alamat_supplier,0,1);
        $pdf->Cell(30,6,$no_telp,0,1);
        $pdf->Cell(10,10,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30);
        $pdf->Cell(30,6,'Mohon untuk disediakan produk-produk berikut ini :  ',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(50,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(50,6,'SATUAN',1,0,'C');
        $pdf->Cell(50,6,'JUMLAH',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;
        foreach ($detailpengadaan as $row){
            if($row->id_pengadaan == $param)
            {
                $pdf->Cell(10,10,$i,1,0,'C');
                foreach ($produk as $loop){
                    if($loop->id_produk == $row->id_produk)
                    {
                        $i++;
                        $pdf->Cell(50,10,$loop->nama_produk,1,0,'L');
                        $pdf->Cell(50,10,$row->satuan,1,0,'L');
                        $pdf->Cell(50,10,$row->jml_pengadaan_produk,1,1,'C');
                    } 
                }
            } 
        }
        date_default_timezone_set('Asia/Jakarta');
        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output('Surat_Pemesanan_Kouvee.pdf','I');
        //.$param
    }
}