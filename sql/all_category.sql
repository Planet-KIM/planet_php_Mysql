SELECT `joketext`
FROM `joke`
INNER JOIN `jokecategory`jokecategory
	ON `joke`.`id` = `jokeid`
INNER JOIN `category`
	ON `categoryid` = `category`.`id`