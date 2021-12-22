lock tables book write;

{foreach name=uploadImage from=$uploadImageList key=key item=uploadImage}
update book

set image = '/images/book/{$uploadImage.book_no}{"/^([^.]+)(\.[a-zA-Z]+)$/"|preg_replace:"\\2":$uploadImage.file|basename}'

where publisher_no = {$publisher_no}
and book_no = {$uploadImage.book_no}
;
{/foreach}

unlock tables;
