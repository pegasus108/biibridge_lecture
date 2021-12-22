SELECT n.*
FROM `author` as n
WHERE n.author_no in ({$listString});