<!DOCTYPE html>
<html>
<head>
	<title>MTGLibrary</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Content delivery network (CDN)-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<!--Content delivery network (CDN)-->
	<link rel="stylesheet" href="css/SocialMediaButtons.css">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="css/searchBar.css">
	<link rel="stylesheet" href="css/modalBox.css">
	<script src="/js/searchBar.js"></script>
<style>
	.pageNumbers {
		padding:8px 16px;
		cursor:pointer;
		text-align:center;
		border:solid;
		border-color:white;
	}
	.pageNumbers:hover {
		color:#fff;
		background-color:#000;
	}
	img {
		max-width:350px;
		max-height:350px;
		border: solid;
		cursor: pointer;
	}
	h3 {
		cursor: pointer;
	}
	#welcomeMessage {
		color:rgba(255, 255, 255, 0.8);
		margin-top:8px;
		margin-right:4px;
		margin-bottom:0px;
	}
</style>
</head>
<body  onload="loadDoc();" style="background-color:#A9A9A9">
<nav style="font-weight:bold; background-color:#293a4a; border-bottom-style:solid;" class="navbar navbar-expand-sm navbar-dark">
  <a class="navbar-brand" href="/index2.php">MTGLibrary</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <p id="welcomeMessage" onclick="getName(true)">Hoş geldiniz</p>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="/create.html"><i class="fa fa-plus"></i> Kitap Ekle</a>
      </li>
	  <!-- Search Bar -->
	<li class="navbar-right">
      <form class="search-form" role="search" action="javascript:doNothing();">
        <div class="form-group pull-right" id="search">
          <input type="text" name="search" class="form-control" placeholder="Kitap, yazar, yayınevi" id="searchBox">
          <button type="submit" class="form-control form-control-submit">Submit</button>
          <span class="search-label"><i class="fa fa-search"></i></span>
        </div>
      </form>
     </li>
    </ul>
  </div>
</nav>
<div class="container" style="background-color:white; border-left:solid; border-right:solid;">
	<div class="row">
		<div class="col-sm-12">
		<div id="myModal" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<h2>Kitap Detayı</h2>
					<span id="closeButton" class="close">&times;</span>
				</div>
				<div id="modalBody" class="modal-body">
					<p>Some text in the Modal Body</p>
					<p>Some other text...</p>
				</div>
				<div class="modal-footer">
					<button id="editButton"><i class="fa fa-edit"></i> Düzenle</button>
					<button id="cancelButton"><i class="fa fa-times"></i> İptal</button>
				</div>
			</div>
		</div>
			<div id="content">

			</div>
		</div>
	</div>
</div>
<div class="jumbotron text-center" style="margin-bottom:0px; margin-top:-10px; background-color:#293a4a; border-top-style:solid;">
	<a href="" class="fa fa-linkedin social"></a>
	<a href="" class="fa fa-instagram social"></a>
	<a href="" class="fa fa-twitter social"></a>
	<br>
	<a href='' style="color:white">@gmail.com</a>
</div>
<!-- PHP TEST -->
<?php
// read products
function read(){
	//Connect to database
	$servername = "";
	$username = "";
	$password = "";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=;charset=utf8", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $conn->prepare("SELECT * FROM books");
		$stmt->execute();
		return $stmt;
	}
	catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
}

	// query products
$stmt = read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    $products_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $product_item=array(
            "id" => $id,
            "name" => $name,
            "author" => $author,
            "publisher" => $publisher,
            "image" => $image,
        );

        array_push($products_arr, $product_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    //echo json_encode($products_arr);
	$jsonData=json_encode($products_arr);
	file_put_contents('books.json', $jsonData);
}

