<?php 

// print output in json format with pretty formating
function print_json($array) {
    echo json_encode($array, JSON_PRETTY_PRINT) . "\n";
}


// !!! COMPANIES !!!
// add new company
function add_client($mc, $user_id) {
    $requestArray = array (
        'email_tech' => "user$user_id@test.zorra.com",
        'email' => "user$user_id@test.zorra.com",
        'invisible' => false,
        'name' => $user_id . '_1597402239_test',
        'owner_id' => 1,
        'balance_currency_id' => 2,
        'multicurrency' => false,
        'send_traffic_report' => false,
        'service_restore_flag' => true,
        'manager_id' => 41,
        'services_ids' => array (
                0 => 1,
                1 => 2,
            ),
    );

    $res = $mc->call($requestArray, 'client_add'); 

    return $res;
}

// // get information about company id
function get_company_by_id($mc, $id = null, $invis = false) { //  $name = '',
    $requestArray = [
        "id" => $id,
        "show_invisible" => $invis,
        "fields" => [
            "id", 
            "name", 
            "email_tech", 
            "balance", 
            "balance_id", 
            "limit",
            "invisible",
            "services_ids", 
            "actual_vat_balance",
            "alarm_flag",
            "alarm_limit",
            "notify_flag",
            "alarm_limit_unit"
        ]
    ];

    $res = $mc->call($requestArray, 'client_get'); 
    return($res[0]);
}

// get information about company by name
function get_company_by_name($mc, $name, $fields = null) { //  $name = '',
    if (!$fields) {
        $requestArray = [
        // "id" => $id,
            "fields" => [
                "id", 
                "name", 
                "email_tech", 
                "balance", 
                "balance_id",
                "currency_id",
                "limit",
                "services_ids", 
                "actual_vat_balance",
                "alarm_limit",
                "notify_flag",
                "limit",
                "limit_flag",
                "alarm_limit_unit",
                "alarm_flag",
                "tp_limit",
                "tp_limit_flag"
            ]
        ];
    } else {
        $requestArray = [
        // "id" => $id,
            "fields" => $fields
        ];
    }


    $res = $mc->call($requestArray, 'client_get'); 

    $result = [];
    
    foreach ($res as $line) {
        if (strpos($line['name'], $name) !== false) {
            $result[] = $line;
        };
    }
  
    return $result;
}

// get information about company by email
function get_company_by_email($mc, $email) { //  $name = '',
    $requestArray = [
        // "id" => $id,
        "fields" => [
            "id", 
            "name",
            "email",
            "email_tech", 
            "balance", 
            "balance_id", 
            "limit",
            "services_ids", 
            "actual_vat_balance",
            "alarm_flag",
            "alarm_limit",
            "notify_flag",
            "alarm_limit_unit"
        ]
    ];

    $res = $mc->call($requestArray, 'client_get'); 

    $result = [];
    
    foreach ($res as $line) {
        if (strpos($line['email'], $email) !== false) {
            $result[] = $line;
        };
    }
  
    return $result;
}

// change company financial settions (you should send company objet here - you can get it with functions get_company_by*, all paramerets except id can be changed)
function set_client_financial_settings($mc, $company, $limit_flag) {

    $requestArray = [
        "id" => $company['id'],
        "tp_limit" => $company['tp_limit'],
        "alarm_flag" => $company['alarm_flag'],
        "tp_limit_flag" => $company['tp_limit_flag'],
        "limit" => $company['limit'],
        "currency_id" => $company['currency_id'],
        // "limit_flag" => $company['limit_flag'],
        "limit_flag" => $limit_flag,
        "alarm_limit" => $company['alarm_limit'],
        "service_restore_flag" => true

    ];

    $res = $mc->call($requestArray, 'client_set');

    return $res;
}

