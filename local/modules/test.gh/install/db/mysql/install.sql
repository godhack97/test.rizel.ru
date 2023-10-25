create table if not exists currency_list
(
	`ID` int not null auto_increment,
    `CODE` varchar(50),
    `DATE` datetime,
    `COURSE` float,
	primary key (ID)
);