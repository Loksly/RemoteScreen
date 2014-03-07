<?php
/*	DANGER, IMPORTANT ISSUE: No filtering is performed against possible XSS attacks.
		Note the message can contain scripts. */
		
	session_start();

	define ('PREFIX', sys_get_temp_dir().DIRECTORY_SEPARATOR.'notify_');
	define ('SUFFIX','.txt');
	
	$hashfunction = 'md5'; /* depending on the kind of channels you may use intval, md5, sha1 or whatever you want */

	$channel = isset($_REQUEST['channel']) ? $_REQUEST['channel'] : '';
	$channel = $hashfunction($channel);
	
	$file = PREFIX. $channel. SUFFIX;
	
	if (isset($_SERVER['HTTP_ACCEPT']) && trim($_SERVER['HTTP_ACCEPT'])=='text/event-stream')
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		if (file_exists($file))
		{
			$contents = file_get_contents($file);
			!isset($_SESSION['previous_'.$channel]) && $_SESSION['previous_'.$channel]='';
			if ($contents!='' && $contents!=$_SESSION['previous_'.$channel])
			{
				echo "data: ".$contents."\n\n";
				flush();
			}
			$_SESSION['previous_'.$channel] = $contents;
		}
	}else{
		$params = $_REQUEST;
		unset($params['channel']);
/*
	you may try to prevent some XSS and code injections with code like this:
	foreach($params as $n => $v){
		$params[$n] = filter_var($v, FILTER_SANITIZE_STRING);
	}
	but it won't be completely safe. You should also guarantee that you only admit
	trusted sources. This can be done by several ways:
	based on IP, based on referer (may be too weak) or any other approaches.
*/
		$message = json_encode($params);
		file_put_contents($file, $message);
		/* you may choose the post or get http method to send messages */
?>
<html>
	<head>
		<title>Messaging system</title>
		<style>
			body{ width:100%; }
			form { width:80%; margin-left:auto; margin-right: auto;}
			label{ width:25%; float:left; clear:left; text-align:right;}
			input,textarea{ width:74%; float:left}
			textarea{ height: 5em; }
			br { clear:both; margin:1em 0;}
		</style>
	</head>
	<body>
		<form action="?" method="get">
			<label for="channel">Channel:</label>
			<input type="text" id="channel" name="channel" value="<?php echo isset($_REQUEST['channel']) ? addslashes($_REQUEST['channel']) : ''; ?>" /><br />
			<label for="message">Message:</label>
			<textarea id="message" name="message"><?php echo isset($_REQUEST['message']) ? ($_REQUEST['message']) : ''; ?></textarea><br />
			<label for="sound">Audio:</label>
			<input type="text" id="sound" name="sound" value="<?php echo isset($_REQUEST['sound']) ? addslashes($_REQUEST['sound']) : ''; ?>" /><br />
			<label for="submit"> </label>
			<input type="submit" id="submit" />
		</form>
	</body>
</html>
<?php
	}
?>
