<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'titles' => [
        'role' => '角色',
        'roles' => '角色',
        'user' => '使用者',
        'manager' => '管理者',
        'advertisement' => '廣告',
        'advertisementcount' => '廣告統計',
        'seo' => 'SEO',
        'usersite' => '用戶對應多站',
        'icon' => '圖標',
        'iconcount' => '圖標統計',
        'share' => '分享',
        'sharecount' => '分享統計',
        'visitoractivity' => '用戶活躍數圖表',
        'newsticker' => '跑馬燈管理',
        'site' => '多站管理',
        'retentionrate' => '用戶留存率',
        'book' => '小說管理',
        'bookrecommend' => '小說推薦管理',
        'bookcount' => '小說類型點擊圖表',
    ],

    'list' => '列表',
    'index' => '列表',
    'store' => '儲存',
    'create' => '新增',
    'edit' => '編輯',
    'delete' => '刪除',
    'expire' => '狀態',
    'recommend' => '推薦',

    // -------------------------------------------------------------------
    // default
    'id' => '序號',
    'name' => '名稱',
    'content' => '內容',
    'image' => '圖片',
    'image_profile_dec' => '圖片(不上傳就不更新，只接受圖片檔案(png jpeg gif))',
    'start_time' => '開始時間',
    'end_time' => '結束時間',
    'buyer' => '購買人',
    'buyer_msg' => '請輸入廣告購買人名稱',
    'attribution_web' => '歸屬網站',
    'unattribution_web' => '無法歸屬',
    'action' => '動作',
    'pre_page' => '上一頁',
    'next_page' => '下一頁',
    'submit' => '送出',
    'take_up' => '上架',
    'take_down' => '下架',
    'take_msg' => '上下架',
    'take_up_down_msg' => '上架需在有效時間內才有用，下架是任意時間都有用',
    'take_up_down_info' => '上下架情況(任意時間均可下架，上架需在結束時間以前)',
    'choose_file' => '選擇檔案',
    'place' => '位置',
    'account' => '帳號',
    'enable_user' => '啟用',
    'account_def' => 'name',
    'pass_def' => 'password',
    'web_id' => '網站序號',
    'web_name' => '網站名稱',
    'web_url' => '網址',
    'web_name_def' => '請輸入網站名稱',
    'web_url_def' => '請輸入網址',
    'web_connect_url' => '連結網址',
    'daily_active_users' => '每日用戶活躍數',
    'daily_hit_users' => '每日用戶活躍數',
    'sort' => '排序',
    'sort_msg' => '排序(由左自右由上自下，數字越小越前面，最小為0，最大為225)',
    'status' => '狀態',
    'status_one' => '未完成',
    'status_second' => '已完成',
    'change_status_fail' => '改為未完成',
    'change_status_true' => '改為已完成',
    'ip' => 'IP位址',
    'ip_msg_def' => '請輸入ip',
    'table_page_info' => '顯示第 :page 頁 共 :total 筆 共 :last_page 頁 每頁顯示 :step 筆',
    'remind' => '請小心操作',
    'click_time' => '點擊時間',
    'click_count' => '點擊數',
    'googleAuth'=> 'Googlg Auth 驗證 ',
    'role'=> '角色',
    'isopen' => ' GOOGLE AUTH驗證',
    'book_type' => '小說類型',
    // -------------------------------------------------------------------
    // left box
    'leftbox' => [
        'tittle' => '入口網站後台控制',
        'manager' => '使用者管理',
        'role' => '角色管理',
        'site' => '多站管理',
        'user_site' => '用戶對應多站管理',
        'book' => '小說管理',
        'book_recommend' => '小說推薦管理',
        'book_count' => '小說類型點擊圖表',
    ],
    // -------------------------------------------------------------------
    // frontend
    'frontend' => [
        'title' => '阿古小說',
        'home' => '首 页',
        'library' => '书 库',
        'rank' => '排 行',
        'complete' => '全 本',
        'keywords' => '手机看小说,免费小说，玄幻小说,都市小说,言情小说,无弹窗小说网',
        'description' => '阿古小說提供免费免费小说,言情小说,玄幻小说,言情小说,都市小说,网游小说,军事小说等各类最新热门小说免费无弹窗无广告全文阅读,分享最新热门小说，共享小说阅读快乐！',
        'popular' => '热门小说推荐',
        'study' => '阅读排行榜',
        'book_create' => '最新小说',
        'book_update' => '最近更新',
        'book_name' => '小說名',
        'author' => '作者名',
        'search' => '搜 索',
        'word_num' => '字数',
        'read' => '阅读量',
        'introduction' => '简介',
        'update_to' => '更新到',
        'latest_chapter' => '最新章节',
        'update_time' => '更新时间',
        'light_open' => '开灯',
        'light_close' => '关灯',
        'front_add' => '字体+',
        'front_reduce' => '字体-',
        'chapter_pre' => '上一章',
        'chapter_next' => '下一章',
        'directory' => '目录',
        'hint' => '温馨提示：按 回车[Enter]键 返回书目，按 ←键 返回上一页， 按 →键 进入下一页，加入书签方便您下次继续阅读。',
        'total_hit_rank' => '总点击榜',
        'month_hit_rank' => '月点击榜',
        'week_hit_rank' => '周点击榜',
        'latest_storage_rank' => '最新入库',
    ],
    // -------------------------------------------------------------------
    // UserController
    'error_login_msg' => '帳密有誤，請重新登入！',
    // -------------------------------------------------------------------
    // ManagerController
    'manager_control' => [
        'manager_control' => '管理者',
        'manager_insert' => '新增管理者',
        'manager_update' => '更新管理者',
        'manager_acc' => '管理者帳號',
        'manager_pass' => '密碼',
        'GoogleAtuh'=> 'GOOGLE 驗證碼',
    ],
    // -------------------------------------------------------------------
    // RoleController
    'role_control' => [
        'role' => '角色',
        'role_insert' => '新增角色',
        'role_update' => '更新角色',
        'role_name' => '角色名稱',
        'role_permission' => '角色權限',
    ],
    // -------------------------------------------------------------------
    // SiteController
    'site_control' => [
        'site_control' => '多站管理',
        'site_insert' => '新增網站',
        'site_update' => '更新網站',
    ],
    // -------------------------------------------------------------------
    // UserSiteController
    'usersite_control' => [
        'usersite_control' => '用戶對應多站管理',
        'usersite_insert' => '新增用戶對應多站',
        'usersite_update' => '更新用戶對應多站',
        'user_id' => '用戶序號',
        'user_name' => '用戶名稱',
        'user_id_def' => '請輸入用戶序號',
    ],
    // -------------------------------------------------------------------
    // BookController
    'book_control' => [
        'book_control' => '小說管理',
        'book_type' => '類型',
        'book_name' => '名稱',
        'book_auther' => '作者',
        'book_status' => '狀態',
        'book_word_num' => '字數',
        'book_img' => '封面',
        'book_latest_chapter' => '最新章節',
        'book_update_time' => '更新時間',
        'book_update' => '更新書籍資訊',
        'book_name_def' => '請輸入名稱',
        'book_auther_def' => '請輸入作者',
        'book_recommend_create_time' => '推薦時間',
        'book_recommend_control' => '小說推薦管理',
        
    ],
    // -------------------------------------------------------------------
    // BookCountController
    'bookCount_control' => [
        'bookCount_control' => '小說類型點擊圖表',
    ],
];
