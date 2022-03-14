<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Taskmanagermodel'));
    }

    public function index()
    {
        $data["categories"] = $this->Taskmanagermodel->getCategories();
        $this->load->view('simpleform', $data);
    }


    /**
     * Load Task Table
     */
    public function loadtasks()
    {
        echo $this->Taskmanagermodel->getTask();
    }
}
