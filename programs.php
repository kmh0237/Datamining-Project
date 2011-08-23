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

require_once 'ArrayToXML.php';/*Authentication script copied from http://students.csci.unt.edu/~donrclass/4410authenticationExample2/showSource.cgi */

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
	echo "Error: important file deleted!";	exit(0);
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
		echo "Error: important file deleted!";		exit(0);
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
$pos = array();for ($i=0;$i<count($arr);$i++) {
	for ($j=0;$j<$MAX_CHECK;$j++) {
		if (!$arr[$i][2][$j]) {			$num = $arr[$i][1];
			$eNum=$j+1;			$addZero='';
			$addZeroE='';			if ($num < 10)				$addZero='0'; // add 0 is "0" if 1 digit, "" if 2+ digits
			if ($eNum < 10)
				$addZeroE='0';
			$url='http://torrentz.eu/feed?q=' . rawurlencode($arr[$i][0]) .rawurlencode(' S'  . $addZero.$num.'E'.$addZeroE.$eNum);
			$contents=file_get_contents($url);			$contents = ArrayToXML::toArray($contents);
			$contents=$contents['channel']['item'];
			$base=0; // first 5 (so 4 starting at 0) arenÕt entries we want			// $info[any_index] = (name,size,size_unit,seeds,peers,link)
			for ($k=$base;$k < count($contents);$k++) {
				$info[] = array();
				$pos[] = array($i,$j);				$info[count($info)-1][0] = $contents[$k]['title'];				$temp = explode(' ',$contents[$k]['description']);				$info[count($info)-1][1] = $temp[1];				$info[count($info)-1][2] = $temp[2];				$info[count($info)-1][3] = $temp[4];				$info[count($info)-1][4] = $temp[6];				$info[count($info)-1][5] = $contents[$k]['link'];			}		}	}
}

$data = "<?php\n\$pos = " . var_export($pos,true) . ";\n?" . ">\n";
// Write the data to a file
$fh = fopen($UserName.'-pos.php','w');
fputs ($fh,$data);
fclose ($fh);
// create the table from the mined info
?> <form method="post" action=""> <?php
echo "<table>";echo "<tr>";echo "<th></th>";echo "<th>Program Name</th>";echo "<th> Size </th>";echo "<th> Seeds </th>";echo "<th> Peers </th>";echo "</tr>";for ($i = 0; $i<count($info); $i++) {	echo "<tr>";	echo "<td> <input type=\"checkbox\" name=\"check".$i."\" value=\"check".$i."\"> </td>";	echo "<td> <a href=\"".$info[$i][5]."\"> ".$info[$i][0]." </a> </td>";	echo "<td> ".$info[$i][1].$info[$i][2]." </td>";	echo "<td> ".$info[$i][3]." </td>";	echo "<td> ".$info[$i][4]." </td> </tr>";}echo "</table>";

echo '<input type="submit" name="LOGOUT" value="LOGOUT" />';

echo '<input type="submit" name="UpdateProgramInformation" value="Update Program Information" />';

echo '<input type="submit" name="EditPrograms" value="Edit Programs" />';

echo "</form>\n";

?>
