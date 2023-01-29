import requests
import json
import os
import sys
import time
import mysql.connector
from mysql.connector import Error
sys.stdin.reconfigure(encoding='utf-8')
sys.stdout.reconfigure(encoding='utf-8')
def create_connection(host_name='162.55.49.187',port='4000', user_name='app', user_password='shohLa4chaim2Iequupheech2Airephe!', db_name='crm'):
    """Подключение к БД"""
    sql = None
    try:
        sql = mysql.connector.connect(
            host=host_name,
            port=port,
            user=user_name,
            passwd=user_password,
            database=db_name
        )
    except Error as e:
        print(f"The error '{e}' occurred")
    return sql

def sql_ex(query):
    """INSERT | DELETE | UPDATE запросы"""
    sql = create_connection()
    cursor = sql.cursor()
    try:
        cursor.execute(query)
        sql.commit()
        last_row = cursor.lastrowid
        cursor.close()
        return last_row
    except Error as e:
        print(f"The error '{e}' occurred")

def sql_query(query):
    """SELECT запросы"""
    sql = create_connection()
    cursor = sql.cursor()
    result = None
    try:
        cursor.execute(query)
        result = cursor.fetchall()
        cursor.close()
        return result
    except Error as e:
        print(f"The error '{e}' occurred")

def get_cookies_file(cookie):
    """Получаем и формируем куки"""
    jar = requests.cookies.RequestsCookieJar()
    cookies_json = json.loads(cookie)
    for i in cookies_json:
        jar.set(name= i['name'],value = i['value'],domain=i['domain'],path=i['path'],secure=i['secure'],expires=i['expires'])
    return jar

def get_app_info(app):
    profile = sql_query(f'SELECT `apps_bm_fb_id`,`apps_cookie_fb`,`apps_proxy_fb` FROM `apps` WHERE `apps_fb_id` = "{app}" AND `apps_status` = 2 AND `platform` = 1 LIMIT 1')[0]
    if profile[2] != '0':
        proxyDict = { 
            'https' : profile[2]
        }
    jar = get_cookies_file(profile[1]) # получаем куки
    """Получаем токен БМ"""
    url = f'https://business.facebook.com/apps/advertising_settings/{app}?business_id={profile[0]}'
    if profile[2] != '0':
        resp = requests.get(url, proxies=proxyDict, cookies=jar)
    else:
        resp = requests.get(url,cookies=jar)
    token_bm = resp.text.split('"init",[{"accessToken":"')[1].split('"')[0]
    """Получаем статусы рекламных кабинетов"""
    variables = '{"appID":"'+app+'"}'
    url = f'https://graph.facebook.com/graphql?variables={variables}&doc_id=5628600177156267&access_token={token_bm}&method=post'
    if profile[2] != '0':
        resp = requests.get(url, proxies=proxyDict, cookies=jar).json()['data']['node']['app_skan_settings']['ad_accounts_for_skan_campaign_limits']
    else:    
        resp = requests.get(url,cookies=jar).json()['data']['node']['app_skan_settings']['ad_accounts_for_skan_campaign_limits']
    data = []
    for i in resp:
        if i['ad_account_is_disabled']:
            status = 'Отключен'
        else:
            status = 'Активен'
        if i['ad_account_is_pending_removal']:
            delete = 'На удаление'
        else:
            delete = 'Можно удалять'
        if i['has_ad_account_level_permission']:
            level_permission = 'Да'
        else:
            level_permission = 'Нет'
        data.append(f"{i['ad_account_id']},{i['ad_account_name']},{i['num_of_active_campaigns']},{i['num_of_cooldown_campaigns']},{level_permission},{delete},{status}")
    return '|'.join(data)
# print(get_app_info('1378252976025108'))

