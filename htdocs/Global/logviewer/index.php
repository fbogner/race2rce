<html>
<head>
	<title>Log Viewer</title>
</head>
<body>
<h1>Log Viewer</h1>
<p>This site allows you to view your FakeApp's log files. Please select a log to view:</p>

<?php
	$log_folder="../logs";
	
	$files = scandir($log_folder);
	foreach($files as $file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if ($ext=="log") {
			echo "<a href='?file=$log_folder/$file'>$file</a><br>";
		}
	}
?>

<?php
$color="green";
$message="";

if (isset($_GET['file'])) {
	$full_file=$_GET['file'];
	
	if (file_exists($full_file)) {
		$message=file_get_contents($full_file);
	}
	else {
		$color="red";
		$message="File $full_file does not exists.";
	}	
?>
<br>
<h2>Log content</h2>
<table style="border-width:1px; border-color:<?php echo $color ?>;border-style:solid;">
	<tr>
		<td><?php echo $message ?></td>
	</tr>
</table>
<?php
}
?>

</body>
</html>