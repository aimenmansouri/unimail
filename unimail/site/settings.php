<?php
session_start();
include 'functions.php';
include 'connect.php';

$sett_stmt_check = $db->prepare
        ("SELECT email
        FROM memb 
        WHERE id = ?");

        $sett_stmt_check->execute(array($_SESSION['S_id']));
        $row = $sett_stmt_check->fetch();
        $email = $row['email'];

        switch ($_SESSION['S_type']) {
            case 0:
                $type = 'Admin';
                break;
            case 1:
                $type = 'Administration';
                break;
            case 2:
                $type = 'Student (group administrator)';               
                break;
            case 3:
                $type = 'Student';
                break;
            case 4:
                $type = 'Administration(wating forapproval )';
                break;

            default :
            $type = 'error';
        }
if(!isset($_SESSION['S_type']))
header('Location: login.php');

        $valid_curr_password = true;
        $valid_lastname = true;
        $valid_firstname = true;
        $valid_password = true;
        $valid_email = true;
        $email_err = 'Email already exist.';

if($_SERVER['REQUEST_METHOD'] == 'POST' )
{

        $sett_stmt_check = $db->prepare
        ("SELECT pass
        FROM memb 
        WHERE id = ?");

        $sett_stmt_check->execute(array($_SESSION['S_id']));
        $row = $sett_stmt_check->fetch();

            if($row['pass'] == sha1($_POST['set_curpass']))
            {
                if(strlen($_POST['sett_name'])!=0){
                if((strlen($_POST['sett_name'])<3 || strlen($_POST['sett_name'])>20)  )
                {
                    $valid_firstname = false;
                }}

                if(strlen($_POST['sett_surname'])!=0){
                if((strlen($_POST['sett_surname'])<3 || strlen($_POST['sett_surname'])>20))
                {
                    $valid_lastname = false;
                }}

                if(strlen($_POST['sett_pass'])!=0){
                if((strlen($_POST['sett_pass'])<6 ))
                {
                    $valid_password = false;
                }}

                if(strlen($_POST['sett_email'])!=0){
                    if((strlen($_POST['sett_email'])>30))
                    {
                        echo 1;
                        $valid_email = false;
                        $email_err = 'Email can not be over 30 character.';
                    }
                    else
                    {
                        
                        $sett_stmt_check = $db->prepare
                        ("SELECT email
                        FROM memb 
                        WHERE email = ?");

                        $sett_stmt_check->execute(array($_POST['sett_email']));
                        $count = $sett_stmt_check->rowCount();

                        if($count > 0)
                        {
                            $valid_email = false;
                        }
                    }
                }

                if($valid_firstname && $valid_lastname && $valid_password && $valid_email)
                {

                    $sett_name = $_POST['sett_name'];
                    $sett_surname = $_POST['sett_surname'];
                    $sett_email = $_POST['sett_email'];
                    $sett_pass;

                    if(strlen($sett_name)>0)
                    {
                        $sett_stmt_name = $db->prepare
                        ("UPDATE memb
                        SET name = ?
                        WHERE id = ?;");
    
                        $sett_stmt_name->execute(array($sett_name ,$_SESSION['S_id']));
                        $_SESSION['S_name'] = $sett_name;
                    }

                    if(strlen($sett_surname)>0)
                    {
                        $sett_stmt_surname = $db->prepare
                        ("UPDATE memb
                        SET surname = ?
                        WHERE id = ?;");
    
                        $sett_stmt_surname->execute(array($sett_surname ,$_SESSION['S_id']));

                        $_SESSION['S_surname'] = $sett_surname;
                    }

                    if(strlen($sett_email)>0)
                    {
                        $sett_stmt_email = $db->prepare
                        ("UPDATE memb
                        SET email = ?
                        WHERE id = ?;");
    
                        $sett_stmt_email->execute(array($sett_email,$_SESSION['S_id']));
                    }

                    if(strlen($_POST['sett_pass'])>0)
                    {
                        $sett_pass = sha1($_POST['sett_pass']);
                        $sett_stmt_pass = $db->prepare
                        ("UPDATE memb
                        SET pass = ?
                        WHERE id = ?;");
    
                        $sett_stmt_pass->execute(array( $sett_pass , $_SESSION['S_id']));
                    }

                    alert('Your personal information has been successfully updated.');
                }
       
            }

            else
            {
                $valid_curr_password = false;
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
    
    <title>Settings</title>
    <link rel="icon" href="res/unimail_icon.png"> 

</head>
<body>
<nav class="navbar fixed-top navbar-expand-lg shadow-sm " id="my-nav-bar">
  <div class="container-fluid">
  
    <a class="navbar-brand" id="my-brand" href="main.php">Uni Mail</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon " id="burger-menu">
      
      <!-- navbar berguer menu icon -->

      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
</svg>
      
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

    <li class="nav-item">
         <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="main.php">Announcements</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="discussion.php">Discussion</a>
        </li>
      </ul>


      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        

        
          <li class="nav-item dropdown ms-auto"><a class="nav-link pe-0" id="userInfo" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span style="font-weight: 550;"><?php  echoName(); echoSurname();?></span></a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userInfo">
              <div class="dropdown-header text-gray-700">
                <h6 class="text-uppercase font-weight-bold"><?php  echoName(); echoSurname();?></h6><small> <?php groupe(); ?></small>
              </div>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="myposts.php">My posts</a>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="settings.php">Settings</a>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php">Logout</a>
            </div>
          </li>
      </ul>
    </div>
  </div>
</nav>
<div class="navmarg"></div>
<?php wating_approval(); ?>

<div class="container">
  <div class="row  justify-content-center">
  <div class="col-md-12 col-lg-6" id="half">
  <div class="card">
  <ul class="list-group list-group-flush">
    <li class="list-group-item">First name : <?php echo $_SESSION['S_name'] ?></li>
    <li class="list-group-item">Last mane : <?php echo $_SESSION['S_surname'] ?></li>
    <li class="list-group-item">Email : <?php echo $email ?></li>    
    <li class="list-group-item">Groupe : <?php echo $_SESSION['S_groupe'] ?></li>
    <li class="list-group-item">Account : <?php echo $type ?></li>
    <li class="list-group-item">Account id : <?php echo $_SESSION['S_id'] ?></li>
  </ul>
</div>
  </div>
        <div class="col-md-12 col-lg-6" id="half">
                <div class="card shadow-sm">
                    <div class="card-header  shadow-sm ps-md-5 px-md-4" id= "cardheader" style="background-color: white; font-weight : 700; font-size: large;    padding-top: 5%; padding-bottom: 5%; ">     
                        <div class="card-heading text-primary">Settings</div>
                        <p><span class ="text-muted" style="font-size: small;">Note :  fill the wanted fieldes after entering your current password.</a></span></p>
                    </div>

                    <div class="card-body p-md-5 mb-5">
                    
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">

                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label" >Current password *</label>
                                <input type="password" class="form-control <?php isinvalid($valid_curr_password)?>" id="exampleInputPassword1" name="set_curpass">
                                <div class="invalid-feedback">
                                    Password incorrect.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">new first name</label>
                                <input type="text" class="form-control <?php isinvalid($valid_firstname)?>" id="exampleInputTextl1" aria-describedby="textlHelp" name="sett_name">
                                <div class="invalid-feedback">
                                    First name must be at least 3 characters.
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">new last name</label>
                                <input type="text" class="form-control <?php isinvalid($valid_lastname)?>" id="exampleInputTextl1" aria-describedby="textlHelp" name="sett_surname">
                                <div class="invalid-feedback">
                                    last name must be at least 3 characters.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">new email</label>
                                <input type="email" class="form-control <?php isinvalid($valid_email)?>" id="exampleInputTextl1" aria-describedby="textlHelp" name="sett_email">
                                <div class="invalid-feedback">
                                    <?php
                                    echo $email_err;
                                    ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">New password</label>
                                <input type="password" class="form-control <?php isinvalid($valid_password)?>" id="exampleInputPassword1" name="sett_pass">
                                <div class="invalid-feedback">
                                   password must be at least 6 characters.
                                </div>
                            </div>
                            <!-- photo upload -->
                        

                            <div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
        </div>

    
  </div>
  
</div>
    <!--script bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>