<?php

/* Validate the form
 * @return boolean
 */
function validForm()
{
    global $f3;
    $isValid = true;

    if (!validFirst($f3->get('first'))) {

        $isValid = false;
        $f3->set("errors['first']", "Please enter a first name");
    }

    if (!validLast($f3->get('last'))) {

        $isValid = false;
        $f3->set("errors['last']", "Please enter a last name");
    }

    if (!validAge($f3->get('age'))) {

        $isValid = true;
        $f3->set("errors['age']", "Please enter a numeric between 118 and 18");
    }

    if (!validGender($f3->get('gender'))) {

        $isValid = false;
        $f3->set("errors['gender']", "Please select a gender");
    }

    if (!validPhone($f3->get('phone'))) {
        $isValid = false;
        $f3->set("errors['phone']", "Please enter a properly formatted number");
    }

    return $isValid;
}

/* Validate the form
 * @return boolean
 */
function validForm2() {

    global $f3;
    $isValid = true;

    if (!validEmail($f3->get('email'))){
        $isValid = false;
        $f3->set("errors['email']", "Email must be valid and not be empty");
    }

    if (!validState($f3->get('state'))){
        $isValid = false;
        $f3->set("errors['state']", "State must be chosen within the list");
    }

    if (!validSeeking($f3->get('seeking'))){
        $isValid = false;
        $f3->set("errors['seeking']", "Seeking must be within the list");
    }

    if (!validBiography($f3->get('biography'))){
        $isValid = false;
        $f3->set("errors['seeking']", "Must be proper input");
    }

    return $isValid;
}

/* Validate the form
 * @return boolean
 */
function validForm3() {

    global $f3;
    $isValid = true;

    if (!validOutdoor($f3->get('outdoor'))){
        $isValid = false;
        $f3->set("errors['outdoor']", "Outdoor interests must be valid");
    }

    if(!validIndoor($f3->get('indoor'))){
        $isValid = false;
        $f3->set("errors['indoor']", "Indoor interests must be valid");
    }

    return $isValid;
}


/* Validate a first
 * Food must not be empty and may only consist
 * of alphabetic characters.
 *
 * @param String first
 * @return boolean
 */
function validFirst($first)
{
    return !empty($first) && ctype_alpha($first);
}

/* Validate quantity
 * Quantity must not be empty and must be a number
 * greater than 1.
 *
 * @param String last
 * @return boolean
 */
function validLast($last)
{
    return !empty($last) && ctype_alpha($last);
}

/* Validate Age
 * Age must be numeric
 *
 */
function validAge($age)
{
    return !empty($age) && ctype_digit($age) && $age > 18 && $age < 118;
}
/* Validate a gender
 *
 * @param String gender
 * @return boolean
 */
function validGender($gender)
{
    global $f3;
    return in_array($gender, $f3->get('genders'));
}

/* Validate Phone
 *
 * @param String phone
 * valid if phone is a numeric or not empty
 */
function validPhone($phone)
{
    return !empty($phone) && ctype_digit($phone);
}

/* Validate Email
 *
 * @param String email
 * validate whether the input will be an accurate email or not
 */
function validEmail($email)
{
    // Checks if valid email
    return !empty($email)
        && (filter_var($email, FILTER_VALIDATE_EMAIL));
}

/* Validate Outdoor
 *
 * @param String outdoor
 * validate indoor inputs.
 */
function validOutdoor($outdoor)
{
    global $f3;

    if (empty($outdoor)) {
        return true;
    }

    //But if there are condiments, we need to make sure they're valid
    foreach ($outdoor as $outdoors) {
        if (!in_array($outdoors, $f3->get('outdoor'))) {
            return false;
        }
    }
    return true;
}

/* Validate Indoor
 *
 * @param String indoor
 * validate indoor inputs.
 */
function validIndoor($indoor)
{
    global $f3;

    if (empty($indoor)) {
        return true;
    }

    //But if there are condiments, we need to make sure they're valid
    foreach ($indoor as $indoors) {
        if (!in_array($indoors, $f3->get('indoor'))) {
            return false;
        }
    }

    return true;
}

/* Validate seeking
 *
 * @param String seeking
 * @return boolean
 */
function validSeeking($seeking)
{
    global $f3;

    if (empty($seeking)) {
        return true;
    }

    return true;
}

/* Validate Biography
 *
 * @param String biography
 * @return boolean
 */
function validBiography($biography)
{
    return !empty($biography);
}

/*
 * Validate State
 * placeholder
 */
function validState($state)
{
    return !empty($state);
}