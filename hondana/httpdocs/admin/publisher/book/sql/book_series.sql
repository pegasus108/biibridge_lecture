SELECT nr.*, b.name as series_name
from book_series as nr
	left join series as b
		on nr.series_no = b.series_no
where
	nr.book_no = {$book_no}
	and b.publisher_no = {$publisher_no};
