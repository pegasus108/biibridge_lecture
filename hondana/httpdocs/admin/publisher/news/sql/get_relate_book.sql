SELECT
book_no , name,`image`,book_date

from book

where
publisher_no = '{$publisher_no|escape}' and
book_no in ({$booknolist|escape});
;
