SELECT n.*
FROM `info` as n
WHERE n.info_no in ({$listString});