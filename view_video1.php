<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin_form.php");
    exit();
}

// Redirect if filename is not provided
if (!isset($_GET['filename']) || empty($_GET['filename'])) {
    header("Location: secure.php");
    exit();
}

$filename = $_GET['filename'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "NetflixWeb");

// Fetch video details based on filename
$query = "SELECT * FROM videos WHERE filename = '$filename'";
$result = mysqli_query($conn, $query);

// Check if video exists
if (mysqli_num_rows($result) == 1) {
    $video = mysqli_fetch_assoc($result);
} else {
    echo "Video not found.";
    exit();
}

$videoId = $video['id'];
$userId = $_SESSION['id'];

// Fetch existing comments for the video
$fetchCommentsQuery = "SELECT comments.*, users.username, DATE_FORMAT(upload_datetime, '%W, %M %e, %Y, %l:%i %p') AS formatted_datetime
                       FROM comments 
                       INNER JOIN users ON comments.commenter_id = users.id
                       WHERE comments.video_id = $videoId
                       ORDER BY comments.upload_datetime DESC";
$commentsResult = mysqli_query($conn, $fetchCommentsQuery);

// Check if user has liked or disliked the video
$checkLikeQuery = "SELECT * FROM likes WHERE video_id = $videoId AND user_id = $userId";
$checkDislikeQuery = "SELECT * FROM dislikes WHERE video_id = $videoId AND user_id = $userId";

$hasLiked = mysqli_num_rows(mysqli_query($conn, $checkLikeQuery)) > 0;
$hasDisliked = mysqli_num_rows(mysqli_query($conn, $checkDislikeQuery)) > 0;

// Count total likes and dislikes
$countLikesQuery = "SELECT COUNT(*) AS total_likes FROM likes WHERE video_id = $videoId";
$countDislikesQuery = "SELECT COUNT(*) AS total_dislikes FROM dislikes WHERE video_id = $videoId";

$totalLikesResult = mysqli_query($conn, $countLikesQuery);
$totalLikes = mysqli_fetch_assoc($totalLikesResult)['total_likes'];

$totalDislikesResult = mysqli_query($conn, $countDislikesQuery);
$totalDislikes = mysqli_fetch_assoc($totalDislikesResult)['total_dislikes'];

