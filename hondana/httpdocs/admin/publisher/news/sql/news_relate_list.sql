SELECT nr.*, b.name as book_name,b.book_date,b.image
from news_relate as nr
	left join book as b
		on nr.book_no = b.book_no
where b.publisher_no = '{$publisher_no|escape}'
and nr.news_no = '{$news_no|escape}'
order by nr.`order` is not null,nr.`order` <> 0,nr.`order`,b.book_date desc
;
