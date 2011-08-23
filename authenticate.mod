<?php // authenticate.mod
$SESSION_ID = 'DATAMINER_SESSION'; // Set an ID for this session

if (isset($_SESSION[$SESSION_ID])) { //////////////////////////
  // User has previously authenticated
  
  $UserName = $_SESSION[$SESSION_ID]; // remember the user's name
  
  // Check to see if the Logout button has been pressed, which
  //   should unauthenticate the user
  if (!isset($_POST['LOGOUT'])) // not logging out, so go about your business
    return;
  else {
    unset ($_SESSION[$SESSION_ID]); // remove the session variable
    echo "<p>You have logged out.  You are no longer authenticated.</p>\n";
    showLogin();
  }
}

// When this branch is reached, the user has not been
//   authenticated, and therefore we need to see if he's logging in
//   or something else

if (isset($_POST['Create'])) {
	?>
	<meta http-equiv="refresh" content="1;url=./create.php">
	<?php
	exit;
}

if (isset($_POST['UserName'])) {

  // Get user info from form
  $UserName = $_POST['UserName'];
  $Password = $_POST['Password'];

  if ( ! file_exists("passwords" . '.php')) {
    echo "<p>You did not enter correct UserName and/or Password</p>\n";
  } else {
    include "passwords" . '.php';

    for ($i=0;$i<count($arr);$i+=3) {
      if ($UserName == $arr[$i] && $Password == $arr[$i+1]) {
        // Record the session information for future reference
        //   (remember which user authenticated)
        $_SESSION[$SESSION_ID] = $UserName;
        return; // go about your business...
      }
    }
    echo "<p>You did not enter correct UserName and/or Password</p>\n";
  }
}

if (isset($_POST['Recover'])) {
	if (isset($_POST['UserName'])) {
  		// Get user info from form
  		$UserName = $_POST['UserName'];

		for ($i=0;$i<count($arr);$i+=3) {
			if ($UserName == $arr[$i]) {
				$Password=$arr[$i+1];
				$Email=$arr[$i+2];
			}
		}
			

		echo "An email has been sent to ".$Email;
		// The message
		$message = "Your password is:\n".$Password."\n";	

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70);

		// Send
		if(! mail($Email, 'Dataminer Password Recovery', $message)) {
			echo "<br> mail function failed <br>";
		}
	} else {
		echo "Please type in your username.";
	}
}

echo "<p>You have not previously authenticated</p>\n";

showLogin();

///////////////////////////////////////////////////////////////////////////

function showLogin() {
 
  echo "<h3>User Login</h3>";

  echo '<form method="post" action="">';
  echo '<table><tr><td>User Name:</td><td><input name="UserName" value="" /></td></tr>';
  echo '<tr><td>Password:</td><td><input name="Password" value="" /></td></tr>';
  echo "</table>";
  echo '<input type="submit" name="Login" value="Login" />';
  echo '<input type="submit" name="Create" value="Create a new Account" />';
  echo '<input type="submit" name="Recover" value="Recover an Account" />';
  echo '</form>';
  exit();
} // showLogin
?>
