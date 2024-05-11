<?php

session_start();

// Database connection
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "NetflixWeb";

$conn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Process sign-up form submission
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    // Insert data into users table
    $signup_query = "INSERT INTO users (username, password, FName, LName, Email, ContactNumber) VALUES ('$username', '$password', '$fname', '$lname', '$email', '$contact')";
    if (mysqli_query($conn, $signup_query)) {
        // Redirect to sign-in page after successful sign-up
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $signup_query . "<br>" . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
	
	<Style>
	 body {
            font-family: Arial, sans-serif;
            margin:0;
            padding: 20px ;
			background-image: url('images/banner.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    
	width:100%
    height: 100%;
    color: #fff;
    font-family: 'Arial', sans-serif;
   
        }
		body::before {
    content: ''; /* Required for pseudo-elements */
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
	width:100%
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
    z-index: -1; /* Ensures the overlay is behind the content */
}

.container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
           background-color: rgba(0, 0, 0,.7);
            padding: 20px;
            border-radius: 5px;
			height:80%;
           
}

form{
	height:300px;
	width:300px;
	padding:20px;
}

input {
	margin-top:10px;
	margin-bottom:10px;
  background: transparent;
  border: .5px solid white;
  flex: 1;
    padding: 10px;
  width:100%;
    border-radius: 2px;
    margin-right: 5px;
	color:white;
}
	.email-button {
    padding: 10px 20px;
    background-color: red;
    color: white;
    border: none;
    border-radius: 2px;
    cursor: pointer;
    font-weight: bold;
}

.email-button:hover {
    background-color: darkred;
}

input::placeholder {
  color: #999; 
}

		.logo{
			width:110px;
		}
	</Style>
</head>
<body>
<a href="index.php" ><img class="logo"src="images\chilix.png"/></a>
 
	
	 <div id="signup-form" class="container">
            <div class="modal-content">
                
                <h3>Sign Up</h3>
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number" required>
                    </div>
                    <button type="submit" name="signup" class="email-button">Sign Up</button>
                </form>
            </div>
        </div>

</body>
</html>
