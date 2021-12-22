SELECT
*
FROM genre AS g

WHERE
publisher_no={$publisher_no}
and lft = 1

limit 1
;