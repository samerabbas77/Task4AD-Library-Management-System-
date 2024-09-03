<?php
namespace App\Service\Api;

use App\Models\Book;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BookResources;
use App\Models\Category;

class BookServices
{
    /**
     * Get all the books 
     * Sorting the book dependes on author or available of the book or the categgory of
     * the book(optionally one or all)
     * show the result sorting by name asc or desc deside by(sort_by)
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAll($validated)
    {
        try {          
                    // Start with a base query
                    $query = Book::query();
            
                    // Apply filters if they exist
                    if (!empty($validated['author']))                   
                    {
                        $query->where('author', $validated['author']);
                    }
            
                    if (!empty($validated['category'])) 
                    {
                        $query->whereHas('category', function($q) use ($validated) {
                            $q->where('name', $validated['category']);
                        });
                    }
            
                    if (isset($validated['available'])) 
                    {
                        if ($validated['available'] == 1) 
                        {
                            // Join with borrows table to check if returned_at is not null
                            $query->whereHas('borrows', function ($q) {
                                $q->whereNotNull('returned_at');
                            });
                        } else
                         {
                            $query->where('available', $validated['available']);
                        }
                    }
            
                    // Apply sorting if it exists
                    if (!empty($validated['sort_by'])) 
                    {
                        $query->orderBy('name', $validated['sort_by']); // Assuming you want to sort by 'name'
                    }
            
                    // Get the results, paginated
                    $books = $query->paginate(10);
            
                    // Return the results, for example, to a view        

                    return BookResources::collection($books);
                } catch (\Exception $e) {
                    return $this->handleException($e,"Somthing went rong while fetching the books");
        }
       
    } 
    //.........................................................
    //.........................................................
    /**
     * Story a book
     * @param mixed $data
     * @return BookResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function storeService($data)
    {
        try {
            
            $book = Book::create($data);
            return new BookResources($book);
        } catch (\Exception $e) {
            return $this->handleException($e,"Somthing went rong while storing the book");
        }
    }
    //.........................................................
    //.........................................................
    public function showBook($book)
    {
        try {
            $book = Book::findOrFail($book->id);
            return new BookResources($book);
        } catch (\Exception $e) {
            return $this->handleException($e,"Somthing went rong while fentching the book");
        }
    }

    //.........................................................
    //.........................................................

    /**
     * update a Book by its id
     * @param mixed $data
     * @param mixed $book
     * @return BookResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function updateBook($data,$book)
    {
        try {
            $book = BooK::findOrFail($book->id);
            if(isset($data["title"])) $book->title = $data["title"];
            if(isset($data["author"])) $book->author = $data["author"];
            if(isset($data["description"])) $book->description = $data["description"];
            if(isset($data["published_at"])) $book->published_at = $data["published_at"];
            if(isset($data["category_id"])) $book->category_id = $data["category_id"];

            $book->save();
            return new BookResources($book);
        } catch (\Exception $e) {
            return $this->handleException($e,"Somthing went rong while updating the book");
        }
    }
           
    //.........................................................
    //.........................................................

    public function destroyBook($book)
    {
        try {
            $data = BooK::findOrFail($book->id);
            $book->delete();
            return new BookResources($data);
        } catch (\Exception $e) {
            return $this->handleException($e,"Somthing went rong while Deleting the book");
        }        
    }



    //..............................Hamdle Exception...........................
    //.......................................................................
    
    /**
     * Handle the Exception
     * @param \Exception $e
     * @param string $message
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    protected function handleException(\Exception $e, string $message)
    {
        // Log the error with additional context if needed
        Log::error($message, ['exception' => $e->getMessage(), 'request' => request()->all()]);

        return response()->json($e->getMessage(), 500);
    }
}