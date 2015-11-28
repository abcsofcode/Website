<?php 
	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

    if (!$db) {
        echo mysqli_connect_error();
        exit;
    }

	$file = file_get_contents('../thing.csv');   

    // $curlSession = curl_init();
    // curl_setopt($curlSession, CURLOPT_URL, 'data.tc.gc.ca/extracts/vrdb_60days_daily.csv');
    // curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
    // curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

    // $file = curl_exec($curlSession);
    // curl_close($curlSession);

    $thing = str_getcsv($file, "\n");

    //Each row of CSV
    foreach($thing as &$row){ 
        $row = str_getcsv($row, ";"); 

        foreach ($row as &$deeper) {
            $deeper = str_getcsv($deeper);

            if(trim($deeper[0]) == "RECALL_NUMBER_NUM"){
                continue; //skip the headers
            }

            $query = 'INSERT INTO recalls (
                recall_number,
                year, 
                manufacturer_recall_number, 
                category_en, category_fr, 
                make, model, units_affected, 
                system_type_en, 
                system_type_fr, 
                notification_type_en, 
                notification_type_fr, 
                comment_en, 
                comment_fr) VALUES (' . 
                checkEmpty($deeper[0]) . ', ' . 
                checkEmpty($deeper[1]) . ', "' . 
                checkEmpty($deeper[2]) . '", "' . 
                checkEmpty($deeper[3]) . '", "' . 
                checkEmpty($deeper[4]) . '", "' . 
                checkEmpty($deeper[5]) . '", "' . 
                checkEmpty($deeper[6]) . '", ' . 
                checkEmpty($deeper[7]) . ', "' . 
                checkEmpty($deeper[8]) . '", "' . 
                checkEmpty($deeper[9]) . '", "' . 
                checkEmpty($deeper[10]) . '", "' . 
                checkEmpty($deeper[11]) . '", "' . 
                checkEmpty($deeper[12]) . '", "' . 
                checkEmpty($deeper[13]) . '");';     
            
            echo $query . '<br><br><br>';

            mysqli_query($db, $query);
        }
    } 

	// mysqli_close();

    function checkEmpty($var){
        if(empty($var)){
            return 'null';
        } else {
            return str_replace(',', '', $var);
        }
    }

?>