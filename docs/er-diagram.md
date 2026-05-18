# ER図 - laminas-todo-app

## テーブル定義

```mermaid
erDiagram
    todos {
        INT(UNSIGNED) id PK "AUTO_INCREMENT 主キー"
        VARCHAR(255) title "タスクタイトル NOT NULL"
        TEXT description "詳細説明 NULL可"
        ENUM status "pending / in_progress / done NOT NULL"
        DATETIME created_at "作成日時 NOT NULL DEFAULT CURRENT_TIMESTAMP"
        DATETIME updated_at "更新日時 NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }
```

## フィールド詳細

| フィールド | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | INT UNSIGNED | NO | AUTO_INCREMENT | 主キー |
| title | VARCHAR(255) | NO | - | タスクタイトル |
| description | TEXT | YES | NULL | タスクの詳細説明 |
| status | ENUM('pending','in_progress','done') | NO | 'pending' | 進捗ステータス |
| created_at | DATETIME | NO | CURRENT_TIMESTAMP | 作成日時（自動設定） |
| updated_at | DATETIME | NO | CURRENT_TIMESTAMP | 更新日時（自動更新） |

## ステータス遷移

```mermaid
stateDiagram-v2
    [*] --> pending : 作成
    pending --> in_progress : 着手
    in_progress --> done : 完了
    done --> in_progress : 差し戻し
    pending --> done : 直接完了
```