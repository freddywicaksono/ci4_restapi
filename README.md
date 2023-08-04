# Create Restful API using Codeigniter 4
Please follow these steps:
## 1. Download Codeigniter 4
- Extract, then copy folder to c:/xampp/htdocs
- Rename folder to 'demo'

## 2. Start XAMPP Control Panel
- Start Apache
- Start MySQL

## 3. Open PHPMyAdmin
```
http://localhost/phpmyadmin
```
- Create Database demo

## 2. Rename file env to .env
Edit file .env
```
# CI_ENVIRONMENT = production

# database.default.hostname = localhost
# database.default.database = ci4
# database.default.username = root
# database.default.password = root
# database.default.DBDriver = MySQLi
# database.default.DBPrefix =
# database.default.port = 3306
```
change to
```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = ci4
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```
## 3. Edit file Config/database.php

## 2. Edit file app/Config/App.php
```
public string $baseURL = 'http://localhost:8080/';
public string $indexPage = 'index.php';
public string $uriProtocol = 'REQUEST_URI';
```
change to:
```
public string $baseURL = 'http://localhost/demo/';
public string $indexPage = '';
public string $uriProtocol = 'PATH_INFO';

```
## 3. Copy file .htaccess and index.php from public folder to root directory
Edit file index.php in root directory:
```
require FCPATH . '../app/Config/Paths.php';
```
change into :
```
require FCPATH . 'app/Config/Paths.php';
```
