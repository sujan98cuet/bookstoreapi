<?php

namespace App\Controller;


use \Datetime;
use App\Entity\Books;
use App\Entity\Labels;
use App\Service\BookService;
use App\Service\JsonSerializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class APIController extends Controller
{

    private $serializer;
    private $bookService;
    private $logger;

    public function __construct(BookService $bookService, JsonSerializer $serializer, LoggerInterface $logger) {
        $this->bookService = $bookService;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {

        return $this->json([
            'message' => 'Welcome to BookApi! The path you can access from here ',
            'path 1' => '/api/books/{isbn}',
            'path 2' => '/api/books/{isbn}',
            'path 3' => '/api/books/{isbn}/labels',
            'path 4' => '/api/books/{isbn}/labels/{label}',
            'path 5' => '/admin'
        ]);

    }



    /**
     * @Route("/api/books", name="Get books based on ISBN and Title")
     * @Method("GET")
     */
    public function list(Request $request) {
        $title = $request->query->get('title');
        $isbn = $request->query->get('isbn');
        $json = $this->serializer->serialize($this->bookService->search($title, $isbn));
        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/api/books/{isbn}", name="Get book by ISBN")
     * @Method("GET")
     */
    public function getByIsbn($isbn) {
        $book = $this->bookService->getByIsbn($isbn);
        if ($book) {
            $json = $this->serializer->serialize($book);
            return JsonResponse::fromJsonString($json);
        } else {
            $response =  new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }

    /**
     * @Route("/api/books/{isbn}", name="Add a new book")
     * @Method("POST")
     */
    public function add($isbn, Request $request) {
        $contentType = $request->headers->get('Content-Type');

        $response =  new Response();
        if ($contentType == 'application/json') {
            $content = $request->getContent();
            $this->logger->info("Adding book: " . $isbn);
            $book = $this->serializer->deserialize($content, Book::class);
            $this->bookService->add($book);
            $response->setStatusCode(Response::HTTP_CREATED);
        } else {
            $response->setStatusCode(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        return $response;
    }

    /**
     * @Route("/api/books/{isbn}/labels", name ="Get a book by ISBN")
     * @Method("GET")
     */
    public function labels($isbn, Request $request) {
        $book = $this->bookService->getByIsbn($isbn);
        if ($book) {
            $json = $this->serializer->serialize($book->getLabels());
            return JsonResponse::fromJsonString($json);
        } else {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }

    /**
     * @Route("/api/books/{isbn}/labels/{label}", name= "Add labels to a book")
     * @Method("POST")
     */
    public function addLabel($isbn, $label) {
        $book = $this->bookService->getByIsbn($isbn);

        $response =  new Response();
        if ($book) {
            $this->bookService->addLabel($isbn, $label);
            $response->setStatusCode(Response::HTTP_CREATED);
            return $response;
        } else {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }
}

?>