// no products found will be here
?>
<!-- -->
<script>
function process(response) {
	var result="";
	var nextPageNumber=2;
	var pageName="page1";
	result='<div id="page1" class="w3-main w3-content w3-padding page" style="max-width:1200px;margin-top:2.5px;"><div class="w3-row-padding w3-padding-16 w3-center">';

	if(response.length==0) result+='<p>Sonuç bulunamadı.</p>';

	for(var i=0;i<response.length;i++) {
		//Grid başı
		if(i!=0&&i%4==0&&i%8!=0) {
			result+='</div><div class="w3-row-padding w3-padding-16 w3-center">';
		}
		//Sayfa başı
		else if(i!=0&&i%8==0) {
			pageName='page'+nextPageNumber+'';
			result+='</div><p style="margin-bottom:-5px;"><b>Sayfa:</b> '+(nextPageNumber-1)+'</p></div><div id="'+pageName+'" class="w3-main w3-content w3-padding page" style="max-width:1200px;margin-top:2.5px; display:none"><div class="w3-row-padding w3-padding-16 w3-center">';
			nextPageNumber++;
		}
		//Quarters
		result+='<div class="w3-quarter"><img src="'+response[i].image+'" alt="image" onclick="modalCreator('+response[i].id+')" style="width:100%">';
		result+='<h3 onclick="modalCreator('+response[i].id+')"><b>'+response[i].name+'</b></h3>';
		result+='<p><b>Yazar:</b> '+response[i].author+'<br><b>Yayınevi:</b> '+response[i].publisher+'</p></div>';
	}

	//Closing grid div, adding pagination and closing page div
	result+='</div><p style="margin-bottom:-5px;"><b>Sayfa:</b> '+(nextPageNumber-1)+'</p></div><div class="w3-center w3-padding-32"><div class="w3-bar"><p>';
	for(var j=1;j<nextPageNumber;j++) {
		result+='<span class="pageNumbers" onclick="show(page'+j+');">'+j+'</span>';
	}
	result+='</p></div></div>';

	document.getElementById("content").innerHTML = result;
}

function show(pageID) {
	var page=document.getElementById(pageID);

	if(!pageID) {
		alert("There is no such a page id");
		return;
	}
	var otherPages=document.getElementsByClassName('page');
	for(var i=0;i<otherPages.length;i++) {
		otherPages[i].style.display='none';
	}
	pageID.style.display='block';
}

function loadDoc() {
	getName(false);
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var parsedResponse=JSON.parse(this.responseText);
	  process(parsedResponse);
    }
  };
  xhttp.open("GET", "books.json", true);
  xhttp.send();
}

//Search function
$(document).ready(function(){
	var results=[];
	$('#searchBox').keyup(function(){
		//Düzenle
		var searchField=$('#searchBox').val();
		var expression=new RegExp(searchField, "i");
		setTimeout(deneme(), 3000);
		function deneme() {
			$.getJSON('books.json', function(data){
			$.each(data, function(key, value){
				if(value.name.search(expression)!=-1 || value.author.search(expression)!=-1 || value.publisher.search(expression)!=-1) {
					results.push(value);
				}
			});
		});
		}
		process(results);
		results.length=0;
	});
});

function doNothing() {
	return false;
}

function getDetails(bookID) {
	var modalBody=document.getElementById('modalBody');
	var editButton=document.getElementById('editButton');
	$.getJSON('books.json', function(data){
			$.each(data, function(key, value){
				if(value.id==bookID) {
					modalBody.innerHTML="<img src='"+value.image+"' alt='image' style='250px; height:350px; float:left; margin:auto;'><p style='margin:auto; margin-top:10%;'><b>Kitap Adı: </b>"+value.name+"</p><p style='margin:auto;'><b>Kitap Yazarı: </b>"+value.author+"</p><p style='margin:auto;'><b>Kitap Yayınevi: </b>"+value.publisher+"</p>";
					var idNumber=value.id;
					editButton.setAttribute('onclick','edit('+idNumber+')');
				}
			});
		});
}

