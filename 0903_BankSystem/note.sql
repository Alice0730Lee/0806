做一個銀行的系統，有以下幾個功能 ：
1.可依據不同使用者操作 存/提款 （基本的防呆要有、可操作浮點數後4位）-- OK
2.可依據不同使用者查明細（可加選依時間查詢），一樣要分頁 -- OK

架構需求：
1.使用symfony 2.8 版 -- OK
2.資料庫設定檔 git上傳一份.dist 裡面設定的參數值是參考設定 --OK
3.分頁功能用資料庫語法實作 不要直接用套件 -- OK
4.需要不同步更新網頁 --OK

------------------------------------------------------------------------------------------------
測試碼指令
    ./bin/simple-phpunit -c app

測試碼+涵蓋率
    ./bin/simple-phpunit -c app --coverage-text

環境分為
    dev 開發
    test 測試
    prod 生產 ( 正式

測試用資料庫相關指令
    php app/console doctrine:schema:validate --env=test
    php app/console doctrine:database:create --env=test
    php app/console doctrine:schema:create --env=test
    php app/console doctrine:schema:update --force --env=test

------------------------------------------------------------------------------------------------
壓力測試
    ab -n 100 -c 10  -T application/x-www-form-urlencoded -p test.txt  "http://192.168.56.101/doCash"
    -n N N筆資料
    -c N 一次併發N筆

symfony command ( redis 同步到 MySQL
    php app/console syncSQL