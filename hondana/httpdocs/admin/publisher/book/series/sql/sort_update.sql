lock tables series write;


update series
set
	lft = {$lft},
	rgt = {$rgt},
	depth = {$depth}
where
	series_no = {$series_no};


unlock tables;
