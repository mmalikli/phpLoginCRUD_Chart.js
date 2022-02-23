<?php
    //PREVENT ACCESS
    session_start();
    if( (!isset($_SESSION["username"])) || (!isset($_SESSION["userid"])))
    {
        header("Location: ../index.php?error=forbidenaccess");
        exit();
    }

    $userdate = filter_var(trim($_POST['userdate']),FILTER_SANITIZE_STRING);
    $usercontribute = filter_var(trim($_POST['contribute']),FILTER_SANITIZE_NUMBER_FLOAT);

    require 'connectDB.inc.php';
    require '../functions.php';

    //CHECKING AGAIN IF USER CONTRIBUTED TO COMPLETLY AVOID ANY MISSTRAES
    if(iscontributed($_SESSION['userid'],$_SESSION['pid'])) {
        header("location: ../table.php?error=contributed");
        exit();
    }

    //I MADE SPEPARATE IF's BECAUSE FOR ME IT IS MORE READIBLE
    //CHECKING AGAIN TO KEEP OUR DATA HANDLED
    if($usercontribute > ($_SESSION['requested'] - $_SESSION['collected'])) {
       header("location: ../table.php?error=tomuchofcontribute");
       exit(); 
    }
    //CHECKING THE DATES
    /*
    $udate = strtotime($userdate);
    $penddate = strtotime($_SESSION['end_date']);
    $pstartdate = strtotime($_SESSION['start_date']);
    */

    if( ($userdate < $_SESSION['start_date']) || ($userdate > $_SESSION['end_date'])) {
        
        header("location: ../test.php?error=invalidDate");
       //exit(); 
    }

    $sql = "INSERT INTO 
                projects_investors(idUser,idProject,investmentFund,investmentDate) 
                VALUES (:uid, :pid, :invest, :investDate)";
    $statement = $connect->prepare($sql);

    $statement->bindValue(':uid', $_SESSION['userid']);
    $statement->bindValue(':pid', $_SESSION['pid']);
    $statement->bindValue(':invest', $usercontribute);
    $statement->bindValue(':investDate', $userdate);
    


    if(!$statement->execute()) {
        $statement->closeCursor();
        header("Location: ../test.php?error=stmtfailed");
        exit();
    }

    $statement->closeCursor();

    header("location: ../table.php?contributionsuccess");




















