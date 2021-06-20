<?php
    if(isset($_POST["fileuploaded"])){
        try {
            $targetFileName = time() . random_bytes(5);
        } catch (Exception $e) {
            echo "$e";
        }
        $targetLocation = "uploads/$targetFileName.csv";
        $fileType = strtolower(pathinfo($_FILES["csvFileUpload"]["name"], PATHINFO_EXTENSION));
        $fileSize = $_FILES["csvFileUpload"]["size"];
        if($fileType==="csv"&&$fileSize<5_000_000){
            move_uploaded_file($_FILES["csvFileUpload"]["tmp_name"], $targetLocation);
            require "csvJsonFlashcardConverterModel.php";
            if($response = csvJsonFlashcardConverterModel::convertFlashcardCsvToJson($targetFileName)) {
                $storageRequest = curl_init("https://brianevans.tech/projects/school-companion/api.php");
                curl_setopt($storageRequest, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($storageRequest, CURLOPT_POSTFIELDS, $response);
                curl_setopt($storageRequest, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                echo "<p>Find this code in the browser to get your flashcards: ";
                curl_exec($storageRequest);
                curl_close($storageRequest);
                echo "</p>";
                echo "<hr>";
                echo $response;
            }
            else {
                echo "<p>Failed to convert. Please check CSV file and try again</p>";
            }
        }else{
            $formattedFileSize = number_format($fileSize);
            echo "<p>Only CSV files less than 5 megabytes are allowed. File not uploaded</p>";
            echo "<p>File type: $fileType</p>";
            echo "<p>File size: $formattedFileSize bytes</p>";
        }

    }else{
        ?>
        <form method="post" enctype="multipart/form-data">
            <p>Please upload a csv file to convert. The file should have the headings 'question' and 'answer', in that order</p>
            <input type="file" name="csvFileUpload" id="csvFileUpload" accept=".csv"/>
            <button type="submit" name="fileuploaded">Convert</button>
        </form>
        <?php
    }
