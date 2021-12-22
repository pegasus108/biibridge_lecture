select b.*, a.author_no
from book as b
	left join (select * from opus order by c_stamp) as o
		using(book_no)
	left join author as a
		using(author_no)
where
	b.book_no in ( {$relateList|@join:','} )
	and b.publisher_no = {$publisher_no}
	and b.public_status = 1
	and b.public_date <= now();