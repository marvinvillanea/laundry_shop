<?php 

function registerUsers($db, $data){
    $getID = $db->Insert("INSERT INTO users (username,email,`password`,`status`,user_type,`verify`) VALUES (?,?,?,?,?,?)", array($data["username"],$data["email"],$data["password"], 1,2,1) );
    if($getID){
        $_SESSION['user_id'] = $getID;
        $_SESSION["user_type"] =  2;
        $systemDetails = getSystemDetails($db);
        $image = "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg";
        $get_p_info_id = $db->Insert("INSERT INTO personal_info (`user_id`,`image`,`city`,`province`,`zip_code`,`barangay`,`zone`,`landmark`, `first_name`, `last_name`, `middle_name`, `contact_no`)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", [$getID,$image,$systemDetails["city"],$systemDetails["province"],$systemDetails["zip_code"], $data["list_barangay"], $data["zone_number"], $data["landmark"], $data["first_name"], $data["last_name"], $data["middle_name"], $data["contact_no"] ]);
        if($get_p_info_id){
            // if($db->Insert("INSERT INTO address_info (`p_info_id`) VALUES (?)", [$get_p_info_id])){
            //     return true;
            // }
            return true;
        }
    }
    return false;
}

function getSystemDetails($db){
    $data = $db->Select("SELECT * FROM `system_info` limit 1");
    if(count($data) > 0){
        return $data[0];
    } else {
        return false;
    }
}

function registerCourier($db, $data){
    try {
        $getID = $db->Insert("INSERT INTO users (username,email,`password`,`status`,user_type,`verify`) VALUES (?,?,?,?,?,?)", array($data["username"],$data["email"],$data["password"], 1,3,0) );
        if($getID){
            $_SESSION['user_id'] = $getID;
            $_SESSION["user_type"] = 3;
            $systemDetails = getSystemDetails($db);
            $image = "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg";
            $get_p_info_id = $db->Insert("INSERT INTO personal_info (`user_id`,`image`,`city`,`province`,`zip_code`,`barangay`,`zone`,`landmark`, `first_name`, `last_name`, `middle_name`, `contact_no`)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", [$getID,$image,$systemDetails["city"],$systemDetails["province"],$systemDetails["zip_code"], $data["list_barangay"], $data["zone_number"], $data["landmark"], $data["first_name"], $data["last_name"], $data["middle_name"], $data["contact_no"] ]);
            if($get_p_info_id){
                if($db->Insert("INSERT INTO courier_details (`p_info_id`,`resume`,`description`,`driver_license`) VALUES (?,?,?,?)", [$get_p_info_id,$data["resume"],$data["textarea-input"],
            $data["driver_license"]])){
                    return true;
                }
            }
        }
        return false;
    } catch(\Exception $e) {
        // return $e->getMessage() . $e->getLine();
        return false;
    }
    
}

function registerShop($db, $data){
    try {
        $getID = $db->Insert("INSERT INTO users (username,email,`password`,`status`,user_type,`verify`) VALUES (?,?,?,?,?,?)", array($data["username"],$data["email"],$data["password"], 1,4,0) );
        if($getID){
            $_SESSION['user_id'] = $getID;
            $_SESSION["user_type"] = 4;
            $systemDetails = getSystemDetails($db);
            $image = "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg";
            $get_p_info_id = $db->Insert("INSERT INTO personal_info (`user_id`,`image`,`city`,`province`,`zip_code`,`barangay`,`zone`,`landmark`, `first_name`, `last_name`, `middle_name`, `contact_no`)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", [$getID,$image,$systemDetails["city"],$systemDetails["province"],$systemDetails["zip_code"], $data["list_barangay"], $data["zone_number"], $data["landmark"], $data["first_name"], $data["last_name"], $data["middle_name"], $data["contact_no"] ]);
            if($get_p_info_id){
                $shps_id =$db->Insert("INSERT INTO shops (`p_info_id`,`name`,`descriptions`,`logo`,`permit`,`owner`,`bussiness_id`) VALUES (?,?,?,?,?,?,?)", 
                    [
                        $get_p_info_id,
                        $data["shop_name"],
                        $data["shop_descriptions"],
                        $data["logo"],
                        $data["permit"],
                        $data["shop_owner"],
                        $data["shop_bussiness_id"]
                    ]);
                if($shps_id){
                    foreach ($data["services"] as $value) {
                        $db->Insert("INSERT INTO shops_services (`shop_id`,`services_id`) VALUES (?,?)", 
                        [
                            $shps_id,
                            $value
                        ]);
                    }
                    return true;
                }
            }
        }
        return false;
    } catch(\Exception $e) {
        // return $e->getMessage() . $e->getLine();
        return false;
    }
    
}

