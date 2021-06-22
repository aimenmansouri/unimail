<?php
session_start();

if (isset($_SESSION['S_type']))
{
    header('Location: main.php');
}

include 'connect.php';
include 'functions.php';

    $valid_lastname = true;
    $valid_firstname = true;
    $valid_password = true;
    $valid_email = true;
    $email_err = 'Email already exist.';

if($_SERVER['REQUEST_METHOD'] == 'POST' )
{

    $name;
    $surname;
    $pass;
    $email;
    $photo;
    $groupe;

    

    $reg_email = $_POST['reg_email'];

    if(strlen($_POST['reg_firstName'])<3 || strlen($_POST['reg_firstName'])>20 )
    {
        $valid_firstname = false;
    }

    if(strlen($_POST['reg_lastName'])<3 || strlen($_POST['reg_lastName'])>20)
    {
        $valid_lastname = false;
    }


    if(strlen($_POST['reg_password'])<6 )
    {
        $valid_password = false;
    }

    if(strlen($_POST['reg_email'])==0 || strlen($_POST['reg_email'])>30)
    {
        $valid_email = false;
        $email_err = 'Email field is empty.';
    }


    
    if($valid_firstname && $valid_lastname && $valid_password && $valid_email)
    {    
        $reg_stmt_check = $db->prepare
        ("SELECT email
        FROM memb 
        WHERE email = ?");

        $reg_stmt_check->execute(array($reg_email));
        $count = $reg_stmt_check->rowCount();

        if($count > 0)
        {
            $valid_email = false;
        }
        else 
        {
            $name = $_POST['reg_firstName'];
            $surname = $_POST['reg_lastName'];
            $pass =  sha1($_POST['reg_password']);
            $email = $_POST['reg_email'];
            $groupe = $_POST['reg_groupe'];
            if($_POST['reg_groupe'] == 0)
            {
                $type = 4;
            }else
            {
                $type = 3;
            }

            $reg_stmt = $db->prepare('
            INSERT INTO memb(name,surname,email,pass,type,groupe) 
            VALUES(?,?,?,?,?,?)');

            $reg_stmt->execute(array($name,$surname,$email,$pass,$type,$groupe));
            alert('registered successfully');
            header('Location: main.php');
        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
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
    
    <title>Registre</title>
    <link rel="icon" href="res/unimail_icon.png"> 

    
    
</head>
<body style="margin-bottom: 50px;">
<div class="container">
  <div class="row">


  
        <div class="col-md-12 col-lg-6 order-lg-2" id="half">
                <div class="card shadow-sm">
                    <div class="card-header  shadow-sm ps-md-5 px-md-4" id= "cardheader" style="background-color: white; font-weight : 700; font-size: large;    padding-top: 5%; padding-bottom: 5%; ">     
                        <div class="card-heading text-primary">Registre</div>
                        <p><span class ="text-muted" style="font-size: small;">Note : using real name is obligatory ! Preferably use a real photo.</a></span></p>
                    </div>

                    <div class="card-body p-md-5">

                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">First name</label>
                                <input type="text" class="form-control <?php isinvalid($valid_firstname)?>" id="exampleInputTextl1" aria-describedby="textlHelp" name="reg_firstName">
                                <div class="invalid-feedback">
                                    First name must be at least 3 characters.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Last name</label>
                                <input type="text" class="form-control <?php isinvalid($valid_lastname)?>" id="exampleInputTextl1" aria-describedby="textlHelp" name="reg_lastName">
                                <div class="invalid-feedback">
                                    last name must be at least 3 characters.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label ">Email address</label>
                                <input type="email" class="form-control <?php isinvalid($valid_email)?>" id="reg_email_id" aria-describedby="emailHelp" name = "reg_email">
                                <div class="invalid-feedback">
                                    <?php
                                    echo $email_err;
                                    ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label ">Password</label>
                                <input type="password" class="form-control <?php isinvalid($valid_password)?>" id="reg_password_id" name="reg_password">
                                <div class="invalid-feedback">
                                    assword must be at least 6 characters.
                                </div>
                            </div>

                            <!-- photo upload -->

                        

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col">
                                    
                                    </div>

                                    <div class="col">
                                    <div class="input-group">
                                            <label class="input-group-text" for="inputGroupSelect01">Groupe</label>
                                            <select class="form-select" id="select_groupe" name="reg_groupe">

                                                <option value="0">Administration</option>
                                                <option value="1">G1</option>
                                                <option value="2">G2</option>
                                                <option value="3">G3</option>
                                                <option value="4">G4</option>
                                                <option value="5">G5</option>
                                                <option value="6">G6</option>
                                                <option value="7">G7</option>
                                                <option value="8">G8</option>
                                                <option value="9">G9</option>
                                                <option value="10">G10</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Registre</button>
                            </div>
                            
                        </form>
                    </div>
                    <div class="container ps-md-5">
                            <p><span class ="text-muted" style="font-size: small;">Allready have an account? </span> <span style="font-size: small;"><a href="login.php">Login.</a></span></p>
                        </div>
                </div>
        </div>

        <div class="col-md-12 col-lg-6 text-center p-5 order-lg-1" id="half">
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
