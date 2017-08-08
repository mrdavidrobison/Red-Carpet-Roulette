<?php

// http://sudscreative.com/promotions/rc_roulette_free.php?email=%EMAIL%&name=%FIRSTNAME%

// AC API Key
// 78a71f617cbc3d182215d8c39ed0ad6dc73e58e0bdf1e6f40267127736a9c436f9d1f946

// AC API Url
// https://redcarpet.api-us1.com

// AC API Example
// http://www.activecampaign.com/api/example.php?call=contact_tag_add

// AC Trigger Object Being POSTED by the AC API -- located via $_POST
// Array
// (
//     [contact] => Array
//         (
//             [id] => 3166
//             [email] => joey@stormyourmarket.com
//             [first_name] => Joey
//             [last_name] =>
//             [phone] =>
//             [orgname] =>
//             [tags] => Promotion_2017_Roulette
//             [ip4] => 127.0.0.1
//         )
//
//     [seriesid] => 4
// )

//////////////////////////
// Active Campaign Tags //
//////////////////////////

// Promotion_Roulette_2017_Prize_50Off  --- 30% of contacts will get this tag
// Promotion_Roulette_2017_Prize_25Off  --- 65% of contacts will get this tag
// Promotion_Roulette_2017_Prize_Free   --- 5% of contacts will get this tag

$log_file = 'log.txt';

//////////////////////////
// Database Information //
//////////////////////////

$db_host = 'localhost'; // 37.60.247.101
$db_user = 'brainsto_rcpromo';
$db_pass = 'Djfnv7hFkglnsmnF23';
$db_name = 'brainsto_rcpromos';

/////////////////////////
// Database Connection //
/////////////////////////

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$availableTags = [];

$sqlFree = 'SELECT count(id) as remaining_free FROM brainsto_rcpromos.roulettecoupons_free WHERE assigned = 0';
$resultFree = mysqli_query($conn, $sqlFree);
$row = mysqli_fetch_assoc($resultFree);
$countFree = $row['remaining_free'];

$sql50Off = 'SELECT count(id) as remaining_50Off FROM brainsto_rcpromos.roulettecoupons_50off WHERE assigned = 0';
$result50Off = mysqli_query($conn, $sql50Off);
$row = mysqli_fetch_assoc($result50Off);
$count50Off = $row['remaining_50Off'];

$sql25Off = 'SELECT count(id) as remaining_25Off FROM brainsto_rcpromos.roulettecoupons_25off WHERE assigned = 0';
$result25Off = mysqli_query($conn, $sql25Off);
$row = mysqli_fetch_assoc($result25Off);
$count25Off = $row['remaining_25Off'];

mysqli_close($conn);

if ($countFree > 0) {
    array_push($availableTags, 'Promotion_Roulette_2017_Prize_Free');
}
if ($count25Off > 0) {
    $num25Off = 13;
    for ($i=0; $i < $num25Off; $i++) {
        array_push($availableTags, 'Promotion_Roulette_2017_Prize_25Off');
    }
}
if ($count50Off > 0) {
    $num50Off = 6;
    for ($i=0; $i < $num50Off; $i++) {
        array_push($availableTags, 'Promotion_Roulette_2017_Prize_50Off');
    }
}

shuffle($availableTags);

$contact = $_POST['contact'];
// $output = print_r($contact, true);
// file_put_contents($log_file, $output);

$email = $contact['email'];

$tagSize = sizeof($availableTags);
$randomNumber = rand(0, ($tagSize - 1));
$winnerTag = $availableTags[$randomNumber];

$params = array(
    'api_key'      => '78a71f617cbc3d182215d8c39ed0ad6dc73e58e0bdf1e6f40267127736a9c436f9d1f946',
    'api_action'   => 'contact_tag_add',
    'api_output'   => 'serialize'
);

$post = array(
    'email' => $email,
    'tags' => $winnerTag
);

$query = "";
foreach ($params as $key => $value) {
    $query .= urlencode($key) . '=' . urlencode($value) . '&';
}
$query = rtrim($query, '& ');

$data = "";
foreach ($post as $key => $value) {
    $data .= urlencode($key) . '=' . urlencode($value) . '&';
}
$data = rtrim($data, '& ');

$url = 'https://redcarpet.api-us1.com';
$url = rtrim($url, '/ ');

if (!function_exists('curl_init')) {
    file_put_contents($log_file, 'There was a problem with CURL');
    die();
}

$api = $url . '/admin/api.php?' . $query;

$request = curl_init($api);
curl_setopt($request, CURLOPT_HEADER, 0);
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($request, CURLOPT_POSTFIELDS, $data);
curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

$response = (string)curl_exec($request);

curl_close($request);

if (!$response) {
    file_put_contents($log_file, 'There was a problem with CURL');
    die();
}

$result = unserialize($response);

if ($result['result_code'] == 'SUCCESS') {
    file_put_contents($log_file, 'Worked: '.$winnerTag);
    die();
} else {
    file_put_contents($log_file, 'There was a problem with Active Campaign API');
    die();
}
