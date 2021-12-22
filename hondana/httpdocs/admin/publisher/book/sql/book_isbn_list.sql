select b.book_no ,b.isbn
from book as b
where
	publisher_no = {$publisher_no}
	and isbn is not null
	and isbn <> ''
;