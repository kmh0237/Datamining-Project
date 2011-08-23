<?php // programs.cgi

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

require_once 'ArrayToXML.php';

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


/* END FUNCTIONS */

// If we get here, the user has successfully authenticated, and we can do
// whatever we need to do with the script.

if ( ! file_exists($UserName . '.php')) {
	echo "Error: important file deleted!";
} else {
	include $UserName . '.php';
}

echo "<p>User <b>$UserName</b> has successfully authenticated.</p>";

if (isset($_POST['EditPrograms'])) {
	?> <meta http-equiv="refresh" content="0;url=./project.php"> <?php
	exit;
} else if(isset($_POST['UpdateProgramInformation'])) { // someone pressed update button
	if ( file_exists($UserName . '-pos.php')) {
		include $UserName . '-pos.php';
	} else {
		echo "Error: important file deleted!";
	}
	
	for ($i=0;$i<count($pos);$i++) {
		if(isset($_POST['check'.$i])) {
			echo $arr[$pos[$i][0]][0]." season ".$arr[$pos[$i][0]][1]." episode ".($pos[$i][1]+1)." updated<br />";
			$arr[$pos[$i][0]][2][$pos[$i][1]]=true;
		}
	}
	$data = "<?php\n\$arr = " . var_export($arr,true) . ";\n?" . ">\n";
	// Write the data to a file
	$fh = fopen($UserName.'.php','w');
	fputs ($fh,$data);
	fclose ($fh);

} else {

}
	
// no buttons pressed

$info = array();
$pos = array();
	for ($j=0;$j<$MAX_CHECK;$j++) {
		if (!$arr[$i][2][$j]) {
			$eNum=$j+1;
			$addZeroE='';
			if ($eNum < 10)
				$addZeroE='0';
			$url='http://torrentz.eu/feed?q=' . rawurlencode($arr[$i][0]) .rawurlencode(' S'  . $addZero.$num.'E'.$addZeroE.$eNum);
			$contents=file_get_contents($url);
			$contents=$contents['channel']['item'];

			for ($k=$base;$k < count($contents);$k++) {
				$info[] = array();
				$pos[] = array($i,$j);
}

$data = "<?php\n\$pos = " . var_export($pos,true) . ";\n?" . ">\n";
// Write the data to a file
$fh = fopen($UserName.'-pos.php','w');
fputs ($fh,$data);
fclose ($fh);

?> <form method="post" action=""> <?php
echo "<table>";

echo '<input type="submit" name="LOGOUT" value="LOGOUT" />';

echo '<input type="submit" name="UpdateProgramInformation" value="Update Program Information" />';

echo '<input type="submit" name="EditPrograms" value="Edit Programs" />';

echo "</form>\n";

?>