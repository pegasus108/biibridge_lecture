lock tables publisher_account, opus write;



delete
from opus
where
	publisher_account_no in ( {$publisher_account_no_list|@join:','} )
	and exists(
		select * from publisher_account
		where publisher_account.publisher_account_no = opus.publisher_account_no
		and publisher_account.publisher_no = {$publisher_no}
	);

delete
from publisher_account
where
	publisher_account_no in ( {$publisher_account_no_list|@join:','} )
	and publisher_no = {$publisher_no};



unlock tables;
