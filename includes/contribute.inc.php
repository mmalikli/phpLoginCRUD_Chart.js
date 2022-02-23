<?php
    //PREVENT ACCESS
    session_start();
    if( (!isset($_SESSION["username"])) || (!isset($_SESSION["userid"])))
    {
        header("Location: ../index.php?error=forbidenaccess");
        exit();
    }

    require 'connectDB.inc.php';
    require '../functions.php';

    $pid = filter_var(trim($_GET['id']),FILTER_SANITIZE_NUMBER_INT);
    
    if(iscontributed($_SESSION['userid'],$pid)) {
        
        header("Location: ../table.php?error=alreadycontributed");
        exit();
    }
    //ПОЛУЧИТЬ ДАННЫЕ О ПРОЕКТЕ
    $sql = "SELECT projects.idProject,projects.projectName,
                projects.requestedFund,projects.projectStartDate,
                projects.projectEndDate,
                SUM(projects_investors.investmentFund) as collected 
                FROM users 
                INNER JOIN projects ON users.idUser = projects.idUser 
                INNER JOIN projects_investors ON projects.idProject = projects_investors.idProject GROUP BY projects.idProject
                HAVING idProject = ?";
    $statement = $connect->prepare($sql);
    $statement->bindParam(1,$pid,PDO::PARAM_INT);
    if(!$statement->execute()) {
        $statement->closeCursor();
        header("Location: ../table.php?error=stmtfailed");
        exit();
    }
    if($statement->rowCount() == 0) {
        $statement->closeCursor();
        header("Location: ../table.php?error=noavalablecontributions");
        exit();
    }

    $projectData = $statement->fetch(PDO::FETCH_OBJ);

    $_SESSION['start_date'] = $projectData->projectStartDate;
    $_SESSION['end_date'] = $projectData->projectEndDate;
    $_SESSION['requested'] = $projectData->requestedFund;
    $_SESSION['collected'] = $projectData->collected;
    $_SESSION['project_name'] = $projectData->projectName;
    $_SESSION['pid'] = $pid;

    var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contribution</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h5>You are logged in as <?=$_SESSION["username"]?><a href="logout.inc.php"> LOGOUT?</a></h5>
    <div class="container">
    <div class="card mt-5">
        <div class="card-header">
            <h2><?= $_SESSION['project_name']?> Project Contribute</h2>
            <h4>Project End Date is  <?= $_SESSION['end_date'] ?> </h4>
            <h5>Requested Amount is <?= $_SESSION['requested'] ?></h5> 
            <h5>Total Collected Amount is <?= $_SESSION['collected'] ?></h5> 
        </div>
        <div class="card-body">
            <form action="checkContribute.php" method="post">
                <input type="number" name="contribute" max="<?=$_SESSION['requested']-$_SESSION['collected']?>" min = "1"  step="0.01">
                <input type="date" name="userdate">
                <br>
                <button type="submit" name="submit">Contribute</button>
            </form>
            <?=$_SESSION['pid']?>
        </div>
    </div>
    </div>
</body>
</html>