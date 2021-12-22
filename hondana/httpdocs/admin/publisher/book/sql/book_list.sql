select b.book_no , b.name , b.image
from book as b
where
	b.book_no in ( {$book_no_list|@join:','} )
	and publisher_no = {$publisher_no};