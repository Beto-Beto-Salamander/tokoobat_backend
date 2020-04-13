<?php

use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding ");
        parent::__construct();
        $this->load->model('PegawaiModel');
        $this->load->library('form_validation');
        $this->load->helper(['jwt', 'authorization']);
    }

    public function Rules()
    {
        return [
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ],
            [
                'field' => 'username',
                'label' => 'username',
                'rules' => 'required'
            ]
        ];
    }

    public function index_post()
    {
        $validation = $this->form_validation;
        $rule = $this->Rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->response($this->form_validation->error_array());
        }
        $user = new PegawaiData();
        $user->password = $this->post('password');
        $user->username = $this->post('username');
        $result=$this->PegawaiModel->verify($user);
        if ($result['error']==false) { 
            $pegawai=$result['msg'];
            $token = AUTHORIZATION::generateToken(['id_pegawai' => $pegawai['id_pegawai'], 'nama_pegawai' => $pegawai['nama_pegawai'],'role_pegawai' => $pegawai['role_pegawai']]);
            $status = parent::HTTP_OK;
            $response = ['status' => $status, 'token' => $token];
            return $this->response($response, $status);
        } else {
            return $this->response($result['msg']);
        }
    }
}
class PegawaiData
{
    public $password;
    public $username;
}
