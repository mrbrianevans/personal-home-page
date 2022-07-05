<?php


class quadcopterModel
{

    private $id;
    private $database;
    private $errorList;

    public function getErrorList()
    {
        return $this->errorList;
    }
    private function addError($errName, $errMessage){
        foreach($this->errorList as $error){
            if($error["message"] == $errMessage){
                $alreadySet = true;
            }
        }
        if(!isset($alreadySet)){
            $this->errorList[] = array(
                "name"=>$errName,
                "message"=>$errMessage
            );
        }
    }
    public function __construct($session_id, $username, $ip_address, $built)
    {
        //TODO: Change SQL to bind result
        require_once "../../db_conn.php";
        $this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $sql = "INSERT INTO quadcopters (session_id, ip_address, username, built) VALUES ('$session_id', '$ip_address', '$username', $built)";
        $this->database->query($sql);
        if($this->database->error)
            $this->addError("Error with SQL", $sql . " threw " . $this->database->error);
        $this->id = $this->database->insert_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setParts($classInput, $motorInput, $escInput, $flightControllerInput, $frameInput){
        //TODO: Clean & validate data
        $id = $this->getId();
        if(preg_match("/([0-9]{4})[ ]([0-9]{3,5})kV[ ]([a-zA-Z]+)[ ]?([^']*)/si", $motorInput, $motors)){
            $motorSize = $motors[1];
            $motorSpeed = $motors[2];
            $motorBrand = $motors[3];
            $motorLine = $motors[4]??"";
        }else{
            $this->addError("Invalid motor input", "Please enter motors in the format '[size] [speed]kV [brand] [product line]' 2207 1950kV iFlight Xing. You entered $motorInput");
        }
        if(preg_match("/([0-9]{2,3})[Aa][ ]([a-zA-Z]+)[ ]?([^']*)/s", $escInput, $escDetails)){
            $escAmps = $escDetails[1];
            $escBrand = $escDetails[2];
            $escLine = $escDetails[3];
        }else{
            $this->addError("Invalid ESC input", "Please enter ESC in the format '[amps]A [Brand] [Product line]' such as 60A Racerstar Metal. You entered $escInput");
        }
        if(!isset($this->errorList)){
            $sql = "UPDATE quadcopters SET motor_size='$motorSize',
                                  motor_brand='$motorBrand',
                                  motor_kv='$motorSpeed',
                                    motor_line='$motorLine',
                                  esc_amps='$escAmps',
                                  esc_brand='$escBrand',
                                    esc_line='$escLine',
                                    class='$classInput',
                                    flight_controller='$flightControllerInput', 
                                    frame='$frameInput'
                                  WHERE id=$id";
            $this->database->query($sql);
            if($this->database->error)
                $this->addError("Error with SQL", $sql . " threw " . $this->database->error);
        }

    }

    public function setCharacteristics($dryWeight, $throttleHover)
    {
        //TODO: Clean & validate data
        $id = $this->getId();
        $sql = "UPDATE quadcopters SET dry_weight='$dryWeight', 
                                  throttle_hover='$throttleHover'
                                  WHERE id='$id'";
        $this->database->query($sql);
        if($this->database->error)
            $this->addError("Error with SQL", $sql . " threw " . $this->database->error);
    }

    public function __destruct()
    {
        $this->database->close();
    }
}