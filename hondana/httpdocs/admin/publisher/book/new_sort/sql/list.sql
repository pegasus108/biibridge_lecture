SELECT
b.book_no,
b.name,
b.sub_name,
b.image,
b.isbn,
b.volume,
b.book_date,
b.public_status,
b.public_date,
b.new_order
FROM book as b
left join opus as o
using(book_no)
WHERE b.new_status = 1
AND b.publisher_no = {$publisher_no}
group by b.book_no
ORDER BY b.new_order is not null,b.new_order <> 0,b.new_order,b.book_date desc,o.c_stamp,o.opus_no;
