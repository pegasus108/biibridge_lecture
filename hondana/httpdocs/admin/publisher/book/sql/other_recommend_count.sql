SELECT count(*) as recommend_count
FROM book
WHERE
book_no <> {$book_no}
and recommend_status = 1 and publisher_no = {$publisher_no};