select b.book_no , b.name
from book as b
where
	b.book_no in ( {$book_no_list|@join:','} )
	and b.publisher_no = {$publisher_no}
	and not exists(
		select * from book_series as t
		where
		t.series_no = {$series_no}
		and t.book_no = b.book_no
	)
;
