SELECT b.book_no , b.name , b.volume , lc.status

from book as b
left join
linkage_commissioner as lc
using(book_no)

WHERE b.book_no in ({$book_no_list|@join:','})
and exists(
	select 1
	from book as n
	where n.publisher_no = {$publisher_no}
	and n.book_no = b.book_no
);
