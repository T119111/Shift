CREATE DATABASE if NOT EXISTS webdesign;
USE webdesign;
#従業員データベース
CREATE TABLE if NOT EXISTS employee(
	employeeid INT not null AUTO_INCREMENT,
	fullname VARCHAR(30),
	age INT,
	remarks VARCHAR(10),
	PRIMARY KEY(employeeid)
);
DESC employee;
#シフト管理データベース
DROP TABLE if exists shift;
CREATE TABLE if NOT EXISTS shift(
	shiftid INT NOT NULL AUTO_INCREMENT,
	shiftday DATE,
	employeeid INT,
	strtime DATETIME,
	endtime DATETIME,
	shiftremarks VARCHAR(50),
	PRIMARY KEY(shiftid),
	FOREIGN KEY(employeeid)
	REFERENCES employee(employeeid)
);
DESC shift;

#サンプルデータ入力
/*
INSERT INTO employee VALUES(
	0,'山田一郎',20,'test01'
);
INSERT INTO employee VALUES(
	0,'海田二郎',40,'test02'
);
INSERT INTO employee VALUES(
	0,'空田三郎',60,'test03'
);
*/
SELECT *
FROM employee
;