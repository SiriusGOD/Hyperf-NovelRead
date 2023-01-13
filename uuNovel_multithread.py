import requests     # 发送请求
from bs4 import BeautifulSoup
from my_fake_useragent import UserAgent
import os
import concurrent.futures
from threading import current_thread
import threading
import time
import pymysql
import logging
import urllib.parse
import random

def connectDb(dbName):
    try:
        mysqldb = pymysql.connect(
                host="192.168.1.125",
                user="root",
                passwd="root",
                database=dbName)
        return mysqldb
    except Exception as e:
        logging.error('Fail to connection mysql {}'.format(str(e)))
    return None

def htmlspecialchars(text):
    return (
        text.replace("&", "&amp;").
        replace('"', "&quot;").
        replace("<", "&lt;").
        replace(">", "&gt;").
        replace("'", "“")
    )

def scrape(links):

    thread_id = f"{current_thread().name}"[-1]
    # print(thread_id)
    lock = threading.Lock()

    # 獲取該類型的小說總頁數
    type_data = requests.get(url=links, headers=headers).text
    type_book = BeautifulSoup(type_data, 'lxml')  # 解析器接手
    type_book_total_num = type_book.select('.last')[0].getText()
    type = (type_book.find_all('h2')[0].getText()).replace("小说分类 - ","")
    try:
        locals()['conn'+str(thread_id)] = connectDb('crawlers')
        locals()['conn'+str(thread_id)].begin()
        locals()['cur'+str(thread_id)] = None
        if locals()['conn'+str(thread_id)] is not None:
            locals()['cur'+str(thread_id)] = locals()['conn'+str(thread_id)].cursor()  
        # 獲取type_id
        sql = "select book_type_id from book_type where type_name = '" + type + "'"
        # print("SQL: " + sql)
        locals()['cur'+str(thread_id)].execute(sql)
        locals()['result_book_type'+str(thread_id)] = locals()['cur'+str(thread_id)].fetchone()
        # print(locals()['result_book_type'+str(thread_id)])
        type_id = locals()['result_book_type'+str(thread_id)][0]
        # print(type_id)

        # locals()['cur'+str(thread_id)].close ()
        locals()['conn'+str(thread_id)].commit()
    except Exception as e:
        logging.error('Fail to 獲取type_id  {}'.format(str(e)))

    print("//-----------------------------------------------------")
    print("小說類型: " + type)
    print("小說頁數: " + type_book_total_num)
    print("類型網址: " + links)
    print("//-----------------------------------------------------")
    # lock.release()

    # 獲取頁數網址
    type_page_url = links.split('_')
    count = 1
    while count <= int(type_book_total_num):
        page = str(count)
        # print(type_page_url[0] + '_' + type_page_url[1] + '_' + page + '.html')
        
        # 撈取該頁30本書的連結
        if count > 1:
            type_url = type_page_url[0] + '_' + type_page_url[1] + '_' + page + '.html'
            type_data = requests.get(url=type_url, headers=headers).text
            type_book = BeautifulSoup(type_data, 'lxml')  # 解析器接手
        # print("//-----------------------------------------------------")
        # print('小說名稱: ' + type_book.find_all('h4')[0].getText())
        # print(type_book.find_all('h4')[0].find_next('a')['href'])
        # print(type_book.find_all("div", {"class": "author"}))

        ## 小說名稱
        type_book_name = type_book.find_all('h4')[0].getText()
        type_book_url = type_book.find_all('h4')[0].find_next('a')['href']

        # 進入該本書的詳細頁
        type_book_data = requests.get(url=type_book_url, headers=headers).text
        type_book_soup = BeautifulSoup(type_book_data, 'lxml')  # 解析器接手

        ## 封面圖
        img_result = type_book_soup.find_all("img", {"class": "thumbnail"})
        image_links = img_result[0].get("src")
        img = requests.get(image_links)
        img_type = urllib.parse.quote_plus(type)
        img_type_book_name = urllib.parse.quote_plus(type_book_name)
        if not os.path.exists("public/book/"):
                os.mkdir("public/book/")  # 建立book資料夾
        if not os.path.exists("public/book/" + type):
                os.mkdir("public/book/" + type)  # 建立資料夾
        img_path = "public/book/" + type + '/' + type_book_name + ".jpg"
        db_img_path = "/book/" + img_type + '/' + img_type_book_name + ".jpg"
        with open(img_path, "wb") as file:  # 開啟資料夾及命名圖片檔
            file.write(img.content)  # 寫入圖片的二進位碼
        # print("圖片路徑: " + image_links)

        bookinfo = type_book_soup.select('.bookinfo')
        print("//-----------------------------------------------------")
        print('小說名稱: ' + type_book_name)
        ## 作者
        author = bookinfo[0].select('.booktag')[0].select('.red')[0].getText()
        print('作者: ' + author)
        ## 小說狀態
        status = bookinfo[0].select('.booktag')[0].select('.red')[1].getText()
        print('小說狀態: ' + status)
        ## 字數
        word_num = bookinfo[0].select('.booktag')[0].select('.blue')[0].getText().replace("字","")
        print('字數: ' + word_num)
        ## 小說標籤
        print('小說標籤: ' + bookinfo[0].select('.booktag')[0].select('.blue')[1].getText())
        ## 簡介
        introduction = bookinfo[0].select('.bookintro')[0].getText()
        print('簡介: ' + introduction)
        ## 最新章節
        latest_chapter = bookinfo[0].select('.bookchapter')[0].getText()
        print('最新章節: ' + latest_chapter)
        ## 更新時間
        update_time = (bookinfo[0].select('.booktime')[0].getText()).replace("更新时间：","")
        print('更新時間: ' + update_time)
        print("//-----------------------------------------------------")

        try:
            # 確認小說是否有新增過
            sql = "select book_id from book where type = " + str(type_id) + " and author = '" + author + "' and book_name = '" + type_book_name + "'"
            locals()['cur'+str(thread_id)].execute(sql)
            res_book = locals()['cur'+str(thread_id)].fetchone()
            # print("Res Book: " + str(res_book))
            if res_book is None:
                sql = "insert into book(type, book_name, author, status, word_num, introduction, cover_img, latest_chapter, update_time) values (" + str(type_id) + ", '" + type_book_name + "','" + author + "','" + status + "'," + word_num + ",'" + introduction + "','" + db_img_path + "','" + latest_chapter + "','" + update_time + "')"
                # print("Res Book SQL: " + sql)
                locals()['cur'+str(thread_id)].execute(sql)
                # sql = "SELECT LAST_INSERT_ID()"
                # locals()['cur'+str(thread_id)].execute(sql)
                # result_book_id = locals()['cur'+str(thread_id)].fetchone()
                # print(result_book_id[0])
                locals()['conn'+str(thread_id)].commit()
            else:
                # # 撈取book_id
                # sql = "select book_id from book where type = " + str(type_id) + " and author = '" + author + "' and book_name = '" + type_book_name + "'"
                # locals()['cur'+str(thread_id)].execute(sql)
                # res_book = locals()['cur'+str(thread_id)].fetchone()
                book_id = res_book[0]
                # 確認是否有新的章節
                # sql = "select latest_chapter from book where book_id = " + str(book_id)
                sql = "SELECT chapter FROM book_content where book_id = " + str(book_id) + " order by book_content_id desc limit 1"
                locals()['cur'+str(thread_id)].execute(sql)
                res_book_latest_chapter = locals()['cur'+str(thread_id)].fetchone()
                sql_latest_chapter = res_book_latest_chapter[0]
                if sql_latest_chapter != latest_chapter:
                    sql = "update book set latest_chapter = '" + latest_chapter + "', update_time = '" + update_time + "' where book_id = " + str(book_id)
                    locals()['cur'+str(thread_id)].execute(sql)
                    locals()['conn'+str(thread_id)].commit()
                else:
                    print("沒有新的章節 " + type_book_name + "book_id: " + str(book_id))
                    print("//-----------------------------------------------------")
                    count =  count + 1
                    time.sleep(2)
                    continue
        except Exception as e:
            logging.error('Fail to 確認小說是否有新增過  {}'.format(str(e)))


        # 獲取小說目錄與連結
        results = type_book_soup.find_all('dd')
        chapter_num = 1
        delay_choices = [1,2,3,4,5]  #延遲的秒數
        for result in results: 
            #延遲
            delay = random.choice(delay_choices)  #隨機選取秒數
            print("延遲: " + str(delay) + "秒")
            time.sleep(delay)  #延遲

            # 繁體網址
            # book_url = domain + result.find_next('a')['href']
            # 簡體網址
            book_url = result.find_next('a')['href']
            # print('Domain: ' + domain)
            # print('Result: ' + result.find_next('a')['href'])
            # print('Book URL: ' + book_url)
            book_html = requests.get(url=book_url, headers=headers).text
            book_data = BeautifulSoup(book_html, 'lxml')  # 解析器接手
            # book_results = book_data.select('.read')
            book_results = book_data.find('p', {"class": "readcotent bbb font-normal"})
            
            ## 章節標題
            chapter = result.find_next('a').getText()
            print("章節標題: " + chapter)
            
            # print(book_results[0].getText())

            ## 章節內容
            # if not os.path.exists('novel/'+ type):
            #     os.mkdir('novel/'+ type)  # 建立資料夾
            # with open('novel/'+ type + '/' + type_book_name + '.txt', mode='a+', encoding='utf-8') as f:
            #     f.write(book_results[0].getText())
            try:
                # 撈取book_id
                sql = "select book_id from book where type = " + str(type_id) + " and author = '" + author + "' and book_name = '" + type_book_name + "'"
                locals()['cur'+str(thread_id)].execute(sql)
                res_book = locals()['cur'+str(thread_id)].fetchone()
                book_id = res_book[0]
                # print("撈取book_id")
                # print(book_id)
                
                # 確認是否有新增過
                # content = book_results[0].getText()
                content = htmlspecialchars(str(book_results))
                sql = "select book_content_id from book_content where book_id = " + str(book_id) +  " and chapter = '" + chapter + "'"
                locals()['cur'+str(thread_id)].execute(sql)
                res_book_content = locals()['cur'+str(thread_id)].fetchone()
                # print("Res Book: " + str(res_book_content))
                if res_book_content is None:
                    sql = "insert into book_content(book_id, chapter_num, chapter, content) values ("+ str(book_id) + "," + str(chapter_num) + ",'" + chapter + "','" + content +  "')"
                    # print("Res Book SQL: " + sql)
                    locals()['cur'+str(thread_id)].execute(sql)
                    locals()['conn'+str(thread_id)].commit()
            except Exception as e:
                logging.error('Fail to 新增章節內容  {}'.format(str(e)))
            chapter_num = chapter_num + 1

                
        count =  count + 1
        print("//-----------------------------------------------------")


    time.sleep(2)




