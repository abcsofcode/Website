<?php 
	set_time_limit(0);

	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

    if (!$db) {
        echo mysqli_connect_error();
        exit;
    }

	$file = file_get_contents('../thing.csv');   

    //Read CSV file from link
    // $curlSession = curl_init();
    // curl_setopt($curlSession, CURLOPT_URL, 'data.tc.gc.ca/extracts/vrdb_60days_daily.csv');
    // curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
    // curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

    // $file = curl_exec($curlSession);
    // curl_close($curlSession);

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

            // mysqli_query($db, $query);

            //check if any users have new vehicles added and send them a notification
            $query = 'SELECT * FROM users INNER JOIN user_vehicles ON users.id = user_vehicles.user_id;';
            $result = mysqli_query($db, $query);

            while($row = mysqli_fetch_assoc($result)){
                echo '<pre>';
                print_r($row);

                if($row['year'] == $deeper[1] && $row['make'] == $deeper[5] && $row['model'] == $deeper[6]){
                    $msg = 'Hello ' . $row['username'] . ', your ' . $row['year'] . ' ' . $row['make'] . ' ' . $row['model'] . ' has been associated with a recall';

                    $message = array();
                    $reg_id = array();  

                    $message['m'] = '{"greetMsg":"'. $msg .'"}';
                    $reg_id[0] = $row['push_id'];

                    sendPushNotificationToGCM( $reg_id, $message);

                    // In case any of our lines are larger than 70 characters, we should use wordwrap()
                    $msg = wordwrap($msg, 70, "\r\n");

                    $headers   = array();
                    $headers[] = "MIME-Version: 1.0";
                    $headers[] = "Content-type: text/plain; charset=iso-8859-1";
                    $headers[] = "From: LemonAide <anonymous@pita.dynamichosting.biz>";
                    $headers[] = "Reply-To: You <" . $row['email'] . ">";
                    $headers[] = "Subject: Recall Notice";
                    $headers[] = "X-Mailer: PHP/".phpversion();

                    // Send
                    mail($row['email'], 'Recall Alert', $msg, implode("\r\n", $headers));
                }
            }
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

    //Generic php function to send GCM push notification
    function sendPushNotificationToGCM($registation_ids, $message) {
        // $mArray = array();
        // $mArray['m'] = '{"greetMsg":"'. $message .'"}';
        // $registation_ids = array($registation_ids);

        // sendPushNotificationToGCM(
        //     $registation_ids, 
        //     $mArray
        // );

        //Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        
        $fields = array(
            'registration_ids' => $registation_ids,
            'data'             => $message,
        );

        echo '<pre>';
        print_r($fields);

        // Update your Google Cloud Messaging API Key
        if (!defined('GOOGLE_API_KEY')) {
            define("GOOGLE_API_KEY", "AIzaSyCCWkBRjhe-GPUHUoGCt6BiPsKKuimAvfg");  // Android
            //define("GOOGLE_API_KEY", "AIzaSyABvMLlBjSsuaxLArm0Pa9l5hD-k9B8_4w");  // iOS      
        }

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);   

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }

?>