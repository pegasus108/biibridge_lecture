SELECT nr.*, b.name as label_name
from book_label as nr
	left join label as b
		on nr.label_no = b.label_no
where
	nr.book_no = '{$book_no|escape}'
	and b.publisher_no = '{$publisher_no|escape}';
