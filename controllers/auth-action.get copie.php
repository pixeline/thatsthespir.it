<?php
error_reporting(E_WARNING | E_ERROR);
global $db;
/**
 * Callback for Opauth
 *
 * This file (callback.php) provides an example on how to properly receive auth response of Opauth.
 *
 * Basic steps:
 * 1. Fetch auth response based on callback transport parameter in config.
 * 2. Validate auth response
 * 3. Once auth response is validated, your PHP app should then work on the auth response
 *    (eg. registers or logs user in to your site, save auth data onto database, etc.)
 *
 */
/**
 * Define paths
 */




/**
 * Load config
 */
if (!file_exists(CONF_FILE)) {
	trigger_error('Config file missing at '.CONF_FILE, E_USER_ERROR);
	exit();
}
require CONF_FILE;
/**
 * Instantiate Opauth with the loaded config but not run automatically
 */
require OPAUTH_LIB_DIR.'Opauth.php';

$action = $f3->get('PARAMS.action');

if($action !== 'callback'){
	$Opauth = new Opauth( $config );
} else{
	$Opauth = new Opauth( $config, false);
}



/**
 * Fetch auth response, based on transport configuration for callback
 */
$response = null;
switch($Opauth->env['callback_transport']) {
case 'session':
	$response = $_SESSION['opauth'];
	unset($_SESSION['opauth']);
	break;
case 'post':
	$response = unserialize(base64_decode( $_POST['opauth'] ));
	break;
case 'get':
	$response = unserialize(base64_decode( $_GET['opauth'] ));
	break;
default:
	echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
	break;
}


/*
echo "<pre>";
print_r($f3->get('SESSION'));
echo "</pre>";
exit;
*/

/**
 * Check if it's an error callback
 */
if (array_key_exists('error', $response)) {
	echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.'."<br>\n";
	echo '<pre>'.print_r($response,false).'</pre>';
}
/**
 * Auth response validation
 *
 * To validate that the auth response received is unaltered, especially auth response that
 * is sent through GET or POST.
 */
else{
	if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
		echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.'."<br>\n";
		echo '<pre>'.print_r($response,false).'</pre>';
	} elseif (!$Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
		echo '<strong style="color: red;">Invalid auth response: </strong>'.$reason.".<br>\n";
		echo '<pre>'.print_r($response,false).'</pre>';

	} else {
		/**
		 * It's all good. Go ahead with your application-specific authentication logic
		 */

		$_SESSION['logged_in']= 'ok';

		$username = $response['auth']['info']['email'];
		$f3->set('SESSION.logged_in', 'ok');

		$user = new DB\SQL\Mapper($db, 'users');
		$user->load(array('email = :username LIMIT 0,1', ':username'=>$username));
		$new = false;
		if($user->dry()){
			$user->role='subscriber';
			$new= true;
		}
		$user->email = $username;
		$user->fullname= $response['auth']['info']['name'];
		$user->image= $response['auth']['info']['image'];
		$user->urls= json_encode($response['auth']['info']['urls']);
		$user->password = $response['auth']['provider'];
		$user->created= date('Y-m-d H:i:s');
		$user->save();

if($new){
	// Email to admin
	$smtp = new SMTP ( 'smtp.gmail.com', 465, 'SSL', 'aplennevaux@gmail.com', 'iluvrocknroll' );

	$smtp->set('From', '"pixeline" <alexandre@pixeline.be>');
	$smtp->set('To', '<aplennevaux@gmail.com>');
	$smtp->set('Subject', "That's The Spirit: New user registration!");
	$smtp->set('Errors-to', '<alexandre@pixeline.be>');

	$message = 'On '.date('d.m.Y at H:i:s').', a new user registered, kind master.'."\n---\n";
	$message .= "email: ". $user->email . "\nname:".$user->fullname;
	$message .="\n---\nSee you,\n\nThe Spirit.";
	$sent = $smtp->send($message, TRUE);

}

		$f3->set('SESSION.logged_in', 'ok');
		$f3->set('SESSION.user',  array('id'=> $user->id, 'email'=>$user->email, 'fullname'=>$user->fullname, 'role'=>$user->role, 'image'=> $user->image, 'urls'=> json_decode($user->urls)));

		if(!empty($f3->get('SESSION.next_action'))){
			
			switch($f3->get('SESSION.next_action')){

			case 'like':

				$fav = new DB\SQL\Mapper($db, 'favourites');
				$fav->quote_id = $f3->get('SESSION.quote_id') ;
				$fav->user_email = $username;
				$fav->save();

				$f3->set('SESSION.goto', '/quote/view/'.$f3->get('SESSION.quote_id'));

				break;
			}
			$f3->clear('SESSION.next_action');
			$f3->clear('SESSION.quote_id');
		}


		if(!empty($f3->get('SESSION.goto'))){
			$f3->reroute($f3->get('SESSION.goto'));
			$f3->clear('SESSION.goto');

		}else{
			$f3->reroute('/');
		}
	}
}