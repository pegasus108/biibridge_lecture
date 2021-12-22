lock tables series write;


update series
set
	name = '{$name}',
	kana = '{$kana}'
where
	series_no = {$series_no};


unlock tables;
