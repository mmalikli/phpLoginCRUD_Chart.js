<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Data</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
</script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js">
</script>

</head>
<body>
    <table class="table table-bordered table-striped table-striped">
        <tr>
            <h1><strong>Bar Chart</strong></h1>
            <td><canvas id="bar_canvas" style="overflow-x: auto;"></canvas><br>
            </td>
        </tr>
    </table>

    <script type="text/javascript">

        //Global Options
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 12;
        Chart.defaults.global.defaultFontColor = '#000';

        $(document).ready(function(){
            $.ajax({

                url: "http://localhost/myphp/lastfinal/MuradProject/includes/charts/reviewchart/get_data.php",
                method: "GET",
                success: function(data) {
                    var data = JSON.parse(data);
                    
                    var name = [];
                    var requested = [];
                    var collected = [];

                    for(var i in data) {
                        name.push(data[i].name);
                        requested.push(data[i].requested);
                        collected.push(data[i].collected);
                    }

                    var chardata = {
                        labels: name,
                        datasets: [
                        {
                            label:"Requested",
                            backgroundColor: 'rgba(255, 50, 50, 0.5)',

                            borderWidth:1,
                            borderColor: '#777',
                            hoverBackgroundColor: 'rgba(255, 50, 50, 1)',
                            hoverBorderColor: "#000",
                            //maxBarThickness: 100,
                            //borderWidth: 5,
                            data: requested
                        },
                        { 
                            label:"Collected",
                            backgroundColor: 'rgba(255, 255, 50, 0.75)',

                            borderWidth:1,
                            borderColor: '#777',
                            //borderColor: 'rgba(200, 200, 200, 0.75)',
                            hoverBackgroundColor: 'rgba(255, 255, 50, 0.75)',
                            hoverBorderColor: "#000",
                            //hoverBorderColor: 'rgba(200, 200, 200, 1)',
                            data: collected,
                            //barThickness: 3
                        }]
                    };

                    var bar_canvas = $("#bar_canvas");
                    var barGraph = new Chart(bar_canvas,{
                        type: 'horizontalBar',
                        //type: 'bar',
                        data: chardata,
                        options: {
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    },
                                    barThickness : 73
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    },
                                    barThickness: 60,
                                }]
                            }                
                        }
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
    <script>
        
    </script>
</body>
</html>