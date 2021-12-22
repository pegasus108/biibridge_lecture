SELECT
e.id,
CASE WHEN pe.public_status THEN pe.public_status ELSE null END as status
FROM ebookstores as e
left join `publisher_ebookstores` as pe on e.id = pe.ebookstores_no and pe.publisher_no={$publisher_no}

;