<?php
include __DIR__."/config.php";
$sharing_ass = false;
$load_new = $pdo->prepare('SELECT * FROM `sharing_task` WHERE `sharing_task_app_id` = (SELECT `id` FROM `apps` WHERE `apps_fb_id` = ?) AND sharing_task_status = 1');
$load_new->execute(array($_GET['app']));
$new_acc_count = 1;
$accs_id_result = array();
$accs_id_bd_result = array();
while($row = $load_new->fetch()){
    if($new_acc_count < 100){
        $id_fb_new = explode("\n", $row['sharing_task_acc_ids']);
        if((count($id_fb_new)+$new_acc_count) < 100){
            foreach($id_fb_new as $value){
                if($value != ''){
                    $value = trim($value);
                    if (ctype_digit($value)) {
                        $accs_id_result[] = $value;
                        $new_acc_count++;
                        $sharing_ass = true;
                    }
                }
            }
            $accs_id_result = array_unique($accs_id_result);
            $accs_id_bd_result[] = $row['id'];
        } else {
            break;
        }
    } else {
        break;
    }
}
if($new_acc_count < 100 && $sharing_ass == true){
    $load_old = $pdo->prepare('SELECT * FROM `sharing_task` WHERE `sharing_task_app_id` = (SELECT `id` FROM `apps` WHERE `apps_fb_id` = ?) AND sharing_task_status = 2 ORDER BY `id` DESC LIMIT 100');
    $load_old->execute(array($_GET['app']));
    while($row = $load_old->fetch()){
        if($new_acc_count < 100){
            $id_fb_old = explode("\n", $row['sharing_task_acc_ids']);
            foreach(array_reverse($id_fb_old) as $value){
                if($new_acc_count < 100){
                    if($value != ''){
                        $value = trim($value);
                        if (ctype_digit($value)) {
                            $accs_id_result[] = $value;
                            $new_acc_count++;
                        }
                    }
                } else {
                    break;
                }
            }
        } else {
            break;
        }
    }
}
$accs_id_result = array_unique($accs_id_result);
if($sharing_ass == true){
    echo implode(',',$accs_id_bd_result).'|||'.'<input type="hidden" name="advertiser_account_ids[]" value="'.implode('"><input type="hidden" name="advertiser_account_ids[]" value="',$accs_id_result).'">';
} else {
    echo 0;
}
?>