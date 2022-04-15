<?php
// ini_set("display_errors",1);
require('/mnt/d/code/work_scripts/mediacore_api/mediacore.php');

$mc = new MediaCore(11);

$result = get_company_by_name($mc, 'lv-voip');


foreach ($result as $company) {
    set_client_financial_settings($mc, $company, false);
}
