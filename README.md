# EC2サーバー
## URL
### Laravel
```
（リポジトリの "About" に記載）
```

### HONDANA
```
（リポジトリの "About" に記載）
```

# ローカル環境
## コマンド
```
docker-compose up -d
```
テストデータを追加したい場合下記コマンドを実行してください
```
docker-compose exec laravel php artisan DB:seed
```
## URL
### laravel
```
http://localhost
```

### HONDANA
```
http://localhost:81/admin/login
```

# テスト
```
docker-compose exec laravel php artisan test
```
※テストの都合上データベースが初期化されるので気を付けてください。  
# デプロイ
Laravelプロジェクトが編集されたときにGithubActionsによって自動で更新されます。  

2022/01/14★branchのテスト★

