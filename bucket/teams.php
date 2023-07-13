<?php

// Scores: http://site.api.espn.com/apis/site/v2/sports/basketball/nba/scoreboard

// News: http://site.api.espn.com/apis/site/v2/sports/basketball/nba/news

// All Teams: http://site.api.espn.com/apis/site/v2/sports/basketball/nba/teams

// Specific Team: http://site.api.espn.com/apis/site/v2/sports/basketball/nba/teams/:team

// Get all teams
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "http://site.api.espn.com/apis/site/v2/sports/basketball/nba/teams",
    CURLOPT_RETURNTRANSFER => true,
    // CURLOPT_HTTPHEADER => $headers,
    // CURLOPT_HEADERFUNCTION => $header_callback
]);

$result = json_decode(curl_exec($ch));
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$teams = $result->sports[0]->leagues[0]->teams;
foreach ($teams as $info) {
	$name = $info->team->displayName;
	$logo = $info->team->logos[0]->href;

	$show = '<img src="'. $logo .'" style="width:42px;height:42px;">';
	echo '<pre>' . $show . $name . "\n";
}

// echo $status_code;
// print_r($response_headers);
// echo $response, "\n";