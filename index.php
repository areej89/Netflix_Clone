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

// Fetch genres from the genres table
$genreQuery = "SELECT DISTINCT genre_name FROM genres";
$genreResult = mysqli_query($conn, $genreQuery);
$genres = [];
if ($genreResult && mysqli_num_rows($genreResult) > 0) {
    while ($row = mysqli_fetch_assoc($genreResult)) {
        $genres[] = $row['genre_name'];
    }
}

// Fetch age ratings from the agerating table
$ageRatingQuery = "SELECT DISTINCT rating_name FROM agerating";
$ageRatingResult = mysqli_query($conn, $ageRatingQuery);
$ageRatings = [];
if ($ageRatingResult && mysqli_num_rows($ageRatingResult) > 0) {
    while ($row = mysqli_fetch_assoc($ageRatingResult)) {
        $ageRatings[] = $row['rating_name'];
    }
}

// Check if the user clicked the search button
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $genre = isset($_POST['genre']) ? $_POST['genre'] : '';
    $ageRating = isset($_POST['age_rating']) ? $_POST['age_rating'] : '';

    // Construct the query based on search terms
    $query = "SELECT * FROM videos WHERE (title LIKE '%$search%' OR description LIKE '%$search%' OR Producer LIKE '%$search%' OR Genre LIKE '%$search%' OR AgeRating LIKE '%$search%')";
    if ($genre != '') {
        $query .= " AND Genre = '$genre'";
    }
    if ($ageRating != '') {
        $query .= " AND AgeRating = '$ageRating'";
    }

    $result = mysqli_query($conn, $query);
} else {
    // If not, fetch all videos
    $query = "SELECT * FROM videos";
    $result = mysqli_query($conn, $query);
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
    <title>Netflix</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
 
    margin-top:10%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.content {
    text-align: center;
    padding: 20px;
    border-radius: 5px;
 
}

h1 {
    margin-top: 0;
}

.email-form {
    display: flex;
    margin-top: 20px;
}

.email-input {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 2px;
    margin-right: 5px;
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

p {
    font-size: 0.9rem;
}

		
		.logo{
			width:110px;
		}
		
		button{
			background-color:#E50914;
			color:white;
		}
		

        .logout {
			background-color:#E50914;
			color:white;
            float: right;
            margin-left: 10px;
			 padding: 3px 8px;
			 border-radius:4px;
			 Text-Decoration: None !important; 
        }
		a:hover{
			color:white;
		}
		.signup{
			background-color:#E50914;
			color:white;
            float: right;
            margin-left: 10px;
			 padding: 3px 8px;
			 border-radius:4px;
			 Text-Decoration: None !important; 
        }
		.search{
			background-color:#E50914;
			color:white;
            float: right;
            margin-left: 10px;
			 padding: 3px 8px;
			 border-radius:4px;
			 Text-Decoration: None !important; 
		}

        .search-form {
            margin-bottom: 20px;
        }

        .video-link {
            display: block;
            margin-bottom: 10px;
            color: #000;
            text-decoration: none;
        }

        .video-link:hover {
            color: #007bff;
        }

        #signin-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
		
		.searchbar{
			color:white;
  position: absolute;
  top: 40%;
  left: 40%;
  transform: translate(-40%, -40%);
  padding: 10px;
  text-align:center;
		}
		
		.searchbar h2{
			Font-size:40px;
			text-align:center;
		}
		
		.searchbar .search-form{
			width:100%;
		}
		

    </style>
</head>

<body>
   
	<a href="Main.php" ><img class="logo" src="images\chilix.png"/></a>
	<br>
              <div style="margin-top:-20px">
                  <?php if (isset($_SESSION['username'])) : ?>
                    <span class="username">Welcome, <?php echo $_SESSION['username']; ?></span>
                    <a class="logout" href="logout.php">Logout</a>
                    
                <?php else : ?>
                    <a class="signup" href="signup_form.php">Sign Up</a>
                    <a class="logout" href="signin_form.php" >Sign In</a>
                <?php endif; ?>
            </div>
	
	 <div class="container">
        <div class="content">
            <h1>Unlimited films, TV programmes and more.</h1>
            <p>Watch anywhere. Cancel at any time.</p>
            <p>Ready to watch? Enter your email to create or restart your membership.</p>
            <div class="email-form">
                <input type="email" placeholder="Email address" class="email-input">
                <button class="email-button">Get Started</button>
            </div>
        </div>
    </div>

    <!-- Search form 
    <form class="search-form" action="" method="POST">
        <input type="text" name="search" class="transparent-input" placeholder="Search videos">
        <button type="submit" class="search">Search</button>
    </form>
</div>
    <!-- Display search results 
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                // Display each search result as a table row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td><a class="video-link" href="view_video1.php?filename=' . $row['filename'] . '">' . $row['title'] . '</a></td>';
                    echo '<td>' . $row['description'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">No videos found</td></tr>';
            }
            ?>
        </tbody>
    </table>
	-->

    <!-- Sign-in form -->
    <div id="signin-form">
        <span class="close-btn">&times;</span>
        <h3>Sign In</h3>
        <form action="signin_process.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
			
            <button type="submit" >Sign In</button>
        </form>
    </div>

    <script>
        // Show sign-in form when "Sign In" link is clicked
        document.getElementById("signin-link").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("signin-form").style.display = "block";
        });

        // Close sign-in form when close button is clicked
        document.querySelector(".close-btn").addEventListener("click", function() {
            document.getElementById("signin-form").style.display = "none";
        });
    </script>

</body>

</html>

<?php
mysqli_close($conn);
?>
