<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use App\Service\Api\BookServices;
use Illuminate\Routing\Controller;
use App\Http\Requests\Book\IndexSortingRequest;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use Spatie\Permission\Models\Permission;


class BooksController extends Controller
{
    use ResponseTrait;

    protected $bookService;
    public function __construct(BookServices $bookService)
    {
       // $this->middleware('permission:book-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->bookService = $bookService;
    }
    //.........................................................
    //.........................................................
    /**
     * All Books
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexSortingRequest $request)
    {
        
          $request->validated();

          $vaidated_request = $request->only(["available",'category','author','category_id']);
        
        $data  = $this->bookService->getAll($vaidated_request); 

        return $this->successResponse($data,"All Books Fetching Successfully",200);
    }
    //.........................................................
    //.........................................................
 
    public function store(StoreBookRequest $request)
    {
       $request->validated();
        //we dont git category becaus whene storing(using:create($data)) book there will be error (category is not column in books table)
       $book= $request->only(["title",'author','description','published_at',"category_id"]);

        $data = $this->bookService->storeService($book);
       
        return $this->successResponse($data,"The Book Store Successfully",200);
    }
    //.........................................................
    //.........................................................

    public function show(Book $book)
    {
       $data =  $this->bookService->showBook($book);

       return $this->successResponse($data,"The Book fetching Successfully",200);

    }

    //.........................................................
    //.........................................................
    /**
     * update a book by its id
     * @param \App\Http\Requests\Book\UpdateBookRequest $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $request->validated();

        $data_validated = $request->only(["title",'author','description','published_at',"category_id"]);
        
        $data = $this->bookService->updateBook($data_validated,$book);

        return $this->successResponse($data,"The Book updating Successfully",200);
    }

    //.........................................................
    //.........................................................
 
    public function destroy(Book $book)
    {
        $data = $this->bookService->destroyBook($book);
        return $this->successResponse($data,"The Book Deleting Successfully",200);
    }
}
