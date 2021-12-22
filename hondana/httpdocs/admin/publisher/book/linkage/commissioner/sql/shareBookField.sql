SELECT
	field_id,
    table_id
FROM linkage_shared_field
WHERE
	field_id in('{$fieldList|@join:"','"}')
	and book_no = {$book_no};
