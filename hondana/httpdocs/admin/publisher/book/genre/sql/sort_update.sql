lock tables genre write;


update genre
set
	lft = {$lft},
	rgt = {$rgt},
	depth = {$depth}
where
	genre_no = {$genre_no};


unlock tables;
