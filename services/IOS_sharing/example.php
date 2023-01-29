<?php
# 1 название функции (get_app_info,change_cabs)
# 2 app id (FB) из apps.apps_fb_id
# 3 старые кабинеты через ,
# 4 новый кабиент строка

# Получаем инфу из кабинета
# 1(Название функции) 2(apps.apps_fb_id)
$result = system('python3 sharing.py get_app_info 1378252976025108', $retval);

# Удаляем кабинет
# 1(Название функции) 2(apps.apps_fb_id) 3(старые кабинеты через ,)
// $result = system('python3 sharing.py change_cabs 1378252976025108 469611714962459,977282446322762', $retval);

# Добавляем новый кабинет
# 1(Название функции) 2(apps.apps_fb_id) 3(старые кабинеты через ,) 4(новый кабиент)
// $result = system('python3 sharing.py change_cabs 1378252976025108 469611714962459 977282446322762', $retval);

# Выводим ответ
if ($result != 'False'){
    foreach (explode('|',$result) as $value){
        print_r(str_replace(',',' ',$value)."\n");
    }
} else {
    print_r('Ошибка!');
}

?>