select bfb.*,b.name,b.book_format,b.book_format_other
from book_format_book as bfb
left join book as b on bfb.book_no_other = b.book_no

where bfb.book_no = {$book_no}
and b.publisher_no = {$publisher_no}

order by bfb.order
;