// !!! ORIGINATION | TERMINATION POINTS !!!
// return list of IPs for given op_id
function get_op_ip($mc, $op_id) {
    $requestArray = [
        "limit" => 101,
        "op_id" => $op_id,
        "contexts" => [1],
        "offset" => 0,
        "fields" => ["id","op_id","ip","prefix","enabled"]
    ];

    $res = $mc->call($requestArray, 'op_ips_get'); 
    return($res);
}

// return details of OP with given id (can access to invisible points, can access to SMS points)
function get_op_by_id($mc, $id = null, $invisible = false, $sms = false) {
    $requestArray = [
        "invisible" => $invisible,
        "id" => $id,
        "fields" => [
            'id',
            'name',
            'client_name',
            'client_id',
        ]
    ];

    $result = [];

    if ($sms) {
        $pref = 'sms_';
    } else {
        $pref = '';
    }
 
    $res = $mc->call($requestArray, $pref . 'ops_get'); 
    
    return $res;
}

// return details of OP with given name (can access to invisible points, can access to SMS points)
function get_op_by_name($mc, $op_name = '', $invisible = false, $sms = false) {
    $requestArray = [
        "invisible" => $invisible,
        "fields" => [
            'id',
            'name',
            'client_name',
            'client_id',
        ]
    ];

    $result = [];

    if ($sms) {
        $pref = 'sms_';
    } else {
        $pref = '';
    }
 
    $res = $mc->call($requestArray, $pref . 'ops_get'); 
    
    foreach ($res as $line) {
        if (strpos($line['name'], $op_name) !== false) {
            $result[] = $line;
        };
    }
  
    return $result;
}

// return OPs of given company by name (can access to invisible points, can access to SMS points)
function get_op_by_client_name($mc, $client_name = '', $invisible = false, $sms = false) {
    $requestArray = [
        "invisible" => $invisible,
        "fields" => [
            'id',
            'name',
            'client_name',
            // 'client_id',
        ]
    ];

    $result = [];

    if ($sms) {
        $pref = 'sms_';
    } else {
        $pref = '';
    }
 
    $res = $mc->call($requestArray, $pref . 'ops_get'); 
    
    foreach ($res as $line) {
        if (strpos($line['client_name'], $client_name) !== false) {
            $result[] = $line;
        };
    }
  
    return $result;
}

// return OPs of given company by id (can access to invisible points, can access to SMS points)
function get_op_by_client_id($mc, $client_id = '', $invisible = false, $sms = false) {
    $requestArray = [
        "invisible" => $invisible,
        "clients_ids" => [$client_id],
        "fields" => [
            'id',
            'name',
            'client_name',
            'client_id',
        ]
    ];

    $result = [];

    if ($sms) {
        $pref = 'sms_';
    } else {
        $pref = '';
    }
 
    $res = $mc->call($requestArray, $pref . 'ops_get'); 
    
    foreach ($res as $line) {
        $result[] = $line;
    }
  
    return $result;
}

// return TPs of given company by name (can access to invisible points, can access to SMS points)
function get_tp_by_name($mc, $op_name = '', $invisible = false, $sms = false) {
    $requestArray = [
        "invisible" => $invisible,
        "fields" => [
            'id',
            'name',
            'client_name',
            'client_id',
            'tariff_name',
            'tariff_id'
        ]
    ];

    $result = [];

    if ($sms) {
        $pref = 'sms_';
    } else {
        $pref = '';
    }
 
    $res = $mc->call($requestArray, $pref . 'tps_get'); 
    
    foreach ($res as $line) {
        if (strpos($line['name'], $op_name) !== false) {
            $result[] = $line;
        };
    }
  
    return $result;
}

// add new IP to given OP
function add_op_ip($mc, $id, $prefix, $ip) {
    $requestArray = [
        "op_id" => $id,
        "contexts" => [
            [
                "enabled" => true,
                "context_id" => 1,
                "prefix" => $prefix,
                "ip" => $ip
            ]
        ]
    ];

    $res = $mc->call($requestArray, 'op_ip_add'); 

    return $res;
}

