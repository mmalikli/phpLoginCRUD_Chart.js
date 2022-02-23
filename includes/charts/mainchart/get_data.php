<?php

    require '../../connectDB.inc.php';

    $sql = "SELECT projects.idProject, projects.projectName, 
                SUM(projects_investors.investmentFund) as total_amount, 
                projects.requestedFund 
                FROM projects_investors 
                INNER JOIN projects ON projects_investors.idProject = projects.idProject 
                GROUP BY projects_investors.idProject";

    $get_data = $connect->prepare($sql);

    $get_data->execute();

    if($get_data->rowCount() > 0) {
        while($project = $get_data->fetch(PDO::FETCH_OBJ)) {
            $name =  $project->projectName;
            $requested =  $project->requestedFund;
            $collected =  $project->total_amount;

            $result_array[] = ['name'=>$name, 'requested'=>$requested, 'collected'=>$collected];
        }
        echo json_encode($result_array);
        die();
    }