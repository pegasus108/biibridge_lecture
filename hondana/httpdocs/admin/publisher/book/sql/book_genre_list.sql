SELECT
	bg.book_genre_no,
	bg.book_no,
	g.name
from book_genre as bg
	inner join genre as g
		on bg.genre_no = g.genre_no
		and g.publisher_no = {$publisher_no}
		and g.display <> 0
where bg.book_no in ({$bookNoList|@join:','})
;