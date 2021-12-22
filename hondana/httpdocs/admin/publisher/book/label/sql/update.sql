lock tables label write;


update label
set
	name = '{$name}',
	url = '{$url}'
where
	label_no = {$label_no};


unlock tables;
