/*-- データベース演習I  テーブル作成スクリプト */
DROP TABLE IF EXISTS employee;
DROP TABLE IF EXISTS department;

/*---------------テーブル作成----------------------*/
CREATE TABLE employee(
    u_id         CHAR(4),
    u_password  VARCHAR(6),
    u_name  VARCHAR(50),
    d_id    CHAR(3),
    location VARCHAR(1000),
    state int,
    date VARCHAR(20),
    comment VARCHAR(500)
);

CREATE TABLE department(
	d_id CHAR(3),
	d_dep VARCHAR(10)
);

/*---------------------データ挿入---------------------------*/
INSERT INTO employee VALUES('0001','jvm','工藤亜由美','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0002','jvm','佐々木嘉','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0003','jvm','森岡尊','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0004','jvm','新田秀明','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0005','jvm','武井晋太郎','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0006','jvm','坂本章裕','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0007','jvm','笹野寿哉','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0008','jvm','小幡勇太','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0009','jvm','筒井亮','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0010','jvm','遠藤佳子','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0011','jvm','北風清志','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0012','jvm','藤原弘晃','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0013','jvm','高橋リカ','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0014','jvm','小澤拓郎','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0015','jvm','高橋龍','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0016','jvm','宮嶋誠','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0017','jvm','堀信幸','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0018','jvm','田仲真優','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0019','jvm','齋藤和之','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0020','jvm','田中昭彦','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0021','jvm','浜口昌彦','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0022','jvm','小俣美千子','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0023','jvm','梶田和也','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0024','jvm','杉本桂','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0025','jvm','赤澤武','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0026','jvm','槌田克仁','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0027','jvm','西澤彰','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0028','jvm','遠藤宏樹','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0029','jvm','奥平太郎','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0030','jvm','村田健史','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0031','jvm','松田陽子','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0032','jvm','大槻栄二','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0033','jvm','林友里','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0034','jvm','吉田大輔','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0035','jvm','小阪大吾','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0036','jvm','越智隆行','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0037','jvm','淺野悠子','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0038','jvm','真栄城静代','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0039','jvm','海老原清志','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0040','jvm','今川慶子','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0041','jvm','齋藤裕太','003','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0042','jvm','久保太朗','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0043','jvm','浅野純一','002','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0044','jvm','広島康介','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0045','jvm','石川沙里','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0046','jvm','川口ひとみ','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0047','jvm','森雅人','001','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0048','jvm','今井亜希子','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0049','jvm','阿部綾子','005','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');
INSERT INTO employee VALUES('0050','jvm','松本悠希','004','位置情報を取得できません',0,'1/1 00:00','状況を入力してください');

INSERT INTO department VALUES('001','総務');
INSERT INTO department VALUES('002','法務');
INSERT INTO department VALUES('003','人事');
INSERT INTO department VALUES('004','経理');
INSERT INTO department VALUES('005','広告');

SELECT * FROM employee;
SELECT * FROM department;

SELECT E.u_name , D.d_dep FROM employee as E
LEFT JOIN department AS D
ON E.d_id = D.d_id
where D.d_id = '001';

COMMIT;