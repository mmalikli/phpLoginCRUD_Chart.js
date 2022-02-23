<?php
    session_start();

    //CHECKING IF USER IS LOGGED IN IF NOT REDIRECT HIM TO INDEX PAGE
    if(!isset($_SESSION['username']) && !isset($_SESSION['userid'])) {
        header('location: index.php?error=forbidenacccess');
    }

    require 'includes/connectDB.inc.php';
    require 'functions.php';
    //NOW I WILL CREATE AN TABEL WITH ALL INFO ABOUT OUR PROJECTS

    $sql = "SELECT projects.idProject, 
                users.firstname AS project_owner,projects.projectName,
                projects.projectDescription,projects.requestedFund,
                projects.projectStartDate,projects.projectEndDate,
                SUM(projects_investors.investmentFund) AS collected 
                FROM users 
                INNER JOIN projects ON users.idUser = projects.idUser 
                INNER JOIN projects_investors ON projects.idProject = projects_investors.idProject 
                GROUP BY projects.idProject";
    $statment = $connect->prepare($sql);
    $statment->execute();
    
    $results = $statment->fetchAll(PDO::FETCH_OBJ);
    //I WROTE A FUNCTION TO CHECK IF USERES ALREADY CONTRUBUTED
    //НЕТ БИНД ПАРАМ


//var_dump($_SESSION);
?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title>Projects Table</title>
    <link rel="stylesheet" href="css/table.css">
 </head>
 <body>
 	<h4>Welcome,<?php echo $_SESSION['username'] ?>!<a href="includes/logout.inc.php"> Logout?</a></h4>
    <main style="overflow-x: auto;">
        <table>
      <tr>
          <!--<th>Project ID</th>-->
          <th>Project Owner</th>
          <th>Project Name</th>
          <th>Project Description</th>
          <th>Project Start Date</th>
          <th>Project End Date</th>
          <th>Requested Fund</th>
          <th>Collected Fund</th>
          <th>Action</th>
        </tr>
        <?php foreach($results as $project) : ?>
          <tr>
            <td><?= $project->project_owner; ?></td>
            <td><?= $project->projectName; ?></td>
            <td><?= $project->projectDescription; ?></td>
            <td><?= $project->projectStartDate; ?></td>
            <td><?= $project->projectEndDate; ?></td>
            <td><?= $project->requestedFund; ?></td>
            <td><?= $project->collected; ?></td>
            <td>
                <!-- secure.db.php -->
                <?php if(isowner($_SESSION['userid'],$project->idProject)) { ?>
                    <a href="includes/review.inc.php?id=<?=$_SESSION['pid'] = $project->idProject ?>">Review</a>
                <?php } else {?>
                    <?php if (!iscontributed($_SESSION['userid'],$project->idProject)) {?>
                        <a href="includes/contribute.inc.php?id=<?=$_SESSION['pid'] = $project->idProject?>">Contribute</a>
                    <?php } ?>
                <?php } ?>
            </td>
        <?php endforeach; ?>
            </td>
          </tr>
      </table>
      <a href="includes/charts/mainchart/">View All Projects Chart</a>
    </main>
 </body>
 </html>