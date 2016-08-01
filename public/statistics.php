<?php
require __DIR__ . '/../src/Input.php';

function pageController()
{
    $teamId = Input::get('team_id');
    // Write the SELECT to retrieve the following statistics
    // - Number of Games won
    // - Number of Games lost
    // - Number of Games won as local
    // - Number of Games won as visitor
    // Use joins or sub-queries as needed...


    $sql = "SELECT 
        (
        -- selecting total games won
        SELECT count(*) 
        FROM games
        WHERE (local_team_runs > visitor_team_runs
        AND local_team_id = t.id)
        OR (local_team_runs < visitor_team_runs
        AND visitor_team_id = t.id)) AS 'Games Won',

        (
        -- selecting total games lost        
        SELECT count(*) 
        FROM games
        WHERE (local_team_runs < visitor_team_runs
        AND local_team_id = t.id)
        OR (local_team_runs > visitor_team_runs
        AND visitor_team_id = t.id)
        ) AS 'Games Lost',

        (
        -- selecting total games as local team
        SELECT count(*) 
        FROM games
        WHERE (local_team_runs > visitor_team_runs
        AND local_team_id = t.id) 
        ) AS 'Games Won as Local',

        (
        -- selecting total games won as visitor team
        SELECT count(*) 
        FROM games
        WHERE (local_team_runs < visitor_team_runs
        AND visitor_team_id = t.id)
        ) AS 'Games Won as Visitor'

        FROM teams AS t -- renaming teams, t.id now = $teamId
        WHERE id = $teamId"; 

    // Copy the generated query and verify that it retrieves the correct values
    // in SQL Pro
    var_dump($sql);

    return [
        'title' => 'Statistics Texas Rangers',
    ];
}
extract(pageController());
?>
<!DOCTYPE html>
<html>
<head>
    <?php include '../partials/head.phtml' ?>
</head>
<body>
<div class="container">
    <div class="row">
        <header class="page-header">
            <h1>Statistics</h1>
        </header>
    </div>
    <div class="row">
        <canvas id="stats-chart" width="400" height="400"></canvas>
    </div>
</div>
<?php include '../partials/scripts.phtml' ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.0/Chart.bundle.min.js">
</script>
<script>
    var ctx = $('#stats-chart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Won", "Lost", "Won as local", "Won as visitor"],
            datasets: [{
                label: 'Games',
                // These should be the values from our PHP query
                data: [12, 19, 3, 5],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
</body>
</html>