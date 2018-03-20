<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Assignment 2 (Winter 2018)</title>
    
          <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        
        
    
        <link rel="stylesheet" href="css/captions.css" />
        <!--<link rel="stylesheet" href="css/bootstrap-theme.css" />-->
        <link rel="stylesheet" href="css/format.css" />
        <link rel="stylesheet" href="css/theme.css" />
        
        <link rel="stylesheet" href="css/general.css"/>
    
    </head>
    
    <body>
        
        <header>
            <?php 
            
            if(isset($_SESSION['user'])){
                include 'includes/headerLogout.inc.php'; 
                
            }else if (!isset($_SESSION['user'])){
                include 'includes/header.inc.php'; 
            }
            
            ?>
        </header>
        <main class="container">
            <form action="session.php" method="post">
  

  <div class="jumbotron">
      <?php
      if(isset($_SESSION['error'])){
        echo' <div class="alert alert-danger" id="error" role="alert" style="visibility:visible">';
             echo'  INVALID PASSWORD/USERNAME TRY AGAIN';
             echo' </div>';
             unset($_SESSION['error']);
        
        }
      
      ?>
      
      <script>
      function err(){
        var a = document.getElementById("error");
        
            
            a.style="visibility: hidden";
            
      }
        
        </script>
      
      
      <div class="row">
      
    <label for="uname"><b>Username</b></label>
    <input type="text" onkeydown="err()" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" onkeydown="err()" placeholder="Enter Password" name="psw" required>
        
    <button type="submit">Login</button>
    
  </div>

  
  </div>
</form>
        </main>
        
        
        <footer>
            <div class="container-fluid">
                        <div class="row final">
                    <p>Copyright &copy; 2017 Creative Commons ShareAlike</p>
                    <p><a href="#">Home</a> / <a href="#">About</a> / <a href="#">Contact</a> / <a href="#">Browse</a></p>
                </div>            
            </div>
        </footer>
        
        
        
    </body>
    
    
</html>