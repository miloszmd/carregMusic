<?php
function sanitise($value) {
    return htmlentities($value);
}

function isRequired($needle, $haystack) {
    return in_array($needle, $haystack);
}

function safePOST($variable)  {
    return mysql_real_escape_string($_POST[$variable]);
}

function validEmail($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function validIntegerRange($value, $min, $max) {
    $options = array(
        'options'=>array(
            'min_range'=>$min,
            'max_range'=>$max
        )
    );
    return filter_var($value, FILTER_VALIDATE_INT, $options);
}

function validStringMaxLength($value, $max) {
    $isOkay = false;
    if(strlen($value) <= $max && !is_numeric($value)) {
        $isOkay = true;
    }
    return $isOkay;
}

function HtmlErrorMessage($field, $errorMessages) {
    if(isset($errorMessages[$field])) {
        echo '<span class="error_msg">' . sanitise($errorMessages[$field]) . '</span>';
    }
}

function HtmlText($value) {
    if(!empty($value)) {
        echo sanitise($value);
    }
}

function validateFormField($value, $field) {
    $errorMessage = '';

	require_once('\..\config.php');
	
    switch($field) {
        case 'username':
            if(!validStringMaxLength($value, 25)) {
                $errorMessage = 'Name must be 5 characters or less and must be a string';
            }
            break;
		case 'password':
			if (!validStringMaxLength($value, minLength, passwdMaxLength)) {
				$errorMessage; = 'Password must have between '.minLength.' and '.passwdMaxLength.' characters';
			}
		case 'email':
			if(!validEmail($value) || strlen($value) > emailMaxLength) {
				$errorMessage = 'Invalid email address';
			}
			break;
		case 'login':
		    if(!validStringMaxLength($value, minLength, passwdMaxLength)) {
                $errorMessage = 'Login must have between '.minLength.' and '.loginMaxLength.' characters and must be a string';
            }
			break;
		case 'nickname':
			if(!validStringMaxLength($value, minLength, nicknameMaxLength)) {
                $errorMessage = 'Login must have between '.minLength.' and '.nicknameMaxLength.' characters and must be a string';
            }
			break;
		case 'genre':
            if(!validIntegerRange($value, 1, genreMaxValue)) {
                $errorMessage = 'Please choose your favorite genre from dropdown list.';
            }
			break;
		case 'country':
            if(!validIntegerRange($value, 1, countryMaxValue)) {
                $errorMessage = 'Please choose country from dropdown list.';
            }
			break;
        default:
    }
    return $errorMessage;
}


function loggedIn(){
/*    $loggedIn = false;

    if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
        $loggedIn = true;
    }

    return $loggedIn;
*/
	return (isset($_SESSION['username']) || isset($_COOKIE['username'])); //not tested yet, shoulda work
}



function isThisLoginInDB($login)
{
    $sameNick = mysql_query("SELECT username from users WHERE username = '".$login."'");

    if (!$sameNick) {
        die('Query checking login failed.');
    }

    if (mysql_num_rows($sameNick) > 0) {
        return true;
    } else {
	    return false;
    }
}