def add_cabs_developer(app,cabs,jar,proxy):
    """Получаем целевую страницу (Дополнительно) с уникальными токенами"""
    url = f'https://developers.facebook.com/apps/{app}/settings/advanced/'
    if proxy != '0':
        proxyDict = { 
            'https' : proxy
        }
    if proxy != '0':
        resp = requests.get(url, proxies=proxyDict, cookies=jar).content
    else:
        resp = requests.get(url,cookies=jar).content
    #######################################################
    """Подготавливаем и отправляем запрос (добавление кабинетов) на сервер"""
    url = f'https://developers.facebook.com/x/apps/{app}/settings/advanced/save/'
    data_set = {}
    index = 0
    for i in cabs:
        data_set[f'advertiser_account_ids[{index}]'] = i
        index += 1
    data_set['lsd'] = str(resp).split('["LSD",[],{"token":"')[1].split('"}')[0]
    data_set['fb_dtsg'] = str(resp).split('["DTSGInitialData",[],{"token":"')[1].split('"}')[0]
    if proxy != '0':
        resp = requests.post(url, proxies=proxyDict, cookies=jar,data = data_set)
    else:
        resp = requests.post(url,cookies=jar,data = data_set)
    if resp.text == '':
        return True
    else:
        return False
# print(add_cabs_developer('1378252976025108','699726097832148'))
def change_cabs(app,cabs,new_cabs=False):
    # try:
    profile = sql_query(f'SELECT `apps_bm_fb_id`,`apps_cookie_fb`,`apps_proxy_fb` FROM `apps` WHERE `apps_fb_id` = "{app}" AND `apps_status` = 2 AND `platform` = 1 LIMIT 1')[0]
    if profile[2] != '0':
        proxyDict = { 
            'https' : profile[2]
        }
    jar = get_cookies_file(profile[1])
    url = f'https://business.facebook.com/apps/advertising_settings/{app}?business_id={profile[0]}'
    if profile[2] != '0':
        resp = requests.get(url, proxies=proxyDict, cookies=jar)
    else:
        resp = requests.get(url, cookies=jar)
    acc_id = resp.text.split('"actorID":"')[1].split('"')[0]
    token_bm = resp.text.split('"init",[{"accessToken":"')[1].split('"')[0]
    if new_cabs != False:
        cabs = str(cabs).split(',')
        cabs.append(new_cabs)
        if add_cabs_developer(app,cabs,jar,profile[2]):
            time.sleep(10)
            variables = '{"input":{"client_mutation_id":"","actor_id":"'+acc_id+'","business_id":"'+profile[0]+'","application_id":"'+app+'","ad_account_ids":'+str(cabs)+'}}'
        else:
            return False
    else:
        cabs = str(cabs).split(',')
        variables = '{"input":{"client_mutation_id":"","actor_id":"'+acc_id+'","business_id":"'+profile[0]+'","application_id":"'+app+'","ad_account_ids":'+str(cabs)+'}}'
    url = f'https://graph.facebook.com/graphql?variables={variables}&doc_id=5236966186386178&access_token={token_bm}&method=post&debug=all'
    print(url)
    if profile[2] != '0':
        resp = requests.get(url, proxies=proxyDict, cookies=jar).json()
        return resp
    else:
        resp = requests.get(url, cookies=jar).json()['data']['xfb_ad_app_skan_settings_update']['application']['app_skan_settings']['ad_accounts_for_skan_campaign_limits']
    data = []
    for i in resp:
        if i['ad_account_is_disabled']:
            status = 'Отключен'
        else:
            status = 'Активен'
        if i['ad_account_is_pending_removal']:
            delete = 'На удаление'
        else:
            delete = 'Можно удалять'
        if i['has_ad_account_level_permission']:
            level_permission = 'Да'
        else:
            level_permission = 'Нет'
        data.append(f"{i['ad_account_id']},{i['ad_account_name']},{i['num_of_active_campaigns']},{i['num_of_cooldown_campaigns']},{level_permission},{delete},{status}")
    return '|'.join(data)
    # except:
    #     return False
if sys.argv[1] == "get_app_info":
    print(get_app_info(str(sys.argv[2])))
elif sys.argv[1] == "change_cabs":
    # print(len(sys.argv))
    if len(sys.argv) == 4:
        print(change_cabs(str(sys.argv[2]),str(sys.argv[3])))
    else:
        print(change_cabs(str(sys.argv[2]),str(sys.argv[3]),str(sys.argv[4])))
else:
    print(False)
