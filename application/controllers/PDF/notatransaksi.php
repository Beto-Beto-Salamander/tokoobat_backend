<?php
Class NotaTransaksi extends CI_Controller{
    
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
        $detailtransaksi = $this->db->get('detail_transaksi')->result();
        $transaksi = $this->db->get('transaksi')->result();
        $produk = $this->db->get('produk')->result();
        // $customer = $this->db->get('customer')->result();
        foreach ($detailtransaksi as $row){
            if($row->id_transaksi == $param)
            {
                // foreach ($produk as $loop){
                //     if($loop->id_produk == $row->id_produk)
                //     {
                        // foreach ($customer as $ulang){
                        //     if($loop->id_customer == $ulang->id_customer)
                        //     {
                        //         $nama_customer = $ulang->nama_customer;
                        //         $alamat_customer = $ulang->alamat_supplier;
                        //         $telp_customer = $ulang->telp_customer;
                        //     } 
                        // }
                    // } 
                }
            } 
        }
        foreach ($transaksi as $row){
            if($row->id_transaksi == $param)
            {
                $id_transaksi = $row->id_transaksi;
                $tgl = $row->transproduk_created_at;
            } 
        }
        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        //setlocale(LC_TIME, 'id');
        //$month_name = date('F', mktime(0, 0, 0, $nowMonth));
        $id_p = sprintf("%02d", $id_transaksi);
        $newDate = date("Y-m-d", strtotime($tgl));
        // setting jenis font yang akan digunakan
        $pdf->Image(APPPATH.'controllers/PDF/Logo/logoToko.jpg',20,10,-300);
        $pdf->Image(APPPATH.'controllers/PDF/Logo/logoObat.jpg',25,10,-900);
        $pdf->Cell(10,50,'',0,1);
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kotak.jpg',5,80,-700);
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'NOTA TRANSAKSI',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(140);
        $pdf->Cell(30,8,'NO : '.$param,0,1,'R');
        $pdf->Cell(140);
        $pdf->Cell(30,8,'Tanggal    :   '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(30,6,'Kepada Yth :',0,1);
        // $pdf->Cell(30,6,$nama_customer,0,1);
        // $pdf->Cell(30,6,$alamat_customer,0,1);
        // $pdf->Cell(30,6,$telp_customer,0,1);
        $pdf->Cell(10,10,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30);
        $pdf->Cell(30,6,'Produk yang dibeli di Toko Obat Gratia :  ',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(50,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(50,6,'JUMLAH',1,0,'C');
        $pdf->Cell(50,6,'HARGA',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;
        foreach ($detailtransaksi as $row){
            if($row->id_transaksi == $param)
            {
                $pdf->Cell(10,10,$i,1,0,'C');
                foreach ($produk as $loop){
                    if($loop->id_produk == $row->id_produk)
                    {
                        $i++;
                        $pdf->Cell(50,10,$loop->nama_produk,1,0,'C');
                        $pdf->Cell(50,10,$row->jml_transaksi_produk,1,0,'C');
                        $pdf->Cell(50,10,$row->subtotal_transaksi,1,1,'C');
                        
                    } 
                    
                }
                
            }
           
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(110,10,' TOTAL HARGA: ',1,0,'L'); 
        $pdf->SetFont('Arial','',10);
        foreach ($transaksi as $row){
            if($row->id_transaksi == $param)
            {
                $pdf->Cell(50,10,$row->total_transaksi,1,1,'C');
            }
           
        }
        date_default_timezone_set('Asia/Jakarta');
        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output('Nota_Transaksi_Kouvee.pdf','I');
        //.$param
    }
}