SELECT nr.*, b.name as author_name,b.kana as author_kana, at.author_type_no
from opus as nr
	left join author as b
		on nr.author_no = b.author_no
	left join author_type as at
		on nr.author_type_no = at.author_type_no
where
	nr.book_no = {$book_no}
	and b.publisher_no = {$publisher_no}
order by nr.c_stamp,nr.opus_no;
