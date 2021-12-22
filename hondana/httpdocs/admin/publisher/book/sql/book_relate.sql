SELECT nr.*, b.name as relate_book_name,b.book_date,b.image
from book_relate as nr
	left join book as b
		on nr.book_relate_book_no = b.book_no
where
	nr.book_no = {$book_no}
	and b.publisher_no = {$publisher_no}
order by nr.`order` is not null,nr.`order` <> 0,nr.`order`,b.public_date desc
;
