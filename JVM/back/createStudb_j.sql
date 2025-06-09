/*-- データベース演習I  環境構築スクリプト */

-- ユーザ作成(User名：dbuser)
DROP USER IF EXISTS dbjvm;
CREATE USER dbjvm IDENTIFIED WITH MYSQL_NATIVE_PASSWORD BY 'ecc';

-- データベース削除
DROP DATABASE IF EXISTS studbjvm;

-- データベース作成(jvmdb：学習環境、trdb：練習環境)
CREATE DATABASE studbjvm;

-- ユーザにデータベース権限付与
GRANT ALL ON studbjvm.* TO dbjvm;

-- データベース移動にテーブル作成(学習環境)
USE studbjvm;

source createTable_j.sql