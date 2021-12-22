select coalesce(max(banner_no), 0) + 1 as next_no
from banner
;