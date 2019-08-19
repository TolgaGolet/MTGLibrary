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
	$stmt = $conn->prepare("INSERT INTO books (id, name, author, publisher, image)
    VALUES (:id, :name, :author, :publisher, :image)");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $bookName);
    $stmt->bindParam(':author', $author);
	$stmt->bindParam(':publisher', $publisher);
	$stmt->bindParam(':image', $image);

    // insert a row
	$id=NULL;
    $bookName=$_POST['bookName'];
	$author=$_POST['author'];
	$publisher=$_POST['publisher'];
	$image='/img/'.$_POST['bookName'].'.jpg';
    //$stmt->execute();
    //echo "New record created successfully";

	//Uploading image
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (is_uploaded_file($_FILES['imageFile']['tmp_name']))
  {
  	//First, Validate the file name
  	if(empty($_FILES['imageFile']['name']))
  	{
  		echo " File name is empty! ";
  		exit;
  	}

  	$upload_file_name = $_FILES['imageFile']['name'];
  	//Too long file name?
  	if(strlen ($upload_file_name)>100)
  	{
  		echo " too long file name ";
  		exit;
  	}

  	//replace any non-alpha-numeric cracters in th file name
  	//$upload_file_name = preg_replace("/[^A-Za-z0-9 \.\-_]/", '', $upload_file_name);
	$upload_file_name = $_POST["bookName"].'.jpg';

  	//set a limit to the file upload size
  	if ($_FILES['imageFile']['size'] > 5242880)
  	{
		echo '<script type="text/javascript">
                alert("Dosya boyutu 5 MB\'tan düşük olmalıdır.");
				window.location.replace(".../create.html");
            </script>';
  		exit;
    }

    //Save the file
    $dest=__DIR__.'/img/'.$upload_file_name;
    if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $dest))
    {
    	//echo 'File Has Been Uploaded !';
		$stmt->execute();
		echo '<script type="text/javascript">
                window.location.replace(".../success.html");
            </script>';
    }
  }
}
	}
	else {
		echo '<script type="text/javascript">
                alert("Yetki kodunuz geçersizdir.");
				window.location.replace(".../create.html");
            </script>';
	}
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>