// Process form submission to add new comment
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    $commenter_id = $_SESSION['id'];

    $insertCommentQuery = "INSERT INTO comments (video_id, commenter_id, comment, upload_datetime) 
                       VALUES ($videoId, $commenter_id, '$comment', NOW())";

    if (mysqli_query($conn, $insertCommentQuery)) {
        // Redirect to prevent form resubmission
        header("Location: view_video1.php?filename=$filename");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>View Video</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin:0;
            padding: 20px ;
			background-image: url('images/banner.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;

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

    height: 500%;
    background-color: rgba(0, 0, 0, 0.8); 
    z-index: -1;
     }
        .like,
        .dislike {
            background-color: white;
            border: 1px solid black;
            color: black;
            cursor: pointer;
        }

        .like.clicked {
            background-color: green;
            color: white;
        }

        .dislike.clicked {
            background-color: red;
            color: white;
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
        .logo{
			width:110px;
            margin-left:10px;
		}
    </style>
</head>

<body>
   
<a href="Main.php" ><img class="logo" src="images\chilix.png"/></a>
	<br>
   
 
        <a href="Main.php" style="float: right;" class="logout">Back</a>
   

   <div class="container">
        <div class="row">
            
            <div class="col-md-8">
                        <video width="640" height="360" controls>
                    <source src="uploads/<?php echo $filename; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <h2><?php echo $video['title']; ?></h2>
                <p style="color:grey;font-size:16px;"><?php echo $video['description']; ?></p>
                <!-- Like and dislike buttons -->

                <form action="" method="POST" id="likeDislikeForm">
                    <button style="background-color:green;color:white; padding: 5px 10px;" type="submit" name="like" class="like <?php echo $hasLiked ? 'clicked' : ''; ?>">Like</button>
                    <span><?php echo $totalLikes; ?> Likes</span>
                    <button style="background-color:red;color:white; padding: 5px 10px;" type="submit" name="dislike" class="dislike <?php echo $hasDisliked ? 'clicked' : ''; ?>">Dislike</button>
                    <span><?php echo $totalDislikes; ?> Dislikes</span>
                </form>
            </div>


            <div class="col-md-4">
                 

                    <!-- Add comment form -->
                    <div>
                        <h3>Add a Comment</h3>
                        <form action="" method="POST">
                            <textarea style="background-color:#F7F7F7;width:100%;height:100px;border:none"  name="comment" rows="4" cols="50" placeholder="Enter your comment" required></textarea>
                            <br>
                            <button style="    color: white;
    background-color: #5CD000;
    border: none;
    padding: 5px 10px;
    margin-left: 65.7%;
    font-size: 12px;
    font-weight: 700;" type="submit" name="submit_comment">Submit Comment</button>
                        </form>
                    </div>
                    <!-- Comments section -->
                    <div>
                        <h5><b>Comments</b></h5>
                        <?php
if (mysqli_num_rows($commentsResult) > 0) {
    while ($comment = mysqli_fetch_assoc($commentsResult)) {
        // Fetching the first letter of the username
        $firstLetter = strtoupper(substr($comment['username'], 0, 1));

        // Generating a random background color for the circle
        $randomColor = '#' . substr(md5(mt_rand()), 0, 6);

        // Displaying the username with the first letter inside a circle
        echo '<span style="display: inline-block; background-color: ' . $randomColor . '; color: white; width: 30px; height: 30px; text-align: center; line-height: 30px; border-radius: 50%; margin-right: 10px;">' . $firstLetter . '</span>';
        echo '<span style="text-transform: uppercase;">' . $comment['username'] . '</span><br><span style="font-size:11px">' . $comment['formatted_datetime'] .'</span></span>';
        echo '<span style="color:grey; font-size:14px"><br>' . $comment['comment'] . '</span></span><br><br>';
    }
    }

else {
    echo '<p>No comments yet.</p>';
}
?>


                        
                                    </div>

                   </div>
        </div>
    </div>

    

   
    <?php
    // Handle like and dislike submission
    if (isset($_POST['like'])) {
        if (!$hasLiked) {
            // If the user has previously disliked, remove the dislike
            if ($hasDisliked) {
                $deleteDislikeQuery = "DELETE FROM dislikes WHERE video_id = $videoId AND user_id = $userId";
                mysqli_query($conn, $deleteDislikeQuery);
            }
            $likeQuery = "INSERT INTO likes (video_id, user_id) VALUES ($videoId, $userId)";
            mysqli_query($conn, $likeQuery);
            // Reload the page to update the button status
            header("Location: view_video1.php?filename=$filename");
            exit();
        } else {
            // If the user has already liked, remove the like
            $deleteLikeQuery = "DELETE FROM likes WHERE video_id = $videoId AND user_id = $userId";
            mysqli_query($conn, $deleteLikeQuery);
            // Reload the page to update the button status
            header("Location: view_video1.php?filename=$filename");
            exit();
        }
    }

    if (isset($_POST['dislike'])) {
        if (!$hasDisliked) {
            // If the user has previously liked, remove the like
            if ($hasLiked) {
                $deleteLikeQuery = "DELETE FROM likes WHERE video_id = $videoId AND user_id = $userId";
                mysqli_query($conn, $deleteLikeQuery);
            }
            $dislikeQuery = "INSERT INTO dislikes (video_id, user_id) VALUES ($videoId, $userId)";
            mysqli_query($conn, $dislikeQuery);
            // Reload the page to update the button status
            header("Location: view_video1.php?filename=$filename");
            exit();
        } else {
            // If the user has already disliked, remove the dislike
            $deleteDislikeQuery = "DELETE FROM dislikes WHERE video_id = $videoId AND user_id = $userId";
            mysqli_query($conn, $deleteDislikeQuery);
            // Reload the page to update the button status
            header("Location: view_video1.php?filename=$filename");
            exit();
        }
    }
    ?>
</body>

</html>
