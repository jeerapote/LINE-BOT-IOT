<?php
 require("pub.php");
 require("line.php");

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$access_token = 'vIqVV9lNX5yNkf7r4nm+FFAesNeaypSuYC/OOW9LOiRptDrt0/ELtOJekuhmabamyn5ssrtDODisO/XE2wvauE7MTr1C0xIY84aHbRZRQDRtEojxs7UtkvssK7Y4eS4Xj/r+krB7u9ueoZVAjmOvMwdB04t89/1O/w1cDnyilFU=';
//$Gid ='Cbba671d3c1043d9d231a951b25edc69b';
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['ESP'])) {
	
	send_LINE($events['ESP']);// เรียกฟังชั่นที่ Line.php
		
	echo "OK";
	}
if (!is_null($events['events'])) {
	echo "line bot";
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$Topic = "NodeMCU1" ;
			getMqttfromlineMsg($Topic,$text);//เรียกฟังชั่นที่ pub.php
			// Make a POST Request to Messaging API to reply to sender
			

			$url = 'https://api.line.me/v2/bot/message/reply';
		
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
                        //if($text=="สวัสดี"){
			if(strstr($text,"สวัส")){
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => "จ่าเฉยยินดีรับใช้ครับ"
			];
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
                                    }// end if
			
		}
	}
}
echo "OK3";
?>
