# short_url
## mysqlで以下のSQLを実行し、テーブルを作ってください
```
CREATE TABLE `short_url` (
 `id` int(150) NOT NULL AUTO_INCREMENT,
 `long_url` text NOT NULL,
 `short_url` text NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `long_url` (`long_url`(500)),
 UNIQUE KEY `short_url` (`short_url`(500))
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4
```
config.phpでデーターベースなどの設定を必ずおこなってください

お問い合わせはtwitter:@moons14_まで
