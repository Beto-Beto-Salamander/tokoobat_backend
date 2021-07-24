<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use chriskacerguis\RestServer\RestController;

use Dompdf\Adapter\CPDF;      
use Dompdf\Dompdf;
use Dompdf\Exception;
use Restserver\Libraries\REST_Controller; 

require 'vendor/autoload.php';

class Laporan extends CI_Controller{
    public function __construct(){
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        // header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('DetailTransaksiModel');
        $this->load->model('LaporanModel');
        $this->load->model('TransaksiModel');
        // $this->load->library('PdfGenerator');
    }
    
	
	public function printLaporanProdukTahun($year)
	{   
	    
        // $result['produk'] = $this->LaporanModel->getDataProdukTahun($year);
        $result['produk'] = $this->LaporanModel->getDataTransaksiProdukTahun($year);
        $result['year'] = $year;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('TableProdukTahunan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Produk Terlaris Tahun ' .$year ,array('Attachment'=>0));
        
	}
	
	
	public function printLaporanPengadaanTahun($year)
	{   
        $result['pengadaan'] = $this->LaporanModel->getDataPengadaanTahun($year);
        $result['year'] = $year;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pengadaan_tahunan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pengadaan Tahun ' .$year ,array('Attachment'=>0));
        
	}
	
	public function printLaporanPengadaanBulan($year,$month)
	{   
        $result['pengadaan'] = $this->LaporanModel->getDataPengadaanBulan($year,$month);
        $result['year'] = $year;
        $result['month'] = $month;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pengadaan_bulanan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pengadaan Bulanan ' .$month.' '.$year ,array('Attachment'=>0));
        
	}
	
	public function printLaporanProdukBulan($year,$month)
	{   
        $result['produk'] = $this->LaporanModel->getDataProdukBulan($year,$month);
        $result['year'] = $year;
        $result['month'] = $month;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pendapatan_bulanan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pendapatan Bulanan ' .$month.' '.$year ,array('Attachment'=>0));
        
	}
	
	
}
?>