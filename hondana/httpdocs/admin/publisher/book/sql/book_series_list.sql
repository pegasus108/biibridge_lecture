SELECT
	bs.book_series_no,
	bs.book_no,
	s.name
from book_series as bs
	inner join series as s
		on bs.series_no = s.series_no
		and s.publisher_no = {$publisher_no}
		and s.display <> 0
where bs.book_no in ({$bookNoList|@join:','})
;