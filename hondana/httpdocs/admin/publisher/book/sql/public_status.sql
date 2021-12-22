SELECT n.*
FROM `book` as n
WHERE
	n.book_no in ( {$book_no_list|@join:','} )
	and n.public_status = 1;