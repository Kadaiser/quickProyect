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
        echo json_encode($this->Taskmanagermodel->getTask());
    }


    /**
     * Close a task
     * @param int id
     */
    public function closeTask()
    {
        $id = isset($_POST["id"]) ? htmlspecialchars($_POST["id"]) : null;
        echo json_encode($this->Taskmanagermodel->closeTask($id));
    }

    /**
     * add a task
     * @param string task
     * @param int:array tags
     */
    public function addTask()
    {
        $task = isset($_POST["task"]) ? htmlspecialchars($_POST["task"]) : null;
        $tags = isset($_POST["tags"]) ? $_POST["tags"] : array();
        echo json_encode($this->Taskmanagermodel->addTask($task, $tags));
    }
}
