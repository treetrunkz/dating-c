<?php

//Start a session
session_start();

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Required file
require_once('vendor/autoload.php');
require_once('model/validate.php');
require_once('model/data-layer.php');

//Instantiate Fat-Free
$f3 = Base::instance();

//Turn on Fat-Free error reporting
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

    $view = new Template();
    echo $view->render('views/order-base.html');

});
/*
 * first order hive and validation & form submit
 */
//Define an order route
$f3->route('GET|POST /order', function($f3) {

    //If form has been submitted, validate
    if(!empty($_POST)) {

        //Get data from form
        $first = $_POST['first'];
        $last = $_POST['last'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];

        $cond = $_POST['cond'];

        //Add data to hive
        $f3->set('first', $first);
        $f3->set('last', $last);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);


        //If data is valid
        if (validForm()) {

            //Write data to Session
            $_SESSION['first'] = $first;
            $_SESSION['last'] = $last;
            $_SESSION['gender'] = $gender;
            $_SESSION['age'] = $age;
            $_SESSION['phone'] = $phone;

            if (empty($cond)) {
                $_SESSION['cond'] = "No condiments selected";
            }
            else {
                $_SESSION['cond'] = implode(', ', $cond);
            }

            //Redirect to Summary
            $f3->reroute('order2');
        }
    }

    //Display order form
    $view = new Template();
    echo $view->render('views/order-form.html');
});
/*
 *  Get|POST for order 2, email seeking state and bio
 */
$f3->route('GET|POST /order2', function() use ($f3) {


    if(!empty($_POST)) {

            $email = $_POST['email'];
            $state = $_POST['state'];
            $seeking = $_POST['seeking'];
            $biography = $_POST['biography'];

            $f3->set('email', $email);
            $f3->set('state', $state);
            $f3->set('seeking', $seeking);
            $f3->set('biography', $biography);

            if (validForm2()) {

                $_SESSION['email'] = $email;
                $_SESSION['state'] = $state;
                $_SESSION['seeking'] = $seeking;
                $_SESSION['biography'] = $biography;

                $f3->reroute('order3');
            }
        }
    
    $view = new Template();
    echo $view->render('views/order-form2.html');

});

/*
 * setting variables and validation + population GET|POST for order3
 */
$f3->route('GET|POST /order3', function($f3) {
    if(!empty($_POST)) {


        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (validForm3()) {

            if (isset($_POST['outdoor'])) {
                $_SESSION['outdoor'] = implode(", ", $_POST['outdoor']);
                $out = $_SESSION['outdoor'];
            }
            if (isset($_POST['indoor'])) {
                $_SESSION['indoor'] = implode(", ", $_POST['indoor']);
                $in = $_SESSION['indoor'];
            }

            $_SESSION['test1'] = $in;
            $_SESSION['test2'] = $out;

            $f3->set('outdoor2', $in);
            $f3->set('indoor2', $out);
            $f3->reroute('summary');
        }
        }
    }
    $view = new Template();
    echo $view->render('views/order-form3.html');

});

//Define a summary route this holds all of the variables for 'creating' the profile page
$f3->route('GET /summary', function($f3) {


    //Display summary
    $view = new Template();
    echo $view->render('views/summary.html');
});

//Run Fat-Free
$f3->run();