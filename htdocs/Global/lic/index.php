<html>
<head>
	<title>Enterprise License Viewer</title>
</head>
<body>
<h1>Enterprise License Viewer</h1>
<p>This site allows you to view your FakeApp's license file. Please select your .lic license file and press upload to verify it.</p>

<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="licenseFile" id="licenseFile"> <br>
    <input type="submit" value="Upload & Verify" name="submit">
</form>

<?php
$color="green";
$message="";

if(isset($_POST["submit"])) {
	
	// check if file starts with our magic bytes: fakeapp
	$MAGIC_BYTES="fakeapp";
	$fp = @fopen($_FILES["licenseFile"]["tmp_name"],'r');
	$first_bytes = @fread($fp, strlen($MAGIC_BYTES));   // read first 7 bytes
	
	/*
		if the file starts not with our magic bytes,
		it can't be a FakeApp license
	*/
	if ($first_bytes!=$MAGIC_BYTES) { 
		$color="red";
		$message="This is not a valid FakeApp license file";
		
		@fclose($fp);
	}
	else {
		/* 
			If it starts with the magic bytes, store everything after them
			in a new files. This boils down to removing the first 7 bytes.
		*/
		$xml_content = fread($fp, filesize($_FILES["licenseFile"]["tmp_name"])-strlen($MAGIC_BYTES));
		
		// We store this is the name as given during the upload
		file_put_contents($_FILES['licenseFile']['name'],trim($xml_content));
		
		// Then we use the LicenseManager class to theck the license...
		require_once("LicenseManager.class.php");
		$myLicenseManager = new LicenseManager();
		$result=$myLicenseManager->verifyLicenseFile($_FILES['licenseFile']['name']);
		
		// if it's valid -> say so...
		if ($result!==false) {
			$message="This license file looks good.";
		}
		// ... otherwise report an error
		else {
			$color="red";
			$message="License file is not valid.";
		}
		
		@fclose($fp);
		
		// clean up
		unlink($_FILES['licenseFile']['name']);
	}
	
?>
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