<?php
// ini_set("display_errors",1);
require('/mnt/d/code/work_scripts/mediacore_api/mediacore.php');
// require_once('/mnt/d/code/work/mediacore_api/functions.php');

$mc = new MediaCore(11);
$new_lv_ip = '178.154.207.212';


function get_lv_points($mc) {
    
    $users = get_company_by_name($mc, 'lv-voip');
    $lv_users_points = [];
    $lv_user_prefixes = [];
    $i = 0;

    foreach ($users as $user) {
        $lv_users_points[] = get_op_details_by_id($mc, $user['id']);
    }

    // return $lv_users_points;

    foreach ($lv_users_points as $point) {
        foreach ($point[0]['ips'] as $ip) {
            if ($ip['ip'] == "178.154.207.212/32") {
                continue 2;
            }
        }

        $lv_client_name = $point[0]['op']['name'];
        $lv_user_prefixes[] = [
            $point[0]['op']['client_id'] => [
                'lv_id' => substr($lv_client_name, 0, strpos($lv_client_name, "_")),
                'op_id' => $point[0]['op']['id'],
                'prefix' => $point[0]['ips'][0]['prefix']
            ]
        ];
    }


    return $lv_user_prefixes;

}


function add_ip_row($mc, $op_id, $ip, $prefix) {
    $request_array = [
        "op_id" => $op_id,
        "contexts" => [
            [
                "enabled" => true,
                "context_id" => 1,
                "prefix" => $prefix,
                "ip" => $ip
            ]
        ]
    ];

    return $mc->call($request_array, 'op_ip_add'); 
}

// print_r(add_ip_row($mc, $test_caller_op_id, $test_caller_ip, 264 . "#"));
// die();

$result = [];

$prefixes = get_lv_points($mc);

foreach ($prefixes as $client => $prefixes) {
    foreach ($prefixes as $key => $op) {
        $result[] = add_ip_row($mc, intval($op['op_id']), $new_lv_ip, strval($op['prefix']));
    }
}

print_r($result);
echo("Count of users to add IP: " . count($prefixes) . "\n");
