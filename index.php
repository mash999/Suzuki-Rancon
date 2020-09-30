<?php 
session_start();
if(isset($_SESSION['username'])) $username = $_SESSION['username'];
else $username = "";
?>
<style>
	@charset "UTF-8";
/* CSS Document */

body {
    width:100px;
	height:100px;
  background: -webkit-linear-gradient(90deg, #16222A 10%, #3A6073 90%); /* Chrome 10+, Saf5.1+ */
  background:    -moz-linear-gradient(90deg, #16222A 10%, #3A6073 90%); /* FF3.6+ */
  background:     -ms-linear-gradient(90deg, #16222A 10%, #3A6073 90%); /* IE10 */
  background:      -o-linear-gradient(90deg, #16222A 10%, #3A6073 90%); /* Opera 11.10+ */
  background:         linear-gradient(90deg, #16222A 10%, #3A6073 90%); /* W3C */
font-family: 'Raleway', sans-serif;
}

p {
	color:#CCC;
}


img#bg
{
	max-width: 90%;
	height: auto;
	border-radius: 5px;
}


#singlebutton
{
	background: #293949 !important;
	border: 1px solid #293949;	
	color: #fff;
}

.spacing {
	padding-top:7px;
	padding-bottom:7px;
}
.middlePage {
	width: 680px;
    height: 500px;
    position: absolute;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

.logo {
	color:#CCC;
}
</style>

<title>Login | Rancon Motor</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>

<body>
<div class="middlePage">
<div class="page-header">
  <h1 class="logo">Rancon Motor <small>Login Panel</small></h1>
</div>

<?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
  echo "<p style='font-size: 17px; font-family: helvetica; margin-bottom: 15px;'>$_SESSION[msg]</p>";
  $_SESSION['msg'] = "";
}
?>

<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Please Sign In </h3>
  </div>
  <div class="panel-body">
  
  <div class="row">
  
<div class="col-md-5" >
	<img id="bg" src="img/bg.JPG">
</div>

    <div class="col-md-7" style="border-left:1px solid #ccc;height:160px">
<form action="functions/process-forms.php" method="post" class="form-horizontal">
<fieldset>
	

  <input id="textinput" name="username" type="text" placeholder="Enter User Name" value="<?php echo $username;?>" class="form-control input-md">
  <div class="spacing"></div>
  <input id="textinput" name="password" type="password" placeholder="Enter Password" class="form-control input-md">
  <div class="spacing"></div>
  <button id="singlebutton" name="login-user" class="btn btn-sm">Sign In</button>

</fieldset>
</form>
</div>
    
</div>
    
</div>
</div>

<p>Developed By <a href="http://whitepaper.tech/" target="_blank">Whitepaper</a></p>

</div>
</body>