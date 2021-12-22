SELECT n.*
FROM `role` as n
WHERE n.publisher_no = {$publisher_no}
OR n.publisher_no = 0
ORDER BY role_no;