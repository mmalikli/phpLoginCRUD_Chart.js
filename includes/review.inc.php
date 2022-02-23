<?php
    //PREVENT ACCESS
    session_start();
    if( (!isset($_SESSION["username"])) || (!isset($_SESSION["userid"])))
    {
        header("Location: index.php?error=forbidenaccess");
        exit();
    }

    require 'connectDB.inc.php';
    require '../functions.php';

    $pid = filter_var(trim($_GET['id']),FILTER_SANITIZE_NUMBER_INT);
    
    if(!isowner($_SESSION['userid'],$pid)) {
        
        header("Location: ../table.php?error=noownership");
        exit();
    }

    $sql = "SELECT users.firstname,users.lastname,projects.projectName,
                projects_investors.investmentFund,projects_investors.investmentDate 
                FROM projects_investors 
                INNER JOIN users ON projects_investors.idUser = users.idUser 
                INNER JOIN projects ON projects_investors.idProject = projects.idProject 
                WHERE projects.idProject = :pid";
    //I WILL GET PROJECT ID FROM URL


    $statement = $connect->prepare($sql);
    $statement->bindValue(':pid',$pid);
    
    if(!$statement->execute()) {
        $statement->closeCursor();
        header('location: ../table.php?error=stmtfaild');
        exit();
    }

    if($statement->rowCount() == 0) {
        $statement->closeCursor();
        header('location: ../table.php?error=nothintoreview');
        exit();
    }

    $results = $statement->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Table</title>
    <link rel="stylesheet" href="../css/table.css">
</head>
<body>
    <main>
        <h5>You are logged in as <?= $_SESSION["username"]?>
        <a href="../includes/logout.inc.php">LOGOUT?</a></h5>
          <h2>Project Details</h2>
          <table class="table table-bordered">
          <tr>
              <th>User First Name</th>
              <th>User Last Name</th>
              <th>Investment Fund</th>
              <th>Investment Date</th>
            </tr>
            <?php foreach($results as $project): ?>
            <?php
            /*
                if($project->project_owner != $_SESSION['userid']) {
                    header("location: ../../table.php?error=wrongprojectowner");
                    exit();
                 }  
            */
            ?>
              <tr>
                <td><?= $project->firstname; ?></td>
                <td><?= $project->lastname; ?></td>
                <td><?= $project->investmentFund; ?></td>
                <td><?= $project->investmentDate; ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
    </main>
</body>
</html>
<?php
    include('charts/reviewchart/index.php');
?>
