function modalCreator(bookID) {
	var modal = document.getElementById('myModal');
	var closeButton = document.getElementById('closeButton');
	var editButton=document.getElementById('editButton');
	var cancelButton=document.getElementById('cancelButton');
	modal.style.display = "block";
	closeButton.onclick = function() {
		modal.style.display = "none";
		editButton.style.display='block';
		cancelButton.style.display='none';
	}
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
			editButton.style.display='block';
			cancelButton.style.display='none';
		}
	}
	cancelButton.onclick = function() {
		modal.style.display = "none";
		editButton.style.display='block';
		cancelButton.style.display='none';
	}
	getDetails(bookID);
}

function edit(bookID) {
	var modalBody=document.getElementById('modalBody');
	var editButton=document.getElementById('editButton');
	var cancelButton=document.getElementById('cancelButton');
	$.getJSON('books.json', function(data){
			$.each(data, function(key, value){
				if(value.id==bookID) {
					modalBody.innerHTML="<img src='"+value.image+"' alt='image' style='250px; height:350px; float:left; margin:auto;'><h2>Kitabı Düzenle</h2><form id='bookEditingForm' action='edit.php' method='post' enctype='multipart/form-data'><input type='text' name='bookID' id='bookID' style='display:none;' value='"+value.id+"' maxlength='40' required><input type='text' name='bookOldName' id='bookOldName' style='display:none;' value='"+value.name+"' maxlength='40' required><b>Kitap Adı: </b><input type='text' name='bookName' id='bookName' value='"+value.name+"' maxlength='40' required><br><b>Kitap Yazarı: </b><input type='text' name='author' id='author' value='"+value.author+"' maxlength='40' required><br><b>Kitap Yayınevi: </b><input type='text' name='publisher' id='publisher' value='"+value.publisher+"' maxlength='50' required><br><b>Yetki Kodu: </b><input type='password' name='authorizationCode' id='authorizationCode' placeholder='Yetki Kodu' maxlength='50' required><br><b>Kitap Resmi</b>: <input type='file' name='imageFile' id='imageFile' accept='image/*'><br><button type='submit' id='saveButton'><i class='fa fa-save'></i> Kaydet</button><button type='submit' id='deleteButton' formaction='delete.php'><i class='fa fa-trash'></i> Kitabı Sil</button></form>";
				}
			});
		});
	editButton.style.display='none';
	cancelButton.style.display='block';
}

function getName(reset) {
	if(reset==true) document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
	var readCookie = document.cookie;
	if(readCookie==null||readCookie=="") {
		var person = prompt("Hoş geldiniz. Lütfen isminizi yazınız:", "İsim vermek istemiyorum.");
		if(person=="İsim vermek istemiyorum."||person==null) document.cookie="username="+"";
		else document.cookie = "username="+person;
		prepareMessage(person);
	}
	else {
		var person="";
		var cname="username";
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(readCookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
			c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				person=c.substring(name.length, c.length);
				prepareMessage(person);
				break;
			}
		}
	}
}

function prepareMessage(person) {
	var date=new Date();
	var hour=date.getHours();

	if(person=="İsim vermek istemiyorum."||person==null||person=="") {
		if(hour>=6&&hour<=11) document.getElementById("welcomeMessage").innerHTML="Günaydın";
		else if(hour>11&&hour<17) document.getElementById("welcomeMessage").innerHTML="İyi Günler";
		else if(hour>=17&&hour<23) document.getElementById("welcomeMessage").innerHTML="İyi Akşamlar";
		else if(hour==23||hour<6) document.getElementById("welcomeMessage").innerHTML="İyi Geceler";
	}
	else {
		if(hour>=6&&hour<=11) document.getElementById("welcomeMessage").innerHTML="Günaydın, "+person;
		else if(hour>11&&hour<17) document.getElementById("welcomeMessage").innerHTML="İyi Günler, "+person;
		else if(hour>=17&&hour<23) document.getElementById("welcomeMessage").innerHTML="İyi Akşamlar, "+person;
		else if(hour==23||hour<6) document.getElementById("welcomeMessage").innerHTML="İyi Geceler, "+person;
	}
}
</script>
</body>
</html>
