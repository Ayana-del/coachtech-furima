# coachtech-furima

coachtech フリマアプリの開発プロジェクトです。

## アプリケーションの概要

本アプリケーションは、ユーザーが商品を自ら出品・購入できるフリマプラットフォームです。

## 使用技術（実行環境）

・Backend: PHP 8.1/ Laravel 8.x  
・Frontend: Blade / CSS  
・nfrastructure: Docker / Docker Compose  
・Web Server: Nginx 1.21.1  
・Database: MySQL 8.0.35  
・Tool: phpMyAdmin / MailHog  
・Laravel Fortify  
・Blade  
・CSS

## 環境構築手順

クローン後、以下の手順で開発環境を起動できます。

### 1.リポジトリのクローン

```bash
git clone https://github.com/Ayana-del/coachtech-furima.git
```

```bash
cd coachtech-furima
```

### 2.Docker コンテナの構築・起動

```bash
docker-compose up -d --build
```

### 3.依存パッケージのインストール

```bash
cp src/.env.example src/.env
```

```bash
docker-compose exec php php artisan key:generate
```

### 5.マイグレーションの実行

```bash
docker-compose exec php php artisan migrate
```

## URL(開発環境)

・アプリケーション本体: http://localhost  
・phpMyAdmin (DB 管理): http://localhost:8080  
　・サーバー名：mysql  
　・ユーザー名：laravel_user  
　・パスワード：laravel_pass

## ER 図

![ER図](./er-diagram.drawio.png)

## データベース設計　

本プロジェクトは、機能要件に基づき、９つのテーブルで構成されています。  
| テーブル名 | 役割 | 関連する機能要件 |  
| ---- | ---- | ---- |  
| users | 認証の基本情報 | US001, US002 |  
| profile | ユーザー詳細 | US008 (FN027) |  
| items | 出品情報の詳細情報 | US004, US005, US009 |  
| categories | 商品カテゴリ名 | US009 (FN028) |  
| category_item | 商品とカテゴリの中間テーブル | US009 (FN028: 複数選択) |  
| comments | 商品へのコメント保存 | US005 (FN020) |  
| likes | 商品への「いいね」保存 | US005 (FN018) |  
| orders | 商品の購入確定情報・配送先 | US006 (FN022, FN024) |

## モデル・リレーション定義

Eloquent モデルにおける主要なリレーションシップを以下に定義しています。

### User 　モデル

・hasOne(Profile):1 つのプロフィールを持つ  
・hasMany(Item):複数の商品を出品する  
・hasMany(Comment):複数のコメントを投稿する  
・hasMany(Like):複数のいいねを行う  
・belongsToMany(Item,Like):マイリスト表示用。いいねした全商品を取得  
・hasMany(Order):複数の注文履歴を持つ。

### Profile モデル

・belongTo(User):特定のユーザーに属する

### Item モデル

・belongsTo(User):特定のユーザーによって出品される  
・belongsToMany(Category):複数のカテゴリに属する(中間テーブルを経由)  
・hasMany(Comment):複数のコメントを持つ  
・hasMany(Like):複数のいいねを持つ  
・hasOne(Order):一つの注文によって購入される(購入済み判定用)

### Category モデル

・belongsToMAny(Item):複数の商品に紐づく

### Comment モデル

・belongsTo(User):誰がコメントをしたか  
・belongsTo(Item):どの商品へのコメントか

### Like モデル

・belongsTo(User):誰が「いいね」したか  
・belongsTo(Item):どの商品へのコメントか

### Order モデル

・belongsTo(user):誰が購入したか  
・belongsTo(Item):どの商品を購入したか  

## 会員登録・メール認証機能の実装  

### 1.実装済み機能  
  
・ユーザー登録：ユーザー名、メールアドレス、パスワードによる新規アカウント作成。  
・バリデーション：FormRequest を使用したサーバーサイドでの入力チェック。  
・メール認証：新規登録時の本人確認メール送信機能追加。  
・レスポンシブ・デザイン：PC(1400px - 1540px)とタブレット(768px - 850px)に対応。  
  
