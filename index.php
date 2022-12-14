
<?php 
require  __DIR__.'/vendor/autoload.php';

$repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
->immutable()
->make();

$dotenv = Dotenv\Dotenv::create($repository, __DIR__);
$dotenv->load();

include('./database/connection.php');
include('./controller/security.php');
include('./controller/Portal.php');




$db = new DatabaseClass(); // initialize db
$systemDetails = getSystemDetails($db); // initialize system details 
 
$users_details = getDetailsUsers($db); // check user

include('./pages/header.php'); // header html

if(!$users_details){
    header("location:logout.php");
}

    $level = [
        "admin" => [
            "home",
            "pending_courier",
            "pending_shop",
            "list_users",
            "list_shops",
            "list_courier",
        
        ],
        "users" =>[
            "user_home",
            "laundry_shop",
            "all_status",
            "list_nofity",
        ],
        "courier" => [
            "user_home",
            "new_order",
            "all_status",
            "order_accepted",
            "ready_to_pick_up",
            "review_items",
            "item_collected",
            "arrived_at_destination",
            "in_process",
            "packaging",
            "ready_to_delivered",
            "in_transit",
            "arrived_at_destination_client",
            "delivered",
            "declient_order",
            "list_nofity",
        ], 
        "shops" => [
            "user_home",
            "shops_details",
            "new_request",
            "all_status",
            "arrived_at_destination",
            "in_process",
            "packaging",
            "in_transit",
            "delivered",
            "declient_order",
            "list_nofity",
            "shops_services",
        ]
    ];
    $accessable = ["logout"];
    $folder_type = "";
    $home_page = "";
    switch ($_SESSION["user_type"] ) {
        case 1:
            $folder_type  = 'admin/';
            $home_page = 'home';
            break;
        case 2:
            $folder_type  = 'users/';
            $home_page = 'user_home';
            break;
        case 3:
            $folder_type  = 'courier/';
            $home_page = 'user_home';
            break;
        case 4:
            $folder_type  = 'shops/';
            $home_page = 'user_home';
        break;
        default:
            $folder_type ='';
            break;
    }
    $page = isset($_GET['page']) ? $_GET['page'] : $home_page;

    if(!isset($_GET["page"])) {
        header('Location: index.php?page='.$page);//go home
    }

    if(!file_exists($folder_type.$page.".php")){
        if(in_array($page, $accessable)){
            include $page.'.php';
        } else {
            include '404.html';
        }
    } else {
        if($_SESSION["user_type"] == 1){
            if(in_array($page, $accessable)){
                include $page.'.php';
            } elseif (in_array($page, $level["admin"])) {
                include('./admin/'.$page.'.php');
            } else {
                include '404.html';
            }
            
        }elseif($_SESSION["user_type"] == 3) {
            $information = getDetailsUsersInformation($db);
            if(in_array($page, $accessable)){
                include $page.'.php';
            } elseif (in_array($page, $level["courier"])) {
                // include $page.'.php';
                include('./courier/'.$page.'.php');
            } else {
                include '404.html';
            }
        } elseif($_SESSION["user_type"] == 2 ) {
            $information = getDetailsUsersInformation($db);
            if(in_array($page, $accessable)){
                include $page.'.php';
            } elseif (in_array($page, $level["users"])) {
                // include $page.'.php';
                include('./users/'.$page.'.php');
            } else {
                include '404.html';
            }
        } elseif($_SESSION["user_type"] == 4 ) {
            $information = getDetailsUsersInformation($db);
            if(in_array($page, $accessable)){
                include $page.'.php';
            } elseif (in_array($page, $level["shops"])) {
                // include $page.'.php';
                include('./shops/'.$page.'.php');
            } else {
                include '404.html';
            }
        }
    }
   
 include('./pages/footer.php');//fotter 

?>
