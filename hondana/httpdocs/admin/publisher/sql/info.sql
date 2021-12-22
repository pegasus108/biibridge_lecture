SELECT `name`,value, DATE_FORMAT(public_date, '%Y/%m/%d') AS date
FROM info
WHERE public_status = 1
and public_date <= now()
ORDER BY public_date DESC, u_stamp DESC, c_stamp DESC
;