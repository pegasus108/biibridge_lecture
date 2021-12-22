SELECT
*
FROM series AS s

WHERE
publisher_no={$publisher_no}
and lft = 1

limit 1
;