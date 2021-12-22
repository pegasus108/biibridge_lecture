lock tables info write;


delete
from info
where
	info_no in ({$listString});

unlock tables;
