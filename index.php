<?php


//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Required file
require_once('vendor/autoload.php');


//Instantiate Fat-Free
$f3 = Base::instance();
$validator = new Validate();
$dataLayer = new DataLayer();
$controller = new Controller($f3);
//Turn on Fat-Free error reporting

session_start();

$f3->set('DEBUG', 3);

//Define arrays
$f3->set('outdoor', array("Disc Golf", "Running", "Star Gazing", "Snow Sledding", "Shooting", "Cooking", "SnowBoarding", "Skiing"));
$f3->set('indoor', array("Drawing", "TV", "Movies", "Acting", "Dancing", "Guitar", "Flute", "Piano", "Break Dancing", "Yoga"));
$f3->set('state', array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA',
    'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA',
    'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND',
    'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT',
    'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY'));
$f3->set('seeking', array('Male', 'Female', 'Trans-Fem', 'Trans-Masc', 'Non-Binary'));
$f3->set('sexual', array('Male', 'Female', 'Trans-Fem', 'Trans-Masc', 'Non-Binary'));
$f3->set('genders', array('Male', 'Female', 'Trans-Fem', 'Trans-Masc', 'Non-Binary'));
$f3->set('condiments', array('ketchup', 'mayonnaise', 'mustard'));

//Define a default route == home page
$f3->route('GET /', function() {

    global $controller;
    $controller->home();

});
/*
 * first order hive and validation & form submit
 */
//Define an order route
$f3->route('GET|POST /order', function($f3) {

    //If form has been submitted, validate
    global $controller;
    $controller->order();

});
/*
 *  Get|POST for order 2, email seeking state and bio
 */
$f3->route('GET|POST /order2', function() use ($f3) {


    //If form has been submitted, validate
    global $controller;
    $controller->order2();

});

/*
 * setting variables and validation + population GET|POST for order3
 */
$f3->route('GET|POST /order3', function($f3) {

    //If form has been submitted, validate
    global $controller;
    $controller->order3();

});

//Define a summary route this holds all of the variables for 'creating' the profile page
$f3->route('GET /summary', function($f3) {

    //Display summary
    global $controller;
    $controller->summary();
    
});

//Run Fat-Free
$f3->run();