SELECT n.name,
n.publisher_no,
n.book_no

from book as n
WHERE n.book_no = {$book_no}
	and n.publisher_no = {$publisher_no};
