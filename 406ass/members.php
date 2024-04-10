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
            <p><a href="home.php">Mojo's Dojo</a> </p>
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

            <a href="finance.php"> <button class="btn">Club Finances</button> </a>
            <a href="members.php"> <button class="btn">Members</button> </a>
            <a href="php/logout.php"> <button class="logbtn">Log Out</button> </a>

        </div>
    </div>
    <main>

       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Here is your list of members <b><?php echo $res_Uname ?></b>-kun :></p>
            </div>

          </div>
          <div class="bottom">
            
          <div class="dropdown">
                <button onclick="toggleDropdown()" style="width: 400px;">Click to choose</button>
                <div id="dropdownContent" class="content" style="display: none;">
                    <a onclick="showInformation('Jake')">Jake</a>
                    <a onclick="showInformation('Jason')">Jason</a>
                    <a onclick="showInformation('Quandale')">Quandale</a>
                </div>
            </div>
          </div>
       </div>

       <script>
        function toggleDropdown() {
        var dropdownContent = document.getElementById('dropdownContent');
        if (dropdownContent.style.display === 'none') {
            dropdownContent.style.display = 'block';
        } else {
            dropdownContent.style.display = 'none';
        }
    }

    function showInformation(name) {
        // Create a message string
        var message = "Hello " + name;
        // Get the container element
        var messageContainer = document.getElementById('messageContainer');
        // Update the HTML content of the container
        messageContainer.innerHTML = message;
        // Hide the dropdown after selecting an option
        document.getElementById('dropdownContent').style.display = 'none';
    }
        </script>


    </main>
</body>
</html>