<html>
    <head>
        <style type="text/css">
            #warning
            {
                color: red;
            }
            #succeded
            {
                color:chartreuse;
            }
            
            #form 
            {
                text-align: center;
                margin: 30px auto auto auto;
                padding: 20px;
                border: 2px;
                border-style: ridge;
                border-radius: 10px;
                background-color:bisque;
            }
            
            #page 
            {
                max-width: 400px;
                height: auto;
                margin: 80px auto auto auto;
                padding: 20px;
                border: 2px;
                border-style: ridge;
                border-radius: 10px;
                background-color:bisque;
            }
            
             h1, h2, h3 {
                font-weight: normal;
                color:dimgray;
                margin: 0px; 
            }
            h1 {
                text-align: center;
                font-family: Georgia, Times, serif;
                font-size: 225%;
                text-shadow: 2px 2px 3px #666666;
                padding-bottom: 10px;}
            p
            {
                font-family: Georgia, Times, sans-serif;
                text-shadow: 2px 2px 3px #666666;
                
            }
            
            #id {
                padding-left: 50px;
                
            }
            a {
                text-decoration: none;
                font-weight: bold;
                color:darkslateblue;
            }
        
        </style>
        <title>Student Database FIIT Log in </title>
    </head>
    <body>
        
        <div id="background"> 
             
        <div id="page">
            
            <h1>Student Database FIIT</h1>
            
            <div id="form">
                
                <form action="login.php" method="post">
                    <a href="register.php">  Register Student</a><br/>
                    <a href="registerteacher.php">  Register Teacher</a>
                    <p id="id">
                        ID:
                    <input type="text" name="username" id="name" /><span></span>
                    </p>
                    <p>
                        Password:
                    <input type="password" name="password" id="pass"/><span></span>
                    </p>
                    <p><input type="radio" name="usertype" value="student" checked>Student
                    <input type="radio" name="usertype" value="teacher">Teacher
                        <input type="radio" name="usertype" value="admin">Admin
                    </p>
                    <p><input type="submit" name="login" value="Log in"/> 
                    </p>
                </form>
                
            
            
            </div>


<?php 
           
        
if(isset($_POST['login'])){
    
    $data_missing = array();
    
    if(empty($_POST['username'])){

        // Adds name to array
        $data_missing[] = 'Username';

    } else {

        // Trim white space from the name and store the name
        $username = strtolower(trim($_POST['username']));

    }

    if(empty($_POST['password'])){

        // Adds name to array
        $data_missing[] = 'Password';

    } else{

        // Trim white space from the name and store the name
        $password = trim($_POST['password']);

    }

    
    if(empty($data_missing)){
        
        require_once('C:\Server\data\mysqli-connect.php');
        $selected_radio=$_POST['usertype'];
        if($selected_radio=='teacher')
        {
    
    
            $query="SELECT * FROM teacher 
            WHERE username=?";
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            $affected_rows = mysqli_stmt_affected_rows($stmt);
        
      
            if($affected_rows != 0)
            {
                 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    $dbusername=$row['username'];
                    $dbpassword=$row['password'];
                    $dbteacher_id=$row['teacher_id'];
                }
            
                if($username==$dbusername && $password==$dbpassword)
                {
                    session_start();
                    $_SESSION["username"]=$dbusername;
                    $_SESSION["teacher_id"]=$dbteacher_id;
                    $query2="select lesson_id from lessons, classes, teacher 
                    where teacher.teacher_id=classes.teacher_id
                    and classes.class_id=lessons.class_id
                    and teacher.teacher_id='$dbteacher_id'";
                    $response = @mysqli_query($dbc, $query2);
                    if($response)
                    {
                        $row2=mysqli_fetch_array($response);
                        $_SESSION["lesson_id"]=$row2['lesson_id'];
                    }
                    else{
                        $_SESSION["lesson_id"]='error';
                    }
                    //echo '<p id="succeded">You are logged in! <a href="index.php"> Click Here</a> to enter Member Page!</p>';
                    header("Location: teacher/index.php");
                
                } else
                {
                   echo '<p id="warning">Incorrect Password</p>';
                }
            
            
                mysqli_stmt_close($stmt);
            
                mysqli_close($dbc);
            } 
            else {
                
                echo '<p id="warning">The username or password is wrong!</p>';
            
                mysqli_stmt_close($stmt);
            
                mysqli_close($dbc);
             
                }
        }
        
        else if($selected_radio=='student')
        
        {
            $query="SELECT * FROM students 
            WHERE username=?";
            $stmt = mysqli_prepare($dbc, $query);
        
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            $affected_rows = mysqli_stmt_affected_rows($stmt);
        
      
            if($affected_rows != 0)
            {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    $dbusername=$row['username'];
                    $dbpassword=$row['password'];
                    $dbstudent_id=$row['student_id'];
                }
            
                if($username==$dbusername && $password==$dbpassword)
                {
                    session_start();
                    $_SESSION["username"]=$dbusername;
                    $_SESSION["student_id"]=$dbstudent_id;
                    //echo '<p id="succeded">You are logged in! <a href="index.php"> Click Here</a> to enter Member Page!</p>';
                    header("Location: student/index.php");
                
                } else {
                    echo '<p id="warning">Incorrect Password</p>';
                }
            
            
                mysqli_stmt_close($stmt);
            
                mysqli_close($dbc);
            } 
            else {
                
            
                echo '<p id="warning">The username or password is wrong!</p>';
            
                mysqli_stmt_close($stmt);
            
                mysqli_close($dbc);
            
        }
        }
        else if($selected_radio=='admin')
        {
            $query="SELECT * FROM admin
            WHERE username=?";
            $stmt = mysqli_prepare($dbc, $query);
        
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            $affected_rows = mysqli_stmt_affected_rows($stmt);
        
      
            if($affected_rows != 0)
            {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    $dbusername=$row['username'];
                    $dbpassword=$row['password'];
                }
            
                if($username==$dbusername && $password==$dbpassword)
                {
                    session_start();
                    $_SESSION["username"]=$dbusername;
                    //echo '<p id="succeded">You are logged in! <a href="index.php"> Click Here</a> to enter Member Page!</p>';
                    header("Location: admin/index.php");
                
                } else {
                    echo '<p id="warning">Incorrect Password</p>';
                }
            
            
                mysqli_stmt_close($stmt);
            
                mysqli_close($dbc);
            } 
            else {
                
            
                echo '<p id="warning">The username or password is wrong!</p>';
            
                mysqli_stmt_close($stmt);
            
                mysqli_close($dbc);
            
        }
        }
            
        
    } 
        
}
    

?>

        
        <p id="warning">
            <?php
            if(!empty($data_missing)) {
        
        echo 'Enter the following data data<br />';
        
        foreach($data_missing as $missing){
            
            echo "$missing<br />";
            
        }
            }
                ?>
            </p>
        
        
        </div>
        </div>
        <script src="login_validation.js">
        </script>
    </body>
</html>