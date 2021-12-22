select b.book_no
from book as b
where
	publisher_no = {$publisher_no}
	and isbn = '{$isbn}';