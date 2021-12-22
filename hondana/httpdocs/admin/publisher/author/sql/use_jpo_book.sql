select
	b.book_no,b.name
from
	`author` as nc
	left join opus as n on nc.author_no = n.author_no
	left join book as b on n.book_no = b.book_no
where
	nc.author_no in ({$listString})
	and n.opus_no is not null
	and nc.publisher_no = {$publisher_no}
	and b.sync_allowed is not null;