SELECT count(*) as recommend_count
FROM book
WHERE recommend_status = 1 and publisher_no = {$publisher_no};