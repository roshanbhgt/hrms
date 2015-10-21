<?php
/**
 * @param varchar $email
 * @return boolean
 */
function validateEmail($email){
    if(preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $email) ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Function to check whether user is login or not
 */
function checkAuthentication(){
    if (empty($_SESSION['adminid'])) {
        header('Location: '.FRONTEND.'index.php');
        exit;
    }
}

function getStatusLabel($id){
    $status = array('0'=>'In Active','1'=>'Active');
    return $status[$id];
}

?>
