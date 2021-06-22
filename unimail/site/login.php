
<?php
session_start();
include 'functions.php';
include 'connect.php';
if (isset($_SESSION['S_type']))
{
    header('Location: main.php');
}
$count = 3;
if($_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $email = $_POST['emaillog'];
    $hached_pass = sha1($_POST['passlog']);
    
    $stmt = $db->prepare
    ("SELECT id , name , surname , type , groupe 
    FROM memb 
    WHERE email = ? AND pass = ?");

    $stmt->execute(array($email,$hached_pass));

    $count = $stmt->rowCount();

    if($count == 0)
    {
        $count = 2;
    }
    else 
    {
        $row = $stmt->fetch();
        $_SESSION['S_id'] = $row['id'];
        $_SESSION['S_name'] = $row['name'];
        $_SESSION['S_surname'] = $row['surname'];
        $_SESSION['S_type'] = $row['type'];
        $_SESSION['S_groupe'] = $row['groupe'];

        header('Location: main.php');
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    if($count==2)
    {
        alert('Wrong email or password !');
    }
    ?>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- styles -->
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/login.css">
    <!--font poopins -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap">
    
    <title>Login</title>
    <link rel="icon" href="res/unimail_icon.png"> 

</head>
<body>
    
<div class="container">
  <div class="row">
        <div class="col-md-12 col-lg-6" id="half">
                <div class="card shadow-sm">
                    <div class="card-header  shadow-sm ps-md-5 px-md-4" id= "cardheader" style="background-color: white; font-weight : 700; font-size: large;    padding-top: 5%; padding-bottom: 5%; ">
                        <div class="card-heading text-primary">Login</div>
                        <p><span class ="text-muted" style="font-size: small;">Forgot Password? </span> <span><a href="https://www.facebook.com/aimen.aimenrma" target="_blank"  style="font-size: small;">Contact us.</a></span></p>
                    </div>

                    <div class="card-body p-md-5">

                        <form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="emaillog">
                             </div>

                             <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="passlog">
                             </div>

                             <div class="mb-1 form-check">
                             <!--remember me--> 
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                            
                    </div>
                    <div class="container ps-md-5">
                        <p><span class ="text-muted" style="font-size: small;">Don't have an account? </span> <span style="font-size: small;"><a href="registre.php">Registre.</a></span></p>
                    </div>
                </div>
        </div>

        <div class="col-md-12 col-lg-6 text-center p-5" id="half">
            <br><br>
            <p style="font-weight: 400; font-size :larger">Welcome to <span style="color: #0d6efd; font-weight: 700 ; font-size: 1.25rem;">Uni Mail</span></p>
            <p class="text-muted">Keep up to date with your university.</p>

        </div>
  </div>
</div>
    <!--script bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>
