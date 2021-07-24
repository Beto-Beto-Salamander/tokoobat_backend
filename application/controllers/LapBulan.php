<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller ; 

class LapBulan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
    
        $this->load->model('LapModel');
        // $this->load->library('PdfGenerator');
    }
    
    public function index_get($type = null)
	{   if ($type == null){
	        $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
	    }
        else if ($type == "Produk"){
            $data = $this->LapModel->produkBulan();
            if($data == null){
                $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
            }else{
                $this->response(['Data'=>$data,'Error'=>false],200);
            }
        }else if($type == "Pengadaan"){
            $data = $this->LapModel->pengadaanBulan();
            if($data == null){
                $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
            }else{
                $this->response(['Data'=>$data,'Error'=>false],200);
            }
        }
    
	}
	
	 public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}
	
}
?>