<?php
require __DIR__ . '/../src/Input.php';

function pageController()
{
    $teams = [];
    if (Input::has('league')) {
        $league = Input::get('league');
        // Filter teams based on league, only select the team's identifier
        // and its name
        $selectTeams = "SELECT id, name FROM teams WHERE league = '$league'";
        // Try your query in Sequel Pro
        var_dump($selectTeams);
        // Use the values from your query to populate your form
        // $teams = ...
    }
    // The player's identifier should be in the query string
    $playerId = Input::get('player_id'); 
    $sql = "SELECT * FROM players JOIN teams ON players.team_id = teams.id WHERE players.id = $playerId";
    var_dump($sql);

    return [
        'title' => 'Chris Young',
        'teams' => $teams,
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
                <div class="page-header"><h1>Chris Young</h1></div>
                <?php include '../partials/player-form.phtml' ?>
            </div>
        </div>
        <?php include '../partials/scripts.phtml' ?>
    </body>
</html>
