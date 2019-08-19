<?php
//Connecting to database
$servername = "";
$username = "";
$password = "";
$authorizationCode = '';

try {
	if($_POST['authorizationCode']==$authorizationCode) {
		    $conn = new PDO("mysql:host=$servername;dbname=;charset=utf8", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("DELETE FROM books WHERE id=:id;");
    $stmt->bindParam(':id', $id);

    // insert a row
	$id=$_POST['bookID'];

	$oldImageFile = __DIR__.'/img/'.$_POST['bookOldName'].'.jpg';
	unlink($oldImageFile) or die("Couldn't delete old image file");
	$stmt->execute();
	echo '<script type="text/javascript">
			window.location.replace(".../success.html");
          </script>';
	}
	else {
		echo '<script type="text/javascript">
                alert("Yetki kodunuz geçersizdir.");
				window.location.replace(".../index2.php");
            </script>';
	}
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>
