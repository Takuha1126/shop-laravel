#アプリケーション名
フリマアプリ
商品を出品できる、購入できるアプリ

##作成目的
機能をわかりやすく誰でも使えるアプリを作成するため

##アプリケーションURL
http://13.114.27.64/

注意事項
商品を出品したら誤解で自分のを買わないように出品者のホームと検索欄には自分の商品は出ませんのでご注意ください。
商品を購入したらその商品はどのユーザーのホーム画面と検索ページには表示されなくなり、購入した人の購入欄だけにしか表示されないでご注意ください。
管理画面でユーザーを削除した場合、他のユーザーに購入をされていない商品とその削除したユーザーがしたコメントが削除されるのでご注意ください。

##機能一覧
利用者
ログイン、会員登録、認証メール送信、ログアウト、商品一覧取得、商品一覧取得、商品詳細取得、商品お気に入り一覧取得、ユーザー情報取得、ユーザー出品商品取得、プロフィール変更、商品お気に入り追加、商品お気に入り削除、商品コメント追加、商品コメント削除、出品機能、カテゴリーでの検索、購入機能、配送先変更機能、支払い方法の選択・変更機能、ページネーション

管理者
ログイン、新規登録、ユーザー情報取得、ユーザー削除、コメント情報取得、コメント削除、利用者へメール送信、ページネーション

##使用技術
Laravel,php

##テーブル設計
<img width="1440" alt="スクリーンショット 2024-08-17 17 46 46" src="https://github.com/user-attachments/assets/953d312c-f9d5-4089-8613-6eff033d3c98">
<img width="1440" alt="スクリーンショット 2024-08-17 17 46 55" src="https://github.com/user-attachments/assets/45505ccc-d5da-4ab8-acf6-8084c39f51bb">
<img width="1440" alt="スクリーンショット 2024-08-17 17 47 02" src="https://github.com/user-attachments/assets/d7236f6a-538f-40ec-9e69-5aebc2db1242">
<img width="1440" alt="スクリーンショット 2024-08-17 17 47 10" src="https://github.com/user-attachments/assets/f490ae49-01d9-4482-930a-d3675bf7c89f">
<img width="1440" alt="スクリーンショット 2024-08-17 17 47 19" src="https://github.com/user-attachments/assets/6b815a20-ce05-441a-8474-6ba2c149de3c">



##ER図
<img width="1440" alt="スクリーンショット 2024-08-17 17 47 40" src="https://github.com/user-attachments/assets/356b888d-e31b-4eb8-ae3b-431889b859f3">



#環境構築
このプロジェクトは、初心者が簡単に環境設定を行えるように設計されています。デフォルトでは、.envファイルを使用して設定を行います。以下の手順に従って、環境を構築してください。
開発環境を以下のGithubURLからクローンする
git clone git@github.com:Takuha1126/shop-laravel.git

ここではshop-laravelで行う
cd shop-laravel

Dockerで開発環境構築
docker-compose up -d --build

Laravelパッケージをインストール
docker-compose exec php bash

gdをインストールする(テスト用にインストールしておく)
apt-get update
apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install gd

インストールを実行
composer install

.envファイルの作成
cp .env.example .env

データベースの設定
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
このように書き換える

もしlaravel_dbというデータベースがなかったら作成する
docker-compose exec mysql mysql -u root -p
CREATE DATABASE laravel_db;

メール設定
各自でしてください
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-email-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="${APP_NAME}"

stripeの設定
各自でしてください
本プロジェクトはHTTPを使用しているため、テストキーを使用してください
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key

アプリケーションキーを作成する
php artisan key:generate

テスト用の.envを作る
.env.testingの作成
cp .env.example .env.testing

テスト用のデータベースの設定
DB_CONNECTION=mysql_test
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root

テスト用アプリケーションキーを作成する
php artisan key:generate --env=testing

手動でテストする
もしdemo_testというデータベースがなかったら作成する
docker-compose exec mysql mysql -u root -p
CREATE DATABASE demo_test;

テストを実行する
php artisan test
何かを修正しテストでエラーが出たら速やかに修正をお願いします。

ストレージ保存した画像を反映させるために、シンボリック作成
php artisan storage:link

シンボリックの中にディレクトリを作成
mkdir storage/app/public/images
mkdir storage/app/public/profile_images
ls storage/app/public/

ここにアクセスしOneDriveから画像をダウンロード
https://onedrive.live.com/?authkey=%21AMtSQRNgrYE61v8&cid=176BB5CC4F0A2C2F&id=176BB5CC4F0A2C2F%21122&parId=176BB5CC4F0A2C2F%21114&o=OneUp


これでコピー(ここではローカルマシンで操作)
cd src
cp ~/Downloads/logo.svg storage/app/public/images 

画像をダウンロードし、profile_imagesフォルダにdefault.jpgとして保存(拡張子はjpgを使用)
curl -o ~/Downloads/default.jpg https://assets.st-note.com/img/1676155437876-5NNUYKTjTE.png

ここで画像名をdefault.jpgという名前に変えて保存
cp ~/Downloads/default.jpg storage/app/public/profile_images 

画像があるか確認
ls storage/app/public/profile_images
ls storage/app/public/images

ここでprofile_imagesこれにはdefault.jpgこれがあり、imagesこれにはlogo.svgこれがあれば良い

テーブルの作成
docker-compose exec php bash
php artisan migrate:refresh

シーダーで初期データを挿入
php artisan db:seed --class=CategoriesTableSeeder









