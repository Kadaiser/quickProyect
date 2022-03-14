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

        return array("data"=> $data);
    }

    /**
     * Close a task
     * @param int id
     */
    public function closeTask($id = null)
    {
        $response = array("status" => false, "msg"=> "Not Found");

        try {
            if (is_null($id)) {
                throw new Exception("Task id cannot be empty", 1);
            }
            $qry = "DELETE FROM task WHERE task.id = ?";
            $this->db->query($qry, array($id));
            if ($this->db->affected_rows()) {
                $qry = "DELETE FROM taskcategoryrelations WHERE taskcategoryrelations.taskID = ?";
                $this->db->query($qry, array($id));

                $response = array("status" => true, "msg"=> "");
            }
        } catch (Exception $e) {
            $response = array("status" => false, "msg"=> $e->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * add a task
     * @param string task
     * @param int:array tags
     * @return mixed:array with id of created key at the DDBB
     */
    public function addTask($task = null, $tags = null)
    {
        $response = array("status" => false, "msg"=> "Not processed");

        try {
            if (is_null($task) || $task == "") {
                throw new Exception("Task cannot be empty", 1);
            }

            $qry = "INSERT INTO task (body) VALUES (?);";
            $this->db->query($qry, array($task));
            $id = $this->db->insert_id();
            if ($id > 0) {
                foreach ($tags as $tagID) {
                    $qry = "INSERT INTO taskcategoryrelations (taskID, categoryID) VALUES (?, ?)";
                    $this->db->query($qry, array($id, $tagID));
                }
                $response = array("status" => true, "msg"=> $id);
            } else {
                throw new Exception("Error inserting at DDBB", 1);
            }
        } catch (Exception $e) {
            $response = array("status" => false, "msg"=> $e->getMessage());
        } finally {
            return $response;
        }
    }
}
