{*lock tables book_honzuki write;*}


INSERT INTO
book_honzuki(
    book_no,
    is_enable
)
VALUES(

	'{$book_no}' ,

    '{if $honzuki}1{else}0{/if}'

)
ON DUPLICATE KEY UPDATE
	book_no = VALUES(book_no),
	is_enable = VALUES(is_enable)
;



{*unlock tables;*}

