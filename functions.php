<?php
function iscontributed($userid, $projectid) {
        
        global $connect;
        $sql = 'SELECT * FROM projects_investors 
                    WHERE idUser = :uid AND idProject = :pid';
        $statement = $connect->prepare($sql);
        $statement->bindValue(':uid', $userid);
        $statement->bindValue(':pid', $projectid);

        if(!$statement->execute()) {
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

        $sql = 'SELECT * FROM projects
                        WHERE idUser = :uid AND idProject = :pid';
        $statement = $connect->prepare($sql);
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
?>