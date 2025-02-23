<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Interfaces\ProductCategoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private ProductCategoryInterface $productCategoryRepository;

    public function __construct(ProductCategoryInterface $userRepositoryInterface)
    {
        $this->productCategoryRepository = $userRepositoryInterface;
    }


    public function storeCategory(CategoryRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();
            $subCategory = $this->productCategoryRepository->storeCategory($validatedData);
            DB::commit();
            return response()->json($subCategory, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function index(CategoryIndexRequest $request)
    {
        try {
            $categories = $this->productCategoryRepository->getCategories([
                'search' => $request->input('search'),
                'per_page' => $request->input('per_page'),
            ]);

            return response()->json($categories, 200);
            } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
