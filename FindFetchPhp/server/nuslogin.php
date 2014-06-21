<?php
namespace server;

$rootFile = $_SERVER['DOCUMENT_ROOT'];

require_once ($rootFile . '/server/initial.php');


use thirdpartylib\LightOpenID;

try {
	$domain = "localhost";
	$openid = new LightOpenID($domain);
	if(!$openid->mode) {
		if (isset($_POST['nusnet_id'])) {
			$openid->identity = "https://openid.nus.edu.sg/".$_POST['nusnet_id'];
		}
		# The following two lines request email, full name, and a nickname
		# from the provider. Remove them if you don't need that data.
		//$openid->required = array('contact/email');
		$openid->optional = array('namePerson', 'namePerson/friendly', 'contact/email');
        header('Location: ' . $openid->authUrl());
    } elseif ($openid->mode == 'cancel') {
    	echo 'User has canceled authentication!';
    } else {
        echo "<h1>OpenID Login Information</h1>\n";
        echo "<fieldset>\n";
        echo "<p>User <b>" . ($openid->validate() ? $openid->identity . "</b> has " : "has not ") . "logged in.<p>\n";
        foreach ($openid->getAttributes() as $key => $value) {
        	echo "$key => $value<br>";
        }
        echo "</fieldset>\n";
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}

?>