select b.book_no , b.name
from book as b
where
	b.book_no in ( {$book_no_list|@join:','} )
	and b.publisher_no = '{$publisher_no|escape}'
	and not exists(
		select * from book_label as t
		where
		t.label_no = '{$label_no|escape}'
		and t.book_no = b.book_no
	)
;
