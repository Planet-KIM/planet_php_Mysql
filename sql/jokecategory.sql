CREATE TABLE `jokecategory` (
	`jokeid` INT NOT NULL,
    `categoryid` INT NOT NULL,
    PRIMARY KEY(`jokeid`, `categoryid`)
)DEFAULT CHARACTER SET utf8 ENGINE=InnoDB