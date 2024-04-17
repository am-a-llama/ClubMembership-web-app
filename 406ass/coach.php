<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Home</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="admin.php">Mojo's Dojo</a> </p>
        </div>

        <div class="right-links">

            <?php 
            
            $id = $_SESSION['id'];
            $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_id = $result['Id'];
            }
            
            ?>

            <a href="members.php"><button class="btn">Members</button></a>
            
            <a href="php/logout.php"> <button class="logbtn">Log Out</button> </a>

        </div>
    </div>
    <main>

    <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Welcome Sensei <b><?php echo $res_Uname ?></b>-kun :3</p>
            </div>
            <div class="box">
                <p>We missed you </p>
            </div>
          </div>
          <div class="bottom">
            <div class="box">
               <p><h1>Discussion Board</p>
               <div class="bottom"></div>


       <!-- Message Form -->
        
       <?php 
            include("php/config.php");

            if(isset($_POST['submit'])){
                $message = mysqli_real_escape_string($con, $_POST['message']);

                $query = "INSERT INTO messages (UserID, Username, Message) VALUES ('$res_id', '$res_Uname', '$message')";
                $result = mysqli_query($con, $query);

              
         }
    

        ?>

            <div id="messageForm" class="message-form">
            <form action="" method="post">
            <textarea style="font-size: 15pt " rows="2" cols="100" name="message" placeholder="Whats on your mind today.." required></textarea>
            <button class="btn" type="submit" name="submit" style="width: 100%"> POST </button>
            </form>
            </div>
            </div>


            <!-- all of the Communications area -->


            <!-- Delete message from database -->
            <?php 
            include("php/config.php");

            if(isset($_POST['delete_message'])){
                $messageID = $_POST['message_id'];


                $deleteQuery = "DELETE FROM messages WHERE MessageID = '$messageID'";
                $deleteResult = mysqli_query($con, $deleteQuery);
              
            }
            ?>
            <!----------------------------------------->




            <div class="bottom">
                <div class="box">
                    <?php 
                    $query = mysqli_query($con, "SELECT messages.*, users.Role FROM messages JOIN users ON messages.Username = users.Username ORDER BY MessageID DESC");

                    // Check if there are messages
                    if(mysqli_num_rows($query) > 0) {
                        while($result = mysqli_fetch_assoc($query)){
                            $res_Uname = $result['Username'];
                            $message = $result['Message'];
                            $res_role = $result['Role'];
                            $messageID = $result['MessageID'];



                        // coach announcements through different colored textbox
                        $messageClass = ($res_role == 'Coach') ? 'coach-box' : 'regular-box';
                        $roleColor = ($res_role == 'Admin') ? 'color: red;' : ''; // Add this line

                        echo "<div class='box $messageClass'>";
                        echo "<p><b style='$roleColor'>$res_Uname</b> the $res_role says,</p>"; // Modify this line
                        echo "<p>$message</p>";
                        echo "<br>";
                        
                        
                        // Admin/Coach able to delete messages from discussion board
                        if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Coach') {
                            echo "<form action='' method='post'>";
                            echo "<input type='hidden' name='message_id' value='$messageID'>";
                            echo "<button class='delete-button' type='submit' name='delete_message'>Delete</button>";
                            echo "</form>";
                        }

                        echo "</div>";
                        }
                    } else {
                        echo "<p>No messages yet.</p>";
                    }
                    ?>
                </div>
                </div>

    </main>
</body>
</html>