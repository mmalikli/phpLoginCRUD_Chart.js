<?php
    session_start();

    //CHECKING IF USER IS LOGGED IN IF NOT REDIRECT HIM TO INDEX PAGE
    if(!isset($_SESSION['username']) && !isset($_SESSION['userid'])) {
        header('location: index.php?error=forbidenacccess');
    }

    require 'includes/connectDB.inc.php';
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
    function iscontributed($userid, $projectid) {
        
        global $connect;
        $iscontributed = false;
        $sql = 'SELECT * FROM projects_investors 
                    WHERE idUser = ? AND idProject = ?';
        $statement = $connect->prepare($sql); 
        if(!$statement->execute(array($userid,$projectid))) {
            $statement->closeCursor();
            header("location: table.php?error=stmtfailediscontributed");
        }

        if($statement->rowCount() == 0){
                    $iscontributed = false;
        } else {

            $iscontributed = true;
            }
        return $iscontributed;               
    }
//TO CHECK IF USER IS OWNER
    function isowner($userid, $projectid) {
        global $connect;

        $query = 'SELECT * FROM projects
                        WHERE idUser = :uid AND idProject = :pid';
        $statement = $connect->prepare($query);
        $statement->bindValue(':uid', $userid);
        $statement->bindValue(':pid', $projectid);


        if(!$statement->execute()) {
            $statement->closeCursor();
            header("location: table.php?error=stmtfailedisowner");
        }
        if($statement->rowCount() == 0){
            $isowner = false;
        } else {
            $isowner = true;
        }

        return $isowner;
    }

//var_dump($_SESSION);
?>


<?php

    if(isowner(12,1)) {
        echo "TRUE";
    }
?>