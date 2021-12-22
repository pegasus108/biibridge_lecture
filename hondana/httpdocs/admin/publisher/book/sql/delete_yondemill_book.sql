{*lock tables yondemill_book write;*}

delete
from yondemill_book
where
yondemill_id = {$yondemill_id}
;

{*unlock tables;*}
