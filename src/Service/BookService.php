<?php

namespace App\Service;

use App\Entity\Books;
use Psr\Log\LoggerInterface;

interface BookService {
    public function getByIsbn($isbn);
    public function search($title, $isbn);
    public function add(Books $book);
    public function addLabel($isbn, $label);
}

?>
