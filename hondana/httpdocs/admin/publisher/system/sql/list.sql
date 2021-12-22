select p.*,pa.password as pass
from publisher p
join publisher_account pa on p.publisher_no = pa.publisher_no
where p.publisher_no = {$publisher_no}
and pa.is_default=1
;