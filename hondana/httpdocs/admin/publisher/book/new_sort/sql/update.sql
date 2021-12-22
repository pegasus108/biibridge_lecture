lock tables book write;
{foreach name=bookNoList from=$bookNoList item=v}
update `book` set
new_order = '{$smarty.foreach.bookNoList.iteration}'
where book_no = '{$v}'
and publisher_no = '{$publisher_no}';
{/foreach}

unlock tables;