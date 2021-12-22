select coalesce(max(publisher_account_no), 0) + 1 as next_no
from publisher_account;
