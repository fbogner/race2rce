<?php

// A very simple license manager...
class LicenseManager { 

	/* 
		If the given file is a valid XML file, the key
		serial is checked for a hardcoded value
	*/
    function verifyLicenseFile($file) { 
        $xml=simplexml_load_string(file_get_contents($file));
        if ($xml->serial=="123-1234-123") {
        	return true;
        }
        else {
        	return false;
        }
		
    } 
} 
?>