// change state (enabled/disabled) of given OP by id
function op_change_state($mc, $id, $invisible = false, $name) {
    $requestArray = [
        "id" => $id,
        "invisible" => $invisible,
        "name" => $name
    ];

    $res = $mc->call($requestArray, 'op_add_or_update');

    return $res;
}

// get origination point by company name
function get_op_details_by_client_name($mc, $client_name) {
    $result = [];

    $ops = get_op_by_client_name($mc, $client_name);

    foreach ($ops as $op) {
        $ips = get_op_ip($mc, $op['id']);
        $result[] = array(
            "op" => $op,
            "ips" => $ips
        );
    }

    return $result;
}

// get origination point by company id
function get_op_details_by_client_id($mc, $client_id) {
    $result = [];

    $ops = get_op_by_client_id($mc, $client_id);

    foreach ($ops as $op) {
        $ips = get_op_ip($mc, $op['id']);
        $result[] = array(
            "op" => $op,
            "ips" => $ips
        );
    }

    return $result;
}

// !!! GROUPS !!!
function get_voice_group_by_name($mc, $name) {
    $requestArray = [
        "fields" => ['id', 'name']
    ];
    $result = [];
    $res = $mc->call($requestArray, 'groups_get');

    foreach ($res as $line) {
        if (strpos($line['name'], $name) !== false) {
            $result[] = $line;
        };
    }
    return $result;
}

function get_sms_group_by_name($mc, $name) {
    $requestArray = [
        "fields" => ['id', 'name']
    ];
    $result = [];
    $res = $mc->call($requestArray, 'sms_groups_get');
    // $res = $mc->call($requestArray, 'groups_get');

    foreach ($res as $line) {
        if (strpos($line['name'], $name) !== false) {
            $result[] = $line;
        };
    }
    return $result;
}


function get_group_by_id($mc, $id, $sms = false) {
    
    $requestArray = [
        "fields" => ['id', 'name']
    ];

    $result = [];
    
    if ($sms) {
        $pref = 'sms_';
    } else {
        $pref = '';
    }
    
    $res = $mc->call($requestArray, $pref . 'groups_get');
    // $res = $mc->call($requestArray, 'groups_get');

    foreach ($res as $line) {
        if ($line['id'] == $id) {
            $result[] = $line;
        };
    }
    return $result;
}


// !!! TARIFFS !!!
// return tariff by id (can get sms or voice tariffs / can get OP or TP tariffs)
function tariffs_get_by_id($mc, $tariff_id, $type, $route) {
    
    $result = [];
    $method = 'tariffs';

    if ($type == 'sms') {
        $method = 'sms_' . $method;
    } else if ($type == 'voice') {
        $method = $method;
    } else {
        return null;
    }

    if ($route == 'op') {
        $method = $method . '_op_get';
    } else if ($route == 'tp') {
        $method = $method . '_tp_get';
    } else {
        return null;
    } 

    $requestArray = [
        "sort_by" => [
            [
                "currency_id" => "desc"
            ]
        ],
        "fields" => [
            "tariff_id",
            "name_currency",
            "currency_name",
            "currency_id"
        ]
    ];


    $res = $mc->call($requestArray, $method);

    foreach ($res as $line) {
        if ($line['tariff_id'] == $tariff_id) {
            $result[] = $line;
        };
    }
    return $result;

}

// return tariff by name (can get sms or voice tariffs / can get OP or TP tariffs)
function tariffs_get_by_name($mc, $tariff_name, $type, $route) {

    $result = [];
    $method = 'tariffs';

    if ($type == 'sms') {
        $method = 'sms_' . $method;
    } else if ($type == 'voice') {
        $method = $method;
    } else {
        return null;
    }

    if ($route == 'op') {
        $method = $method . '_op_get';
    } else if ($route == 'tp') {
        $method = $method . '_tp_get';
    } else {
        return null;
    } 

    $requestArray = [
        "sort_by" => [
            [
                "currency_id" => "desc"
            ]
        ],
        "fields" => [
            "tariff_id",
            "name_currency",
            "currency_name",
            "currency_id"
        ]
    ];


    $res = $mc->call($requestArray, $method);

    foreach ($res as $line) {
        if (strpos($line['name_currency'], $tariff_name) !== false) {
            $result[] = $line;
        };
    }
    return $result;

}

