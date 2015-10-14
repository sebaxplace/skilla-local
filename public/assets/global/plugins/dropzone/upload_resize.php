<?php
class Upload extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library('image_lib');
   }
    public function index(){
        $this->load->view('upload');
    }
    public function upload_image(){
      if (!empty($_FILES)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $targetPath = '../../../../uploads';
        $targetFile = $targetPath .substr(uniqid(rand()),0,4).'_'.$fileName ;
        move_uploaded_file($tempFile, $targetFile);
        $config['image_library'] = 'gd2';
        $config['source_image'] = $targetFile;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']     = 75;
        $config['height']   = 50;
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        /* If you wanna save to database image url 
        $imgName = substr(uniqid(rand()),0,4).'_'.$fileName;
        $this->adminproperty_model->save_image($imgName);
        */
       }
    }
}
?>