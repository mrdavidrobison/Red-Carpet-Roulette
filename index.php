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
  $db_user = 'brainsto_rcroul';
  $db_pass = 'XpK9zWNvi%3K';
  $db_name = 'brainsto_rcroulette';

  /////////////////////////
  // Database Connection //
  /////////////////////////

  $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $contact = $_POST;
  $output = print_r($contact, true);
  file_put_contents($log_file, $output);
