SELECT nr.*, b.name as genre_name
from book_genre as nr
	left join genre as b
		on nr.genre_no = b.genre_no
where
	nr.book_no = {$book_no}
	and b.publisher_no = {$publisher_no};
