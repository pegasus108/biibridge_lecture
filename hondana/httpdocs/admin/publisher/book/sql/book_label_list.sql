SELECT
	bg.book_label_no,
	bg.book_no,
	g.name
from book_label as bg
	inner join label as g
		on bg.label_no = g.label_no
		and g.publisher_no = '{$publisher_no|escape}'
		and g.display <> 0
where bg.book_no in ({$bookNoList|@join:','})
;