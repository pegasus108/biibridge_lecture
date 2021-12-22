select coalesce(max(book_no), 0) + 1 as next_no
from book;
