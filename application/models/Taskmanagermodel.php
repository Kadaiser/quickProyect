<?php

class Taskmanagermodel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }


    public function getCategories()
    {
        $categories        = array();
        $qry = "SELECT * FROM quickproyect.categories";
        $rs  = $this->db->query($qry);
        foreach ($rs->result() as $row) {
            $categories["$row->catID"] = $row->name;
        }
        return $categories;
    }

    public function getTask()
    {
        $data = array();

        $qry = "SELECT t.id, t.body, GROUP_CONCAT(c.name) AS tags FROM task AS t
                LEFT JOIN taskcategoryrelations AS r ON t.id=r.taskID 
                LEFT JOIN categories as c ON c.catID=r.categoryID
                GROUP BY t.id";
        $rs = $this->db->query($qry)->result();
        foreach ($rs as $row) {
            array_push($data, ["id"=> $row->id,"task"=> $row->body, "tags"=> array_values(explode(",", $row->tags))]);
        }

        echo json_encode(array("data"=> $data));
    }
}
