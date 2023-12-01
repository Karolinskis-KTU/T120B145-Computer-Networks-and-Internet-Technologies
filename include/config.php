<?php
//nustatymai.php
define("BASE_URL", "http://localhost/");

define("GOOGLE_MAPS_API_KEY", "CHANGE_ME");

define("DB_SERVER", "localhost");
define("DB_USER", "stud");
define("DB_PASS", "stud");
define("DB_NAME", "taxi");
define("TBL_USERS", "users");
define("TBL_DRIVERS", "drivers");
define("TBL_ORDERS", "orders");
define("TBL_CARS", "cars");
$user_roles=array(      // vartotojų rolių vardai lentelėse ir  atitinkamos userlevel reikšmės
	"Administratorius"=>"10",
	"Dispečeris"=>"9",
	"Vairuotojas"=>"4",
	"Klientas"=>"2",	// galioja ir vartotojas "guest", kuris neturi userlevel
	"Užblokuotas"=>"0",
);   

$order_statuses=array (
	"Atšaukta"=>"0",
	"Laukiama"=>"1",
	"Vykdoma"=>"3",
	"Įvykdyta"=>"10",
);
$car_statuses=array (
	"Ilsisi"=>"0",
	"Laukia"=>"1",
	"Dirba"=>"2",
);
define("ADMIN_LEVEL","Administratorius");  // kas turi vartotojų valdymo teisę
define("DISPATCH_LEVEL","Dispečeris");  // kas turi vartotojų valdymo teisę
define("DEFAULT_LEVEL","Klientas");  // kokia rolė priskiriama kai registruojasi
define("BLOCKED_LEVEL","Užblokuotas");  // kokia rolė kai užblokuotas vartotojas
$uregister="both";  // kaip registruojami vartotojai

define("PRICE_FOR_KILOMETER","0.5");
define("MINIMUM_CAR_RANGE","50"); // Range in kilometers
define("MAXIMUM_MONEY_COLLECTED","100"); // Maximum ammount of cash a driver can have