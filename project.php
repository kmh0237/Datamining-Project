<?php // project.cgi

/* This code example demonstrates a variation on the other example program
   shown in this course that performs simple session authentication in PHP.
   
   This version treats the authentication process as a separate module that
   only returns control to this file if the user has successfully
   authenticated (either during this run of the script or was already
   authenticated).
   
   The advantage of this approach is that the authentication process is
   isolated from the rest of the program and separates that process from
   all the rest of the script, making it easier to manage.
*/

// setup the directory where the session variables are stored
$DirectoryPath = dirname(__FILE__) . '/SessionData';
is_dir($DirectoryPath) or mkdir($DirectoryPath, 0777);
ini_set('session.save_path',$DirectoryPath);
session_start();

ini_set("memory_limit","1000M");

echo "<h2>Datamining Project</h2>";

require_once 'authenticate.mod';

$MAX_CHECK=30;

/* FUNCTIONS */

function newArray() { // create new array for table
	$arr = array();
	return $arr;
}
																		
function table($arr) { // create standard table w/ array as base
	$MAX = count($arr)+2;
	$MAX_CHECK=30;
	?>
	<form method="post" action="">
	<table border="1">
	<tr>
	<th>Del</th>
	<th>Show</th>
	<th>Season</th>
	<th>Episodes</th>
	</tr>
	<?php
	for ($i = 0; $i<$MAX; $i++) {?>
		<tr>
		<td> <input type="checkbox" name="<?php echo "del".$i; ?>" value="<?php "del".$i; ?>"> </td>
		<td> <input name="<?php echo "show".$i; ?>" value="<?php echo $arr[$i][0]; ?>" /></td>
		<td> <input name="<?php echo "season".$i; ?>" value="<?php echo $arr[$i][1]; ?>" /></td>
		<td> <?php echo 1;
		for ($j=0; $j<$MAX_CHECK;$j++) {
			if ($j%15 == 0) {
				echo "\n";
			}
			if ($j%5 == 0 && $j != 0) {
				echo $j;
			}
			?><input type="checkbox" name="<?php echo "ep".$i."-".$j; ?>" value="<?php echo "ep".$i."-".$j; ?>" <?php
			if ($arr[$i][2][$j]) {
				echo "checked ";
			}
			echo '>';
		}
		echo "</td> </tr>";
	}
	echo "</table>";
}

/* END FUNCTIONS */

// If we get here, the user has successfully authenticated, and we can do
// whatever we need to do with the script.

echo "<p>User <b>$UserName</b> has successfully authenticated.</p>";

if (isset($_POST['FINDPROGRAMS!'])) {
	?> <meta http-equiv="refresh" content="1;url=./programs.php"> <?php
	exit;
} else if(isset($_POST['UpdateProgramInformation'])) { // someone pressed update button
	if ( file_exists($UserName . '.php')) {
		include $UserName . '.php';
	} else {
		$arr = newArray(); // make a new array for the user
	}


	$MAX=count($arr);
	for ($i=count($arr); $i < count($arr)+2;$i++) {
		if (isset($_POST["show".$i]) &&  $_POST["show".$i] != "") {
			$MAX++;
		}
	}
	$deleted=false;
	$itodelete=0;
	for ($i = 0; $i<$MAX; $i++) {
		if (isset($_POST["del".$i])) {
			echo "deleting: ";
			echo $arr[$itodelete][0] . " season " . $arr[$itodelete][1] . "</br>";
			unset($arr[$itodelete]);
			for ($j = $itodelete; $j<$MAX-1;$j++) {
				$arr[$j] = $arr[$j+1];
			}
			unset($arr[$MAX -1]);
			$deleted=true;
			$itodelete--;
		}
		$printed=false;
		if (! $deleted && isset($_POST['show'.$i])) {
			if ($arr[$i][0] != $_POST['show'.$i]) {
				echo "added/modified: " . $_POST['show'.$i] . " season " . $_POST["season".$i] . "</br>";
				$printed=true;
			}
			$arr[$i][0] = $_POST['show'.$i];
		}
		if (! $deleted && isset($_POST["season".$i])) {
			if(! $printed && $arr[$i][1] != $_POST["season".$i]) {
				echo "added/modified: " . $_POST['show'.$i] . " season " . $_POST["season".$i] . "</br>";	
			}
			$arr[$i][1] = $_POST["season".$i];
		}
		if (! $deleted) {
			$changed=false;
			for ($j=0; $j < $MAX_CHECK;$j++) {
				if (isset($_POST["ep".$i.'-'.$j])) {
				if ($_POST["ep".$i.'-'.$j] == "ep".$i.'-'.$j) {
						if (!$arr[$i][2][$j]) {
							$changed=true;	
						}
						$arr[$i][2][$j] = true;
					} else {
						if ($arr[$i][2][$j]) {
							$changed=true;	
						}
						$arr[$i][2][$j] = false;
					}
				}
			}
			if ($changed) {
				echo "changed episodes for: " . $arr[$itodelete][0] . " season " . $arr[$itodelete][1] . "</br>";	
			}
		}
		+$itodelete++;			
	} // and put into file
	$data = "<?php\n\$arr = " . var_export($arr,true) . ";\n?" . ">\n";
	// Write the data to a file
	$fh = fopen($UserName.'.php','w');
	fputs ($fh,$data);
	fclose ($fh);
	table($arr);
} else {
	if ( ! file_exists($UserName . '.php')) {
		$arr = newArray(); // make a new array for the user
		table($arr);
	} else {
		include $UserName . '.php';
		table($arr);
	}
}


echo '<input type="submit" name="LOGOUT" value="LOGOUT" />';

echo '<input type="submit" name="UpdateProgramInformation" value="Update Program Information" />';

echo '<input type="submit" name="FINDPROGRAMS!" value="FIND PROGRAMS!" />';

echo "</form>\n";

?>