function getMyUrl()
{
  $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
  $server = $_SERVER['SERVER_NAME'];
  $port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
//   return $protocol.$server.$port;
    return getenv('URL_HOST');
}

function insertToken($db){
    $token = bin2hex(random_bytes(16));
    $db->Insert("INSERT INTO access_token (access_token,`user_id`) VALUES (?,?)", array($token, $_SESSION["user_id"]) );
    $_SESSION['access_token'] = $token;
}

function getDetailsUsers($db){
    $data = $db->Select("SELECT * ,
        (case when `verify` = 0 then 'NOT YET VERIFY' when `verify` = 1 then 'VERIFY' else 'DISABLE USER' end ) as status_user
    FROM access_token
    inner join users  using(user_id)
     where access_token = ?  order by token_id desc limit 1", array($_SESSION["access_token"]));
    if(count($data) > 0){
        return $data[0];
    } else {
        return false;
    }
    
}


function getDetailsUsersInformation($db){
    $data = $db->Select("SELECT * FROM users inner join personal_info using(user_id) where user_id = ? limit 1",array($_SESSION["user_id"]) );
    return $data[0];
}
function countUserPendingAdminCourier($db){
    $data = $db->Select("SELECT count(*) as total_users FROM users where `verify` = 0 and user_type in (3)" );
    return $data[0];
}
function countUserPendingAdminShopes($db){
    $data = $db->Select("SELECT count(*) as total_users FROM users where `verify` = 0 and user_type in (4)" );
    return $data[0];
}
function updateUSerProfile($db){
    extract($_POST);
    try {
        $folder = "images/profile";
        $temp = explode(".", $_FILES["image"]["name"]);
        $newfilename = round(microtime(true)).'.'. end($temp);
        $db_path ="$folder".$newfilename ;
        // //remove the .
        // $listtype = array(
        // '.jpg'=>'application/jpeg',
        // '.png'=>'application/png'
        // ); 
        if ( is_uploaded_file( $_FILES['image']['tmp_name'] ) )
        {
            // if($key = array_search($_FILES['image']['type'],$listtype))
            // {
                if (move_uploaded_file($_FILES['image']  ['tmp_name'],"$folder".$newfilename))
                {
                    // $path =  $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$db_path;
                    $link = getMyUrl().'/'.$db_path;
                    // $image = "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg";
                    $data = $db->Update("update personal_info SET first_name = ?, last_name = ? , middle_name = ?, zone = ?, city = ?, province = ?, zip_code = ?, barangay = ?, house_no = ?, birthdate = ?, gender = ?, contact_no = ?, image = ?, landmark = ?  WHERE user_id = ? ", array(
                        $first_name,
                        $last_name,
                        $middle_name,
                        $zone,
                        $city,
                        $province,
                        $zip_code,
                        $barangay,
                        $house_no,
                        $birthdate,
                        $gender,
                        $contact_no,
                        $link,
                        $landmark,
                        $_SESSION["user_id"]));

                   
                    return true;

                    
                }
                return false;
            // }
            // else    
            // {
            //     // echo "File Type Should Be .Docx or .Pdf or .Rtf Or .Doc";
            //     return "1";
            // }
        } else {
            $data = $db->Update("update personal_info SET first_name = ?, last_name = ? , middle_name = ?, zone = ?, city = ?, province = ?, zip_code = ?, barangay = ?, house_no = ?, landmark = ? , birthdate = ?, gender = ?, contact_no = ?  WHERE user_id = ? ", array(
                $first_name,
                $last_name,
                $middle_name,
                $zone,
                $city,
                $province,
                $zip_code,
                $barangay,
                $house_no,
                $landmark,
                $birthdate,
                $gender,
                $contact_no,
                $_SESSION["user_id"]));
        }
        return false;
    } catch(\Exception $e) {
        // return $e->getMessage();
        return false;
    }
    
}

function getCourierDetails($db){
    $data = $db->Select("SELECT * FROM users 
    inner join personal_info using(user_id) 
    where status = 1 and user_type = 3 and first_name is not null and last_name is not null and middle_name is not null  ");
    return $data;
    // return $data[0];
}

function getWeightAmount($db){
    $data = $db->Select("SELECT * FROM set_weight ");
    return $data;
}

function getaddressOptional($db){
    $data = $db->Select("select * from address_info where user_id = ? ", array($_SESSION["user_id"]));
    return $data;
}

function addParcelUsers($db){
    extract($_POST);
    try {

        $amount = 50;
        
        if(isset($selected_address) && $selected_address == "new"){
            // $address_sender = $address_sender;
            $address_sender = $zone. ' '.$barangay.', '."Anao City". ", Tarlac". " 2310 "; 
            $db->Insert("INSERT INTO address_info (`user_id`,`address`) VALUES (?,?)", [
                $_SESSION["user_id"],
                $address_sender
            ]);
        } else {
            $address_sender  = $selected_address;
        }

        $get_parcel = $db->SELECT("select * from parcel_details where parcel_number = ? ", [$parcel_number]);
        if(count($get_parcel) > 0){
            echo "FAILED";
        } else {
            $get_p_info_id = $db->Insert("INSERT INTO parcel_details (`user_id`,`idcourier_details`,`recepient_name`,`recepient_address`,`recepient_contact_no`,`parcel_number`,`type_delivery`,`weight_id`,`amount`,`address_sender`,`parcel_description`) VALUES (?,?,?,?,?,?,?,?,?,?,?)", [
                $_SESSION["user_id"],
                $idcourier_details,
                $recepient_name,
                $recepient_address,
                $recepient_contact_no,
                $parcel_number,
                $type_delivery,
                $weight_id,
                $amount,
                $address_sender,
                $parcel_description
            ]);
            $description = "You got New Parcel # ".$parcel_number." ".date('y-m-d h:m:s');
            $db->Insert("INSERT INTO courier_notify (`user_id`,`description`) VALUES (?,?)", [
                $idcourier_details,
                $description
            ]);
        }
        
       
        } catch(\Exception $e) {
            return $e->getMessage();
            // return false;
        }
    }

    function getShopsDetails($db){
       $data = $db->Select("SELECT * FROM users 
        inner join personal_info using(user_id) 
        inner join shops using(p_info_id)
        where user_id = ? limit 1",array($_SESSION["user_id"]) );
        return $data[0];
    }


    function message($status,$message){
        $msg = array(
            "success" => $status,
            "message" => $message
        );
        echo arraytojson($msg);
        die();
    }


    function arraytojson($array){

        if(is_array($array)){
            return json_encode($array,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }else{
            /* $this->showErrors("arraytojson","<span class='method'> arraytojson()</span> expecting an array.",$line); */
            return false;
        }
    }

    function getShopDetailsSpecific($db, $shopid){
        $data = $db->Select("SELECT * FROM users 
        inner join personal_info using(user_id) 
        inner join shops using(p_info_id)
        where shop_id = ? limit 1",array($shopid) );
        return $data[0];
    }

    function countOrderShops($db){
         $data = $db->Select("SELECT count(*) as total_new_order FROM form_booking_user  fb
        inner join shops s  ON fb.shop_id = s.shop_id
        inner join personal_info pi ON s.p_info_id = pi.p_info_id
        where pi.user_id = ? and status_booking = 0 limit 1",array($_SESSION["user_id"]) );
        return $data[0];
    }
    

    function countOrderCourier($db){
         $data = $db->Select("SELECT count(*) as total_new_order FROM form_booking_user  fb
        where fb.user_id = ? and status_booking = 1 limit 1",array($_SESSION["user_id"]) );
        return $data[0];
    }
 
?>