create table `tasks` (
	`id` int unsigned not null auto_increment,
	`status` varchar(10),
	`subject` varchar(255),
	`type` varchar(10),
	`place` varchar(255),
	`datetime` timestamp null default null,
	`period` varchar(10),
	`comment` text,
	primary key (`id`)
);