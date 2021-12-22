select
	nc.*
from
	`genre` as nc
	left join
		book_genre as n
		on nc.genre_no = n.genre_no
where
	nc.genre_no in ({$listString})
	and n.book_no is not null
	and nc.publisher_no = {$publisher_no};