<?php
include_once "ConnectionClass.php";
include_once "User.php";
include_once "initialize.php";
include_once 'Facade.php';

$DB = new Database();
$x = new user();
$Message = "";

if(isset($_POST["SignUp"])) {
$x->Fname= $_POST["Fname"];
$x->Lname= $_POST["Lname"];
$x->Birthday = $_POST["BD"];
$x->NID = $_POST["NID"];
$x->HAddress = $_POST["HAddress"];
$x->WAddress = $_POST["WAddress"];
$x->email=$_POST["Email"];
$x->password=$_POST["Password"];
$x->PhoneNumber = $_POST["Phone"];
$x->ContactMethod = $_POST["Method"];

$x::SignUp($x);
header("Location:index.php");
}
if(isset($_POST["AddCus"])) {
$x->Fname= $_POST["Fname"];
$x->Lname= $_POST["Lname"];
$x->Birthday = $_POST["BD"];
$x->NID = $_POST["NID"];
$x->HAddress = $_POST["HAddress"];
$x->WAddress = $_POST["WAddress"];
$x->email=$_POST["Email"];
$x->password=$_POST["Password"];
$x->PhoneNumber = $_POST["Phone"];
$x->ContactMethod = $_POST["Method"];

$x::SignUp($x);
header("Location:customers.php");
}

if(isset($_POST["LogIn"])) {

$x->email= $_POST["email"];
$x->password= $_POST["pass"];

$success = $x::LogIn($x->email,$x->password);

if($success== false) {
    $errorm1= "Sign in failed.";
    $_SESSION["errorm1"] = $errorm1;
    $errorm2="Please check your email and password.";
    $_SESSION["errorm2"] = $errorm2;
    header("Location:index.php");
}


else {

echo $_SESSION["ID"];
echo "<br>";
echo "<br>";
echo $_SESSION["UT"];
$UserType = $_SESSION["UT"];

if($UserType == 6) {
    header("Location:salesdash.php");
}
if($UserType == 5) {
    header("Location:salesdash.php");
}
if($UserType == 4) {
    header("Location:custdash.php");
}
if($UserType == 3) {
    header("Location:empdash.php");
}
if($UserType == 2) {
    header("Location:mandash.php");
}
if($UserType == 1) {
    header("Location:dash.php");
}

 }
}

if(isset($_POST["ChangePassword"])) {

$id= $_POST["id"];
$newpass= $_POST["np"];
$newpassconfirm= $_POST["npc"];


if($newpass == $newpassconfirm){
    $newpass = sha1($newpass);
    User::EditPassword($id, $newpass);
    header("Location:userdetails.php?id=".$id);
}
    else {
        $Message = '<label class="text-success">Sorry, Your Password Was Not Changed, Try Again.</label>';
        header("Location:EditPassword.php");
    }
}

if(isset($_POST["LogOut"])) {
$x::LogOut();
header("Location:index.php");
}


if(isset($_POST["Add"])){ // Re-direct to Delete Customer Page.
header("Location:AddCustomer.php");
}

if(isset($_POST["RemoveCustomer"])){
$x->id = $_POST["CID"];
$x::DeleteCustomer($x->id);
header("Location:customers.php");
}

if(isset($_POST["DeleteEmployee"])) { // Re-direct to Delete Employee (Manager/Employee) Page.
header("Location:DeleteEmployee.php");
}

if(isset($_POST["DeleteEmp"])){
$x->id = $_POST["EID"];
$x::DeleteCustomer($x->id);
header("Location:employees.php");
}

if(isset($_POST["AddEmployee"])){ // Re-direct to Add Employee Page.
header("Location:EmployeeSignUp.php");
}

if(isset($_POST["EmpSignUp"])) {
$x->Fname= $_POST["Fname"];
$x->Lname= $_POST["Lname"];
$x->Birthday = $_POST["BD"];
$x->NID = $_POST["NID"];
$x->HAddress = $_POST["HAddress"];
$x->WAddress = $_POST["WAddress"];
$x->email=$_POST["Email"];
$x->password=$_POST["Password"];
$x->PhoneNumber = $_POST["Phone"];
$x->ContactMethod = $_POST["Method"];
$x->UserType = $_POST["UT"];
$x->Role = $_POST["Role"];

$x::EmployeeSignUp($x);
header("Location:employees.php");
}

if(isset($_POST["EditEmployee"])) { // Re-direct to Edit Employee Page.
header("Location:EditEmployee.php");
}

if(isset($_POST["Update"])) {
$x->Fname = $_POST["fn"];
$x->Lname = $_POST["ln"];
$x->email = $_POST["e"];
$x->HAddress = $_POST["ha"];
$x->WAddress = $_POST["wa"];
$x->PhoneNumber = $_POST["pn"];
$x->ContactMethod = $_POST["CM"];
$x->UserType = $_POST["UT"];

$x->id = $_POST["id"];


$x::EditEmployee($x,$x->id);

header("Location:employees.php");

}

if(isset($_POST["UpdateProfile"])) {
$x->Fname = $_POST["fn"];
$x->Lname = $_POST["ln"];
$x->email = $_POST["e"];
$x->HAddress = $_POST["ha"];
$x->WAddress = $_POST["wa"];
$x->PhoneNumber = $_POST["pn"];
$x->ContactMethod = $_POST["CM"];
$x->id = $_POST["id"];

$id= $x->id;
$x::EditProfile($x,$x->id);

header("Location:userdetails.php?id=".$id);

}

if(isset($_POST["SearchButton"])) {
$x->Search = $_POST["Search"];

$id= $x::CustomerSearch($x->Search);

header("Location:userdetails.php?id=$id");

}

if(isset($_POST["EmpSearchButton"])) {
$x->Search = $_POST["EmpSearch"];

$id= $x::EmpSearch($x->Search);

header("Location:userdetails.php?id=$id");
}

if(isset($_POST["ForgotPassword"])) {
$x->email = $_POST["email"];

$x->password = $_POST["NewPassword"];
$x->password = sha1($x->password);

$id = $x::GetUserEmail($x->email);

$x::EditPassword($id, $x->password);

header("Location:Index.php");
}
?>
