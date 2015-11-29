<?php 
	set_time_limit(0);

	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

    if (!$db) {
        echo mysqli_connect_error();
        exit;
    }

	// $file = file_get_contents('../thing.csv');   

    //Read CSV file from link
    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, 'data.tc.gc.ca/extracts/vrdb_60days_daily.csv');
    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

    $file = curl_exec($curlSession);
    curl_close($curlSession);

    //read csv into strings
    $thing = str_getcsv($file, "\n");

    //Get current recalls
    $query = "SELECT recall_number, year, manufacturer_recall_number, make, model FROM recalls;";
    $result = mysqli_query($db, $query);

    //Save current recalls into variable for reuse
    $rr = array();
    while($r = mysqli_fetch_assoc($result)){
    	array_push($rr, $r);
    }

    $cont = false;
    //Each row of CSV
    foreach($thing as &$row){ 
        $row = str_getcsv($row, ";"); 

        foreach ($row as &$deeper) {
        	$cont = false;
            $deeper = str_getcsv($deeper);

            if(trim($deeper[0]) == "RECALL_NUMBER_NUM"){
                continue; //skip the headers
            }

            foreach($rr as $a){
            	// Check for duplicates from new 60 day recalls
            	if($a['recall_number'] == $deeper[0]){
            		if(checkNull($a['year']) == $deeper[1]){
	            		if(checkNull($a['manufacturer_recall_number']) == $deeper[2]){
		            		if(checkNull($a['make']) == $deeper[5]){
			            		if(checkNull($a['model']) == $deeper[6]){
			            			//match found
									$cont = true;
									$count++;
									break;
				            	} 
			            	}
		            	}
	            	}
            	}
            }

            if($cont == true){
        		continue;
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

            mysqli_query($db, $query);
        }
    } 

	mysqli_close($db);

    function checkEmpty($var){
        if(empty($var)){
            return 'null';
        } else {
            return str_replace(',', '', $var);
        }
    }

    function checkNull($var){
    	if($var === 'null'){
    		return '';
    	} else {
    		return $var;
    	}

    }

?>