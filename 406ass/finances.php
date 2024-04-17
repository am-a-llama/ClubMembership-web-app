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
                <p><h2><i>Financial Overview for the Past Month</i> </h2> </p>
            </div>

          </div>
          <div class="bottom">
        

          <div class="box">
    <h1>April 2024</h1>

    <h3>Income:</h3>
    <ul>
        <li>Membership Fees: $15,000</li>
        <li>Sponsorship Revenue: $5,000</li>
        <li>Merchandise Sales: $3,000</li>
    </ul>
    <p><strong>Total Income:</strong> $23,000</p><br>

    <h3>Expenses:</h3>
    <ul>
        <li>Coach's Salary: $3,000</li>
        <li>Rent for the Training Hall: $1,500</li>
        <li>Utility Bills: $500</li>
        <li>Equipment Maintenance: $300</li>
        <li>Advertising and Marketing: $700</li>
        <li>Insurance Premiums: $400</li>
        <li>Office Supplies: $200</li>
        <li>Travel Expenses: $1,000</li>
        <li>Professional Fees: $600</li>
        <li>Training and Education: $800</li>
        <li>Taxes and Licenses: $500</li>
        <li>Repairs and Maintenance: $400</li>
        <li>Website Hosting and Maintenance: $300</li>
        <li>Membership Fees for Professional Organizations: $200</li>
        <li>Miscellaneous Expenses: $300</li>
    </ul>
    <p><strong>Total Expenses:</strong> $10,700</p><br>

    
          <div class="top">
            <div style="background-color:yellow" class="box">
    <h3>Net Profit:</h3>
    <p>Total Income - Total Expenses = $23,000 - $10,700 = <b><u>$12,300</u></b></p>
</div>


    

</main>
</body>
</html>