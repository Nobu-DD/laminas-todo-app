# laminas-todo-app

PHP / Laminas MVC で構築した REST API と jQuery フロントエンドによる Todo 管理アプリケーションです。  
スキーマ駆動開発・Docker による環境統一・GitHub Actions による CI/CD を採用し、チーム開発を意識した構成としています。

---

## 📋 目次

- [サービス概要](#サービス概要)
- [技術スタック](#技術スタック)
- [技術選定理由](#技術選定理由)
- [システム構成](#システム構成)
- [API 仕様](#api-仕様)
- [画面仕様](#画面仕様)
- [ディレクトリ構成](#ディレクトリ構成)
- [開発環境セットアップ](#開発環境セットアップ)
- [CI/CD](#cicd)
- [今後の課題](#今後の課題)

---

## サービス概要

| 項目 | 内容 |
|---|---|
| アプリ名 | laminas-todo-app |
| 概要 | タスクの作成・管理・進捗追跡ができる Todo アプリ |
| 対象ユーザー | 個人の作業管理をしたいユーザー |
| 主な機能 | タスク CRUD・ステータス管理（未着手 / 進行中 / 完了） |
| 開発目的 | PHP / Laminas MVC・スキーマ駆動開発・CI/CD 運用のキャッチアップ |

---

## 技術スタック

### バックエンド

| 技術 | バージョン | 役割 |
|---|---|---|
| PHP | 8.2 | サーバーサイド言語 |
| Laminas MVC | 3.x | フレームワーク（Zend Framework 後継） |
| MySQL | 8.0 | データベース |
| PHPUnit | 10.x | ユニットテスト |
| PHP CS Fixer | 3.x | コード整形 / Lint |

### フロントエンド

| 技術 | バージョン | 役割 |
|---|---|---|
| HTML5 / CSS3 | - | マークアップ・スタイリング |
| jQuery | 3.7 | DOM 操作・Ajax 通信 |
| Bootstrap | 5.3 | UI コンポーネント |

### インフラ / 開発支援

| 技術 | 役割 |
|---|---|
| Docker / Docker Compose | ローカル開発環境の統一 |
| GitHub Actions | CI/CD（テスト・Lint・Docker ビルド確認） |
| OpenAPI 3.0 | API スキーマ定義（スキーマ駆動開発） |

---

## 技術選定理由

### Laminas MVC（旧 Zend Framework）
Zend Framework は 2019 年に Laminas Project として移管・継続されています。  
現行の保守バージョンである Laminas MVC を採用することで、Zend Framework の設計思想を習得しながら、実務で使われる最新の状態に追従しています。

### スキーマ駆動開発（OpenAPI First）
実装前に OpenAPI スキーマを定義することで、フロントエンドとバックエンドの API 仕様を固めることにしました。 
仕様変更のコストを下げ、並行開発をしやすくする設計アプローチとして採用しました。

### フロントエンド・バックエンド分離
REST API とクライアントを疎結合にすることで、フロントエンドのフレームワーク差し替えや、モバイルアプリへの対応が容易になります。

### Docker Compose
チームメンバー全員が `docker-compose up` の 1 コマンドで同一環境を再現できるようにするため採用しました。  
「自分の環境では動く」という問題をなくすことが目的です。

### GitHub Actions
push のたびに自動でテスト・Lint・Docker ビルドが走る構成とし、コードの品質を継続的に担保します。  
手動確認に依存せず、チーム開発でのレビュー負荷を下げることを意識しています。

---

## システム構成

```
┌──────────────────────────────────────────────┐
│  フロントエンド                                │
│  index.html + js/app.js (jQuery)             │
│  └─ fetch / Ajax で REST API を呼び出し        │
├──────────────────────────────────────────────┤
│  バックエンド (Laminas MVC)                   │
│  REST API: /api/todos                        │
│  └─ GET / POST / PUT / DELETE                │
├──────────────────────────────────────────────┤
│  MySQL 8.0                                   │
│  todos テーブル                               │
└──────────────────────────────────────────────┘
         ↑ すべて Docker Compose で管理

GitHub Actions（main への push で発火）
  ├─ PHPUnit テスト
  ├─ PHP CS Fixer（Lint）
  └─ Docker ビルド確認
```

---

## API 仕様

OpenAPI スキーマは [`docs/openapi.yml`](./docs/openapi.yml) を参照してください。

---

## 画面仕様

| 画面 | 説明 |
|---|---|
| タスク一覧 | ステータス別にタスクを表示。フィルタリング可能 |
| タスク追加 | タイトル・説明・ステータスを入力して登録 |
| タスク編集 | 既存タスクの内容・ステータスを変更 |
| タスク削除 | 確認ダイアログ付きで削除 |

---

## ディレクトリ構成

```
laminas-todo-app/
├── docs/
│   └── openapi.yml              # OpenAPI スキーマ定義
├── docker-compose.yml
├── docker/
│   ├── php/
│   │   └── Dockerfile
│   └── mysql/
│       └── init.sql             # テーブル初期化
├── backend/                     # Laminas MVC プロジェクト
│   ├── module/
│   │   └── Todo/
│   │       ├── Controller/
│   │       │   └── TodoController.php
│   │       ├── Model/
│   │       │   └── TodoTable.php
│   │       └── test/
│   │           └── TodoControllerTest.php
│   └── composer.json
├── frontend/                    # jQuery フロントエンド
│   ├── index.html
│   └── js/
│       └── app.js
├── .github/
│   └── workflows/
│       └── ci.yml               # GitHub Actions CI/CD
└── README.md
```

---

## 開発環境セットアップ

### 前提条件

- Docker Desktop がインストールされていること
- Git がインストールされていること

### 手順

```bash
# 1. リポジトリをクローン
git clone https://github.com/<your-username>/laminas-todo-app.git
cd laminas-todo-app

# 2. コンテナを起動
docker-compose up -d

# 3. composer パッケージをインストール
docker-compose exec php composer install

# 4. データベースを初期化
docker-compose exec mysql mysql -u root -proot todo_db < docker/mysql/init.sql
```

### アクセス先

| サービス | URL |
|---|---|
| フロントエンド | http://localhost:8080 |
| REST API | http://localhost:8080/api/todos |
| MySQL | localhost:3306 |

### テスト実行

```bash
# PHPUnit テスト
docker-compose exec php ./vendor/bin/phpunit

# PHP CS Fixer（コード整形チェック）
docker-compose exec php ./vendor/bin/php-cs-fixer check
```

---

## CI/CD

GitHub Actions による自動化パイプラインを構成しています。  
`main` ブランチへの push・PR をトリガーに以下が自動実行されます。

| ステップ | 内容 |
|---|---|
| PHPUnit テスト | API エンドポイントのユニットテスト |
| PHP CS Fixer | コーディング規約チェック（PSR-12） |
| Docker ビルド | イメージが正常にビルドできるかを確認 |

設定ファイル: [`.github/workflows/ci.yml`](./.github/workflows/ci.yml)

---

## 今後の課題

- [ ] ユーザー認証機能（JWT）の追加
- [ ] タスクの期限日設定
- [ ] ページネーション対応
- [ ] E2E テストの導入
- [ ] 本番環境へのデプロイ自動化