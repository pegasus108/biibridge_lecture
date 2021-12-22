{*lock tables
book,stock_status
write;
*}


{foreach name=status from=$statuses item=status key=statusName}
{if count($status) >= 1}


UPDATE book
SET stock_status_no = (select stock_status_no from stock_status where stock_status.name = '{$statusName}')
WHERE isbn in('{$status|@join:"','"}')
AND publisher_no={$publisher_no};


{/if}
{/foreach}





{*unlock tables;*}
