select
book_no,
book_format,
book_format_other

from book

where book_no = {$other_book_no}
;