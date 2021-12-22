lock tables series,book_series write;

{foreach name=deleteseries from=$deleteseriesList item=deleteseries}

select @myL := lft from series
where
	series_no = {$deleteseries.series_no};

delete
from series
where
	series_no = {$deleteseries.series_no};


update series
set
	lft = lft - 2
where
	lft > @myL
	and publisher_no = {$publisher_no};


update series
set
	rgt = rgt - 2
where
	rgt > @myL
	and publisher_no = {$publisher_no};


delete from book_series
where
	series_no = {$deleteseries.series_no};


{/foreach}

unlock tables;
