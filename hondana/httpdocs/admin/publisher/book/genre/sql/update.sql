lock tables genre write;


update genre
set
	name = '{$category_name}'
where
	genre_no = {$genre_no};


unlock tables;
