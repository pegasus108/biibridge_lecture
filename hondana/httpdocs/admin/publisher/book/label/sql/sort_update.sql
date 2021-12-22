lock tables label write;


update label
set
	lft = {$lft},
	rgt = {$rgt},
	depth = {$depth}
where
	label_no = {$label_no};


unlock tables;
