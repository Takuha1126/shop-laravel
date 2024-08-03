#アプリケーション名
フリマアプリ
商品を出品できる、購入できるアプリ

##作成目的
機能をわかりやすく誰でも使えるアプリを作成するため

##アプリケーションURL

##機能一覧
利用者
ログイン、会員登録、認証メール送信、ログアウト、商品一覧取得、商品一覧取得、商品詳細取得、商品お気に入り一覧取得、ユーザー情報取得、ユーザー出品商品取得、プロフィール変更、商品お気に入り追加、商品お気に入り削除、商品コメント追加、商品コメント削除、出品機能、カテゴリーでの検索、購入機能、配送先変更機能、支払い方法の選択・変更機能、ページネーション

管理者
ログイン、新規登録、ユーザー情報取得、ユーザー削除、コメント情報取得、コメント削除、利用者へメール送信、ページネーション

##使用技術
Laravel,php

##テーブル設計
<img width="1440" alt="スクリーンショット 2024-08-03 16 34 14" src="https://github.com/user-attachments/assets/2e7ec331-79e5-4469-960c-0d9fa225d3f8">
<img width="1440" alt="スクリーンショット 2024-08-03 16 34 23" src="https://github.com/user-attachments/assets/6725668b-0deb-4dfc-a725-6b798d3a012a">
<img width="1440" alt="スクリーンショット 2024-08-03 16 34 31" src="https://github.com/user-attachments/assets/0127e9f6-95af-427a-9521-68525a060dfe">
<img width="1440" alt="スクリーンショット 2024-08-03 16 34 38" src="https://github.com/user-attachments/assets/d30696bc-382f-4ffe-99e1-971c51c8201d">


##ER図
<img width="1440" alt="スクリーンショット 2024-08-03 16 34 49" src="https://github.com/user-attachments/assets/9ec6e4c1-d075-4a52-915f-e9926eb6a55d">


#環境構築
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

OneDriveから画像をダウンロード
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

テーブルの作成
docker-compose exec php bash
php artisan migrate:refresh

シーダーで初期データを挿入
php artisan db:seed --class=CategoriesTableSeeder