### 2.実装ファイル一覧  

| ファイルパス                            | 役割・実装内容                           |
| --------------------------------------- | ---------------------------------------- |
| app/Http/Requests/RegisterRequest.php   | バリデーション定義ルール・メッセージ定義 |
| resources/views/auth/register.blade.php | 会員登録画面                             |
| public/css/auth/auth.css            | 会員登録画面・ログイン画面共通デザイン |
  
### 3.ユーザー登録・遷移フロー  

・登録後の自動遷移: 会員登録成功後、スムーズにプロフィール設定（マイページ）へ誘導するため、Fortify の設定により遷移先を /mypage/profile にカスタマイズ済みです。  
  
## ログイン・認証制限機能の実装  
  
### 1.実装済み機能  
・ユーザー認証：登録済みメールアドレスとパスワードによる認証処理。  
・バリデーション：LoginRequestによる入力チェックおよび日本語メッセージの返却。  
・未認証リダイレクト：Authenticateミドルウェアにより、未ログイン状態での保護ページアクセスをログイン画面へ強制誘導。  
・画面間遷移制限：ログイン画面と会員登録画面を相互に遷移可能な動線を構築。  
  
### 2.実装ファイル  
| ファイルパス                            | 役割・実装内容                           |
| --------------------------------------- | ---------------------------------------- |
| app/Http/Requests/LoginRequest.php | ログイン専用のバリデーションルール・メッセージ定義 |
| resources/views/auth/login.blade.php | ログイン画面 |
| public/css/auth/auth.css | 会員登録画面・ログイン画面共通デザイ |
  
### 3.機能要件に基づく挙動  
・アクセス制限：認証が必要なページへの未ログインアクセスを検知した際、自動的にログイン画面へ戻します。  
・エラーフィードバック：入力不備がある場合、設計書通りの日本語メッセージを表示し、再入力を促します。  
・認証後の遷移：認証成功後、ユーザーを適切なリダイレクト先へ安全に誘導します。  
  
## 商品一覧画面（Top Page）  
### 1.概要  
アプリケーションのトップ画面であり、全商品の閲覧、検索、およびマイリスト（いいねした商品）の確認を行うメイン画面です。  
  
### 2.実装済み機能  
2-1 商品一覧表示（おすすめ）  
・表示対象：自分以外のユーザーが出品した全商品を表示。  
・未承認時：ログインしていないユーザーでも閲覧可能。  
・データ取得：データベースより全権取得し、フロントエンドに引き渡し。  
  
2-2 マイリスト一覧表示  
・表示対象：ログインユーザーが「いいね」した商品のみを抽出して表示。  
・未承認時：何も表示されない（「表示する商品がありません」と表示）。  
・状態保持：検索キーワードを維持したまま、おすすめタブとマイリストタブの切り替えが可能。  
  
2-3 商品検索機能  
・検索対象：商品名（name カラム）を対象とした部分一致検索。  
・UI:ヘッダー内の検索窓から実行。  
・保持機能：検索結果が表示された状態で「おすすめ/マイリスト」のタブを切り替えても、検索状態が維持される仕組み。  
  
2-4 ステータス表示  
・Soldラベル：すでに購入された商品には、商品画面上に「Sold」のラベルを表示。  
  
### 3.使用技術  
・Backend: PHP 8.x / Laravel 8.x (Eloquent ORM)  
・Frontend: Bladeテンプレート / CSS  
・Database: MySQL (Table: products, likes, users)  
  
### 4.ファイル構成  
Backend  
・app/Http/Controllers/ProductController.php: 一覧取得・検索・タブ切り替えロジック  
・app/Models/Product.php: 商品データのリレーション定義  
・app/Models/Like.php: いいねデータのリレーション定義  
  
Frontend  
・resources/views/products/index.blade.php: 商品一覧メインビュー  
・resources/views/layouts/common.blade.php: 検索窓・ナビゲーションを含む共通ヘッダー  
・public/css/products/index.css: 一覧画面専用スタイル  
・public/css/layouts/common.css: ヘッダー共通スタイル  
  