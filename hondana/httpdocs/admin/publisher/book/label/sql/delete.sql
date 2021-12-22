lock tables label, book_label write;

{foreach name=deletelabel from=$deletelabelList item=deletelabel}

select @myL := lft from label
where
	label_no = {$deletelabel.label_no};

delete
from label
where
	label_no = {$deletelabel.label_no};


update label
set
	lft = lft - 2
where
	lft > @myL
	and publisher_no = {$publisher_no};


update label
set
	rgt = rgt - 2
where
	rgt > @myL
	and publisher_no = {$publisher_no};


delete from book_label
where
	label_no = {$deletelabel.label_no};


{/foreach}

unlock tables;
