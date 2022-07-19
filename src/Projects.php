<?php

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
        $sql = "INSERT INTO projects (name, short_description, long_description, url, start_date, end_date) VALUES (?,?,?,?,?,?)";
        $query = $this->database->prepare($sql);
        $query->bind_param("sssss", $project["name"], $project['short_description'], $project['long_description'], $project["url"], $project["start_date"], $project["end_date"]);
        $success = $query->execute();
        $query->close();
        return $success;
    }

    public function listProjects(bool $onlyFeatured): array
    {
        $filter = $onlyFeatured ? "WHERE featured": "";
        $sql = "SELECT 
    projects.id as project_id, name, short_description, long_description, 
    start_date, end_date, url, featured, GROUP_CONCAT(tag) as tags
FROM projects 
    LEFT JOIN project_tags pt on projects.id = pt.project_id 
     $filter
GROUP BY projects.id;";
        $result = $this->database->query($sql)->fetch_all(MYSQLI_ASSOC);
        foreach ($result as &$row){
            if($row['tags']) {
                $row['tags'] = str_getcsv($row['tags']);
            }else{
                $row['tags'] = [];
            }
        }
        return $result;
    }

}