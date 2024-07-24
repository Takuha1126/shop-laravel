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
<img width="1440" alt="スクリーンショット 2024-07-24 14 53 13" src="https://github.com/user-attachments/assets/863e0418-766f-405c-900b-0fc3347e84a4">
<img width="1440" alt="スクリーンショット 2024-07-24 14 53 22" src="https://github.com/user-attachments/assets/1fb84b5d-cc0b-45f8-ba8b-837ac025093a">
<img width="1440" alt="スクリーンショット 2024-07-24 14 53 30" src="https://github.com/user-attachments/assets/92ba0b31-4ce8-45fe-b8a0-011a5f682fd5">
<img width="1440" alt="スクリーンショット 2024-07-24 14 53 40" src="https://github.com/user-attachments/assets/d1de8660-26cf-4415-bbf9-80db97f29f12">


##ER図
<img width="1440" alt="スクリーンショット 2024-07-24 14 52 21" src="https://github.com/user-attachments/assets/04a58c95-9e00-46f0-a0d5-03ffa63d69e2">


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

composer install

.envファイルの作成
cp .env.example .env

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

手動でテストする
もしdemo_testというデータベースがなかったら作成する
docker-compose exec mysql mysql -u root -p
CREATE DATABASE demo_test;

テストを実行する
php artisan test
何かを修正しテストでエラーが出たら速やかに修正をお願いします。

ストレージ保存した画像を反映させるために、シンボリック作成
php artisan storage:link

テーブルの作成
php artisan migrate:refresh

シーダーで初期データを挿入
php artisan db:seed --class=CategoriesTableSeeder