# 偽裝
ua = UserAgent(family='chrome')
res = ua.random()
headers = {
    'cookie': '_ga=GA1.2.1340036773.1672037715; current-font=1; currentFontString=font-large; _gid=GA1.2.200395686.1672711393',
    'referer': 'https://www.baidu.com/',
    'user-agent': res,
}
domain = 'https://cn.uukanshu.cc'

 # 書庫
url = 'https://cn.uukanshu.cc/class_1_1.html'
html_data = requests.get(url=url, headers=headers).text
soup = BeautifulSoup(html_data, 'lxml')  # 解析器接手


# DB 連線
conn = connectDb('crawlers')
cur = None
if conn is not None:
    cur = conn.cursor() 

links = list() 
results = soup.find_all('li')
for result in results:
    links.append(domain + result.find_next('a')['href'])
    type = result.find_next('a').getText()
    type_url = domain + result.find_next('a')['href']

    if type == "其他类型":
        type = "其他小说"

    ## 小說類型 新增進DB
    sql = "select book_type_id from book_type where type_name = '" + type + "'"
    cur.execute(sql)
    result_one = cur.fetchone()
    if result_one is None:
        sql = "insert into book_type(type_name) values ('" + type +"')"
        cur.execute(sql)
        conn.commit()

    # print(result_one)
    # print(type)
    # print(type_url)
    

print(links)
start_time = time.time()
# 同時建立及啟用10個執行緒
with concurrent.futures.ThreadPoolExecutor(max_workers=10) as executor:
    executor.map(scrape, links)
# with concurrent.futures.ProcessPoolExecutor(max_workers=10) as executor:
#     executor.map(scrape, links, chunksize=5)
    
end_time = time.time()
print(f"{end_time - start_time} 秒爬取")