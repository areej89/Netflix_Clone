<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
	
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
 <div class="container">
    
    <form action="signin_process.php" method="POST">
	<h2>Sign In</h2>
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" placeholder="Enter Username"><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter Password"><br><br>
		 <button type="submit"  class="email-button">Sign In</button>
    
    </form>
	</div>
</body>
</html>
