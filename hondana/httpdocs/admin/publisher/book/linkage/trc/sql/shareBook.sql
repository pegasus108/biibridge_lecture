SELECT
	{$fieldList|@join:','}
from linkage_{$table_id}
where book_no = {$book_no};