<?php
include_once(__DIR__."/config.php");
$botJSON = __DIR__.'/bot_push_request.json';

$loadInfoDB = $pdo->prepare('SELECT * FROM request_accounts_buyer WHERE `status_id` = 1');
$loadInfoDB->execute(array());
$loadInfoDBRow = $loadInfoDB->fetchAll();
$requestFor = 0;
foreach($loadInfoDBRow as $value){
    $requestFor++;
};
if ($requestFor > 0){
    $taskList = json_decode(file_get_contents($botJSON),TRUE);
    $pushSelect = false;  
    foreach($loadInfoDBRow as $row){
        $requestInfoJson = 0;
        foreach ($taskList as $value){
            if ($value['id'] == $row['id']){
                $requestInfoJson = 1;
                break;
            }
        }
        if ($requestInfoJson == 0){
            $taskList[] = array('id'=>$row['id']);
            file_put_contents($botJSON,json_encode($taskList));
            $pushSelect = true;
        }
    }
    if ($pushSelect == true){
        if ($requestFor == 1){$textSendBot = "Request @havityan";} else {$textSendBot = "Request @havityan";}
        $urlTG = 'https://api.telegram.org/bot1624183859:AAGpH000mXgBdHlya2PuZn2evcJ4EuH2xgc/sendMessage?chat_id=-1001466036275&text='.$requestFor.' -- '.$textSendBot.'';
        file_get_contents($urlTG);
    }  
}


?>