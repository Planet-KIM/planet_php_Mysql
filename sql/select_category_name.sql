SELECT `author`.`name`
FROM `joke`
INNER JOIN `author`
	ON `authorid` = `author`.`id`
INNER JOIN `jokecategory`
	ON `joke`.`id` = `jokeid`
INNER JOIN `category`
	ON `categoryid` = `category`.`id`
WHERE `category`.`name` = "Knock-Knock"