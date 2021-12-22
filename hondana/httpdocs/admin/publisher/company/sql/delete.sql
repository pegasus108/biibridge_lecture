delete
from company
where
	company_no in ({$listString})
	and publisher_no = '{$publisher_no|escape}';