// !!! PAYMENTS !!!
// get payments for given company (by name) / time range should be declared in function
function payment_get_by_company($mc, $company_name) {
    $result = [];

    $requestArray = [
        "limit" => "2000",
        "date_from" => "2020-11-05T00:00:00",
        "date_to" => "2020-11-05T23:59:59",
        "sort_by" => [
            [
                "client_name" => "desc"
            ]
        ],
        "offset" => "0",
        "fields" => [
            "id",
            "amount_paid",
            "client_id",
            "client_name",
            "curr_balance",
            "last_payment_date",
            "notes",
            "payment_date",
            "balance_id",
            "balance_name",
            "currency",
            "type_id",
            "type_name",
            "user_name",
            "status",
            "direction"
        ]
    ];

    $res = $mc->call($requestArray, 'payment_get');

    foreach ($res as $line) {
        if ($line['client_name'] == $company_name) {
            if ($line['amount_paid'] < -500) {
                $result[] = $line;
            }
        };
    }

    return $result;
}

// get details of payment by id
function payment_get_by_id($mc, $payment_id, $date_from, $date_to) {
    $result = [];

    $requestArray = [
        "limit" => "10000",
        // "date_from" => "2020-11-05T00:00:00",
        // "date_to" => "2020-11-05T23:59:59",
        "id" => $payment_id,
        "date_from" => $date_from,
        "date_to" => $date_to,
        "sort_by" => [
            [
                "client_name" => "desc"
            ]
        ],
        "offset" => "0",
        "fields" => [
            "id",
            "amount_paid",
            "client_id",
            "client_name",
            "curr_balance",
            "last_payment_date",
            "notes",
            "payment_date",
            "balance_id",
            "balance_name",
            "currency",
            "type_id",
            "type_name",
            "user_name",
            "status",
            "direction"
        ]
    ];

    $res = $mc->call($requestArray, 'payment_get');

    return $res;
}

// add new payment for given company (you should know balance_id of that company)
function payment_add($mc, $balance_id, $amount) {
    $requestArray = [
        "amount_paid_vat" => $amount,
        "balance_id" => $balance_id,
        "notes" => "Add test payment",
        "direction" => "incoming",
        "date" => "2022-01-13T00:00:00",
        "type_id" => 1
    ];

    return $mc->call($requestArray, 'payment_add');
}

// delete payment by is's id
function delete_payment($mc, $payment_id) {
    $requestArray = [
        "id" => $payment_id,
        "notes" => "Failed payment"
    ];

    return $mc->call($requestArray, 'payment_del');
}

// change exist payment details (you can get unknown payment details by payment_get_by_id()) function ) 
function payment_change($mc, $payment_id, $amount, $direction, $balance_id, $notes, $date, $type_id) {
    $requestArray = [
        "id" => $payment_id,
        "amount_paid_vat" => $amount,
        "direction" => $direction,
        "balance_id" => $balance_id,
        "notes" => $notes,
        "date" => $date,
        "type_id" => $type_id
    ];

    return $mc->call($requestArray, 'payment_set');
}


// !!! MESSAGES !!!
// get message from CDR by message id
function get_sms_by_message_id($mc, $message_id) {
    $requestArray = [
        "time_zone" => "03:00",
        "start_date" => "2020-11-14T00:00:00",
        "end_date" => "2020-11-16T23:59:59",
        "type" => "individual",
        "message_id" => $message_id,
        "limit" => 20,
        "fields" => [
            "setup_time",
            "op_name",
            "tp_name",
            "clear_number",
            "country",
            "status",
            "state",
            "message_id"
        ]
    ];

    return $mc->call($requestArray, 'incoming_sms_get'); 
}
