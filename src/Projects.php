<?php

$exampleProject = ["name"=>"Companies House", "description"=>"A collection of components relating to Companies House", "tags"=>[1,2,3], "startDate"=>"never"];

class ProjectsService
{


    private $database;

    public function __construct()
    {
        require_once "./db_conn.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
    public function __destruct()
    {
        $this->database->close();
    }

    public function addProject($project): bool
    {
        $sql = "INSERT INTO projects (name, description, url, start_date, end_date) VALUES (?,?,?,?,?)";
        $query = $this->database->prepare($sql);
        $query->bind_param("sssss", $project["name"], $project['description'], $project["url"], $project["start_date"], $project["end_date"]);
        $success = $query->execute();
        $query->close();
        return $success;
    }

    public function listProjects(bool $onlyFeatured): array
    {
        if($onlyFeatured) {
            $result = $this->database->query("SELECT * FROM projects WHERE featured;")->fetch_all(MYSQLI_ASSOC);
        }else{
            $result = $this->database->query("SELECT * FROM projects;")->fetch_all(MYSQLI_ASSOC);
        }
        return $result;
    }

}