<?php

class Controller
{
    private $_f3;

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }
    
    function home()
    {
        session_destroy();
        $view = new Template();
        echo $view->render('views/order-base.html');
    }
    
    function order() {

        global $validator;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first = $_POST['first'];
        $last = $_POST['last'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $premium = $_POST['premium'];


        if(!$validator->validFirst($first)) {
                $this->_f3->set("errors['first']", "Please enter a first name");
            }
        if(!$validator->validLast($last)) {
                $this->_f3->set("errors['last']", "Please enter a last name");
                }
        if(!$validator->validAge($age)) {
                $this->_f3->set("errors['age']", "Please enter a numeric between 118 and 18");
                }
        if(!$validator->validGender($gender)) {
                $this->_f3->set("errors['gender']", "Please select a gender");
                }
        if(!$validator->validPhone($phone)) {
                $this->_f3->set("errors['phone']", "Please enter a properly formatted number");
            }

                  if (empty($this->_f3->get('errors'))) {

                      //If user signs up for premium
                      if($premium == "on") {
                          $member = new PremiumUser($first, $last, $age, $gender, $phone);

                          $_SESSION['$member'] =
                              serialize($member);

                          //If does not user signs up for premium
                      } else {

                          $member = new User($first, $last, $age, $gender, $phone);

                          $_SESSION['$member'] =
                              serialize($member);
                      }

                      $this->_f3->reroute('/order2');
                  }
        }
        //Sticky
        $this->_f3->set('first', isset($first) ? $first : "");
        $this->_f3->set('last', isset($last) ? $last  : "");
        $this->_f3->set('age', isset($age) ? $age : "");
        $this->_f3->set('gender', isset($gender) ? $gender : "");
        $this->_f3->set('phone', isset($phone) ? $phone : "");
        $this->_f3->set('premium', isset($premium) ? $premium : "");

        $view = new Template();
        echo $view->render('views/order-form.html');
    }
    function order2()
    {

        global $validator;
//        $dataLayer = new DataLayer();
        $member = unserialize($_SESSION['$member']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $state = $_POST['state'];
            $seeking = $_POST['seeking'];
            $_SESSION['seeking'] = $seeking;
            $biography = $_POST['biography'];

            if (!$validator->validEmail($email)) {
                $this->_f3->set('errors["email"]', "Email must be valid format and non empty");
            }


        if (empty($this->_f3->get('errors'))) {
            $member->setEmail($email);
            $member->setState($state);
            $member->setSeeking($seeking);
            $member->setBiography($biography);

            $_SESSION['$member'] = serialize($member);
            if ($member->isMember()) {
                $this->_f3->reroute('/order3');
            } else {
                $this->_f3->reroute('/summary');
            }
        }
        }

        //Sticky data
        $this->_f3->set('email', isset($email) ? $email : "");
        $this->_f3->set('state', isset($state) ? $state : "");
//        $this->_f3->set('seeking', isset($seeking) ? $seeking : "");
        $this->_f3->set('biography', isset($biography) ? $biography : "");
//        $this->_f3->set('seeking', array($dataLayer->getSeeking()));
        $view = new Template();
        echo $view->render('views/order-form2.html');
    }

    function order3()
    {
        global $validator;
        $member = unserialize($_SESSION['$member']);

        //If user submits data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['outdoor'])){
            $outdoor = $_POST['outdoor'];
            if(!$validator->validOutdoor($outdoor)) {
                $this->_f3->set('errors["outdoor"]',"Outdoor interests must be within the defined list");
            }
            }

            if (isset($_POST['indoor'])){
                $indoor = $_POST['indoor'];
                if(!$validator->validIndoor($indoor)) {
                    $this->_f3->set('errors["indoor"]',"Indoor interests must be within the defined list");
                }
            }
            if (empty($this->_f3->get('errors'))) {
                if (isset($indoor)) {
                    $indoorString = implode(", ", $indoor);
                    $member->setIndoor($indoorString);
                }

                //Set outdoor activities
                if (isset($outdoor)) {
                    $outdoorString = implode(", ", $outdoor);
                    $member->setOutdoor($outdoorString);
                }

                //Store object in session
                $_SESSION['$member'] = serialize($member);

                $this->_f3->reroute('/summary');
            }

            }
        $view = new Template();
        echo $view->render('views/order-form3.html');
}
    function summary() {
        
        $member = unserialize($_SESSION['$member']);
        $this->_f3->set('seeking', $member->getSeeking());
        $this->_f3->set('first', $member->getFirst());
        $this->_f3->set('last', $member->getLast());
        $this->_f3->set('age' , $member->getAge());
        $this->_f3->set('gender', $member->getGender());
        $this->_f3->set('phone', $member->getPhone());
        $this->_f3->set('email', $member->getEmail());
        $this->_f3->set('state', $member->getState());
        $this->_f3->set('biography', $member->getBiography());
        if (!$member->isMember()){
            $this->_f3->set('indoor', "");
            $this->_f3->set('outdoor', "");
        }
        if ($member->isMember()){
            $this->_f3->set('indoor', array($member->getIndoor()));
            $this->_f3->set('outdoor', array($member->getOutdoor()));
        }

        $view = new Template();
        echo $view->render('views/summary.html');
    }
}