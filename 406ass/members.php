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
        <?php
        $id = $_SESSION['id'];
        $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

        while ($result = mysqli_fetch_assoc($query)) {
            $res_Uname = $result['Username'];
            $role = $result['Role'];
        }

        // Determine the link based on the user's role
        $logoLink = ($role === 'Coach') ? 'coach.php' : 'admin.php';
        ?>

        <p><a href="<?php echo $logoLink; ?>">Mojo's Dojo</a></p>
    </div>

    <div class="right-links">
        <?php if ($role === 'Admin') : ?>
            <!-- Only visible to admins -->
            <a href="finances.php"><button class="btn">Club Finances</button></a>
        <?php endif; ?>
        <!-- Visible to both admin and coach -->
        <a href="members.php"><button class="btn">Members</button></a>
        <a href="php/logout.php"><button class="logbtn">Log Out</button></a>
    </div>
    </div>

    <main>

       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Here is your list of members <b><?php echo $res_Uname ?></b>-kun :> </p>
            </div>

          </div>
          <div class="bottom">
        

          <div class="box">
            <p><i> --Select name of member to view and update member information-- </i></p>
          <div class="dropdown">
                <button class="btn" onclick="toggleDropdown()" style="width: 100%">Click to choose</button>
                <div id="dropdownContent" class="content" style="width:66.5%">
                <?php 
                    $query = mysqli_query($con,"SELECT * FROM users WHERE Role='Member'");
                    $members = [];
                    
                    if(mysqli_num_rows($query) > 0) {
                        while($result = mysqli_fetch_assoc($query)){
                            $members[] = $result;
                            $res_Uname = $result['Username'];
                            echo "<a onclick='showInformation(\"$res_Uname\")'>$res_Uname</a>";
                        }
                    } else {
                        echo "<p>No members found.</p>";
                    }
                    ?>
                </div>
                </div>

                <div class="bottom">
                <div class="box" id="memberInformation">
                    <!-- Member information will be displayed here -->
                </div>
                </div>
                </div>

            
            



            <!-- Member database update-->
            <?php 
            include("php/config.php");

            if(isset($_POST['submit'])){
                $attended = mysqli_real_escape_string($con, $_POST["attended"]);
                $paid =  mysqli_real_escape_string($con, $_POST["paid"]);
                $id = mysqli_real_escape_string($con, $_POST["memberid"]);


                $query = "UPDATE users SET ClassesAttended = '$attended', ClassesPaidFor = '$paid' WHERE Id='$id'";
                $result = mysqli_query($con, $query);
              
            }
            ?>


            <script>
                function toggleDropdown() {
                    var dropdownContent = document.getElementById('dropdownContent');
                    dropdownContent.style.display = (dropdownContent.style.display === 'none') ? 'block' : 'none';
                }

                function showInformation(name) {
                var memberData = <?php echo json_encode($members); ?>;
                if (memberData) {
                    var selectedMember = memberData.find(member => member.Username === name);
                    if (selectedMember) {
                        var memberInfoHTML = `
                        <form id="updateForm" method="post">
                        <p>Member ID: <span id="memberid"><b>${selectedMember.Id}</b></span></p>
                        <input type="hidden" name="memberid" value="${selectedMember.Id}">

                        <p>Name: <b>${selectedMember.Username}</b></p>

                        <p>Classes Attended: <span id="attended"> ${selectedMember.ClassesAttended} </span><button type="button" class="btn" onclick="increment('attended')">+</button></p>
                        <input type="hidden" name="attended" id="attendedInput" value="${selectedMember.ClassesAttended}">

                        <p>Classes Paid: <span id="paid"> ${selectedMember.ClassesPaidFor} </span><button type="button" class="btn" onclick="increment('paid')">+</button></p><br>
                        <input type="hidden" name="paid" id="paidInput" value="${selectedMember.ClassesPaidFor}">

                        <p>Payments pending: <span id="pending"> ${selectedMember.ClassesAttended - selectedMember.ClassesPaidFor}</span></p>

                        <button class="btn" name="submit" type="submit" style="width:100%">Update</button>
                        </form>
                        `;
                        document.getElementById('memberInformation').innerHTML = memberInfoHTML;
                    } else {
                        document.getElementById('memberInformation').innerHTML = "<p>Member information not found.</p>";
                    }
                } else {
                    document.getElementById('memberInformation').innerHTML = "<p>Error: Member data not available.</p>";
                }
                // Hide the dropdown after selecting an option
                document.getElementById('dropdownContent').style.display = 'none';
            }


            function increment(toUpdate){
                        var element = document.getElementById(toUpdate);
                        var currentValue = parseInt(element.textContent);
                        currentValue++;
                        element.textContent = currentValue;

                        document.getElementById(toUpdate + 'Input').value = currentValue;

                        var attended = parseInt(document.getElementById('attended').textContent);
                        var paid = parseInt(document.getElementById('paid').textContent);
                        var pending = document.getElementById('pending');
                        pending.textContent = attended - paid;
                        }

            </script>



<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        text-align: center;
        padding: 8px;
    }
</style>
            
<div class="bottom">
    <div class="box">
        <div class="members-list">
            <button id="sortByPaymentBtn" style="width:50%" class="btn" onclick="sortByPayment()">Sort Members by Payment</button>
            <button id="sortByAttendanceBtn" style="width:49%" class="btn" onclick="sortByAttendance()">Sort Members by Attendance</button>
        </div>
        <div id="membersTableContainer" style="text-align: center;">
            <?php
            // Check if the sorting method is provided in the URL
            $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'payment';

            displaySortedTable($sortBy);
            ?>
        </div>
    </div>
</div>

<script>
    function sortByPayment() {
    window.location.href = 'members.php?sortBy=payment';
}

function sortByAttendance() {
    window.location.href = 'members.php?sortBy=attendance';
}
</script>


<?php
function displaySortedTable($sortBy) {
    global $con;

    // Determine the SQL query based on the sorting method
    $orderBy = ($sortBy === 'attendance') ? 'ClassesAttended DESC' : 'ClassesAttended - ClassesPaidFor DESC';
    $query = mysqli_query($con, "SELECT * FROM users WHERE Role='Member' ORDER BY $orderBy");

    // Display the sorted table
    echo '<table>';
    echo '<thead><tr><th><u>NAME</u></th><th><u>' . (($sortBy === 'attendance') ? 'ATTENDED' : 'PENDING PAYMENTS') . '</u></th></tr></thead>';
    echo '<tbody>';
    if (mysqli_num_rows($query) > 0) {
        while ($result = mysqli_fetch_assoc($query)) {
            $res_Uname = $result['Username'];
            if ($sortBy === 'attendance') {
                $attended = $result['ClassesAttended'];
                $info = $attended . ' classes';
            } else {
                $paid = $result['ClassesPaidFor'];
                $attended = $result['ClassesAttended'];
                $pending = $attended - $paid;
                $info = $pending . ' classes';
            }

            echo "<tr><td>$res_Uname</td><td>$info</td></tr>";
        }
    } else {
        echo '<tr><td colspan="2">No members found.</td></tr>';
    }
    echo '</tbody></table>';
}
?>



    </main>
</body>
</html>