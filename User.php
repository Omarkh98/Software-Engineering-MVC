<?php
include_once 'ConnectionClass.php';
$DB = new Database();

class user {
public $id;
public $Fname;
public $password;
public $Birthday;
public $NID;
public $HAddress;
public $WAddress;
public $email;
public $HashedPassword;
public $ContactMethod;
public $UserType;
public $Lname;
public $Connection;
public $PhoneNumber;
public $E;
public $P;
public $Role;
public $DropDown;
public $Search;
public $Email;
public $Subject;
public $Body; 

static function CustomerSearch($Search) {
$DB = Database::GetInstance();

$SearchName = "SELECT * FROM user WHERE `FName` LIKE '%$Search%' AND UserType='4' AND Is_Deleted = 0 OR `LName` LIKE '%$Search%' AND UserType='4' AND Is_Deleted = 0";

$SearchID = "SELECT * FROM user WHERE `ID` = '$Search' AND UserType='4' AND Is_Deleted = 0";

$Result = mysqli_query($DB->GetConnection(),$SearchName);
$Result2 = mysqli_query($DB->GetConnection(),$SearchID);


    if(mysqli_num_rows($Result) > 0){
      while ($row = mysqli_fetch_assoc($Result)) {
          $id = $row['ID'];
          return $id;
      }
    }
}

static function EmpSearch($Search) {
$DB = Database::GetInstance();

$SearchName = "SELECT * FROM user WHERE `FName` LIKE '%$Search%' AND UserType='2' AND Is_Deleted = 0 OR `LName` LIKE '%$Search%' AND UserType='2' AND Is_Deleted = 0 OR `FName` LIKE '%$Search%' AND UserType='3' AND Is_Deleted = 0 OR `LName` LIKE '%$Search%' AND UserType='3' AND Is_Deleted = 0 OR `FName` LIKE '%$Search%' AND UserType='5' AND Is_Deleted = 0 OR `LName` LIKE '%$Search%' AND UserType='5' AND Is_Deleted = 0 OR `FName` LIKE '%$Search%' AND UserType='6' AND Is_Deleted = 0 OR `LName` LIKE '%$Search%' AND UserType='6' AND Is_Deleted = 0";

$SearchID = "SELECT * FROM user WHERE `ID` = '$Search' AND UserType='2' AND Is_Deleted = 0 OR `ID` = '$Search' AND UserType='3' AND Is_Deleted = 0";

$Result = mysqli_query($DB->GetConnection(),$SearchName);
$Result2 = mysqli_query($DB->GetConnection(),$SearchID);

    if(mysqli_num_rows($Result) > 0){
      while ($row = mysqli_fetch_assoc($Result)) {

          $id = $row['ID'];
          return $id;
      }
    }
}

static function GetUserInfo($Drop) {
$DB = Database::GetInstance();

$Data = array();

$Query = "SELECT * FROM user WHERE ID = '$Drop'";
$Result = mysqli_query($DB->GetConnection(),$Query);

while($row = mysqli_fetch_array($Result)) {
    $Data [] = $row;
}
return $Data;
}


static function LogOut() {
  session_start();
  session_destroy();
}

static function LogIn($E,$P) {
$DB = Database::GetInstance();

$HashedPassword = "";
$HashedPassword = sha1($P);

    $Query = "SELECT * FROM user WHERE Email ='$E' AND Password ='$HashedPassword' AND Is_Deleted = 0";
    $Result = mysqli_query($DB->GetConnection(),$Query);

    $row = mysqli_fetch_assoc($Result);

    session_start();

    $_SESSION["ID"] = $row["ID"];
    $_SESSION["UT"] = $row["UserType"];
    $_SESSION["FirstN"] = $row["FName"];
    $_SESSION["LastN"] = $row["LName"];

     if(mysqli_num_rows($Result) == 0) {
         $success = false;
    }
    else if($row["UserType"] == 4 && $row["Approved"] == 0) {
         $sucess = false;
    }
    else {
         $success = true;
    }


    return $success;
}
    
static function ChangePassword($id,$oldpass,$newpass,$newpassconfirm) {
    $DB = Database::GetInstance();
    
    $Query = "SELECT * FROM user WHERE ID ='$id' AND Is_Deleted = 0";
    $Result = mysqli_query($DB->GetConnection(),$Query);

    $row = mysqli_fetch_assoc($Result);
    
    $old_db_pass = $row['Password'];
    $hashed_old = sha1($oldpass);
    $hashed_new = sha1($newpass);
    $hashed_newc= sha1($newpassconfirm);
    
    if($old_db_pass != $hashed_old) {
        return -1;
    }
    elseif($hashed_new != $hashed_newc) {
        return -1;
    }
    
    else {
        
        $Query2 = "Update user set Password ='$hashed_new' WHERE ID = '$id' ";
        $Result2 = mysqli_query($DB->GetConnection(),$Query2);
        echo $Query2;

    if($Result2){
       echo "Changed";
       return true;
    }
        
    }
    
}

static function SignUp($Object){
$DB = Database::GetInstance();

$UserType = 4;

$Object->password = sha1($Object->password);

$Object->Birthday = strtotime($Object->Birthday);

$newformat = date('Y-m-d',$Object->Birthday);

$Object->Birthday = $newformat;

$Role = "Regular Customer";

$Query = "INSERT INTO user(FName,LName,Birthday,NationalID,HAddress,WAddress,Email,Password,PhoneNumber,ContactMethod,Role,UserType)
    VALUES('".$Object->Fname."','".$Object->Lname."','".$Object->Birthday."','".$Object->NID."','".$Object->HAddress."','".$Object->WAddress."','".$Object->email."','".$Object->password."','".$Object->PhoneNumber."','".$Object->ContactMethod."','$Role','$UserType')";
$Result = mysqli_query($DB->GetConnection(),$Query);

}

static function EmployeeSignUp($Object){
$DB = Database::GetInstance();

$Object->password = sha1($Object->password);

$newformat = date('Y-m-d', strtotime($Object->Birthday));

$Object->Birthday = $newformat;

$Query = "INSERT INTO user(FName,LName,Birthday,NationalID,HAddress,WAddress,Email,Password,PhoneNumber,ContactMethod,UserType,Role)
    VALUES('".$Object->Fname."','".$Object->Lname."','".$Object->Birthday."','".$Object->NID."','".$Object->HAddress."','".$Object->WAddress."','".$Object->email."','".$Object->password."','".$Object->PhoneNumber."','".$Object->ContactMethod."','".$Object->UserType."','".$Object->Role."')";


$Result = mysqli_query($DB->GetConnection(),$Query);
}

static function GetContactMethod() {
$DB = Database::GetInstance();

    $Query = "SELECT * FROM contactmethods";
    $Result = mysqli_query($DB->GetConnection(),$Query);

    if(mysqli_num_rows($Result) > 0) {
        $Data = array();
        $i = 0;

        while($row=mysqli_fetch_array($Result)) {
        $Data [$i] = new user(0);

        $Data[$i]->id = $row['ID'];
        $Data[$i]->ContactMethod = $row['Name'];

        $i = $i + 1;
    }
    return $Data;
    }
    else {
        return NULL;
    }
}
?>
