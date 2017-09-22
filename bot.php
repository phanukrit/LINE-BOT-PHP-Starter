<?php
$access_token = 'BaSMJU5AZ2+reEMNXleWB74DaL8tIbj7Sbv9pKu0IJrFFh+6lxVsk4XPoq5o7ObbshWo9vgROWq26sgKd9n3HFkwl2PTimb7IqSytqcfgAeNlsv324nyfdYU4X25IFNAsKa277z1lwOCZfMA65hcOgdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
			$data = json_decode($post ,true);
			//echo "<pre>";
			//var_dump($data);
			//echo "</pre>";
			$text = $data['messages'][0]['text']."\n";
			$myfile = fopen("file.txt", "a+") or die("Unable to open file!");
			fwrite($myfile,$text);
			fwrite($myfile,$data);
			fclose($myfile);
			
			
		
		}
	}
}
echo "OK";
