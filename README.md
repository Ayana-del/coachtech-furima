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
![ER図](./er-diagram.png)  

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
| Like | 商品への「いいね」保存 | US005 (FN018) |  
| orders | 商品の購入確定情報・配送先 | US006 (FN022, FN024) |

## モデル・リレーション定義  
Eloquent モデルにおける主要なリレーションシップを以下に定義しています。  
### User　モデル  
・hasOne(Profile):1つのプロフィールを持つ  
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

