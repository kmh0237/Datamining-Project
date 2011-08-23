<?php
//create a new account

echo "<h2>Datamining Project</h2>";
echo "";
echo "<h3>Create a New Account</h3>";
if (isset($_POST['UserName']) && strlen($_POST['Email']) > 1 && strlen($_POST['Password']) > 1) {

	// Get user info from form
	$UserName = $_POST['UserName'];
	$Password = $_POST['Password'];
	$Email = $_POST['Email'];

	if ( ! file_exists("passwords" . '.php')) {
		$arr = array($UserName,$Password,$Email); // make a new array for the user
	} else {
		include "passwords" . '.php';
		$len=count($arr);
		$arr[$len] = $UserName;
		$arr[$len+1] = $Password;
		$arr[$len+2] = $Email;
	}

	$data = "<?php\n\$arr = " . var_export($arr,true) . ";\n?" . ">\n";
	// Write the data to a file
	$fh = fopen("passwords".'.php','w');
	fputs ($fh,$data);
	fclose ($fh);
	?> <meta http-equiv="refresh" content="1;url=./project.php"> <?php
	exit;
} else {
	echo "Type in username, password, and email";
}

echo '<form method="post" action="">';
echo '<table><tr><td>User Name:</td><td><input name="UserName" value="" /></td></tr>';
echo '<tr><td>Password:</td><td><input name="Password" value="" /></td></tr>';
echo '<tr><td>Email:</td><td><input name="Email" value="" /></td></tr>';
echo "</table>";
echo '<input type="submit" name="Create" value="Create Account" /> </form>';
?>
