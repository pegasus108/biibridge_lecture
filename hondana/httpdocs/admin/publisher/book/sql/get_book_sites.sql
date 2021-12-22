SELECT *
FROM book_sites

WHERE
	book_no = '{$book_no|escape}'
;
