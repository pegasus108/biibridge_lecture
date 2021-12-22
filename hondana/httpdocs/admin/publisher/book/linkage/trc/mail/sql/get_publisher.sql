SELECT p.*
FROM linkage_trc as lt
	inner join book as b
		on lt.book_no = b.book_no
	inner join `publisher` as p
		on b.publisher_no = p.publisher_no
where
	lt.status = 1
	
group by
	p.publisher_no
;