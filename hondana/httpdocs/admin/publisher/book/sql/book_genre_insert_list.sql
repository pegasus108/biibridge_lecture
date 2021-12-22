select b.book_no , b.name
from book as b
where
	b.book_no in ( {$book_no_list|@join:','} )
	and b.publisher_no = {$publisher_no}
	and not exists(
		select * from book_genre as t
		where
		t.genre_no = {$genre_no}
		and t.book_no = b.book_no
	)
;
