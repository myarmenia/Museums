<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewsCategoryRequest;
use App\Http\Resources\News\Category\NewsCategoryResource;
use App\Models\News\NewsCategory;
use App\Models\News\NewsCategoryTranslations;
use App\Services\News\NewsCategoryService;
use Illuminate\Http\Request;


class NewsCategoryController extends Controller
{
    protected $newsCategoryService;

    public function __construct(NewsCategoryService $newsCategoryService)
    {
        $this->newsCategoryService = $newsCategoryService;
    }

    public function index()
    {
        $category = NewsCategory::with('translations')->get();

        $category = $this->newsCategoryService->customResource($category);
     
        return view('content.news.category.index', compact('category'));
    }

    public function createCategoryPage()
    {
        return view('content.news.category.create');
    }

    public function createCategory(NewsCategoryRequest $request)
    {
        $this->newsCategoryService->createCategory($request->all());

        return redirect()->route('news-category')
                ->with('i', ($request->input('page', 1) - 1) * 5);
        
    }
}
