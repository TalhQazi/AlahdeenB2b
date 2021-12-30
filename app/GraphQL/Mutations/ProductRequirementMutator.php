<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBuyRequirement;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductRequirementMutator
{

    protected $category_ids;
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue
     * @param  mixed[]  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context
     * @return mixed
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $this->category_ids = [];
        $product = Product::find($args['product_id']);

        //TODO change logic when a single product might belong to multiple categories
        $category = Category::with('allParentCategories')->where('id', $product->category_id)->get();

        $category->each(function($item, $key) {
            array_push($this->category_ids, $item->id);
            if($item->allParentCategories) {
                $this->getAllParentCategories($item->allParentCategories);
            }

        });

        return ProductBuyRequirement::create([
            'user_id' => Auth::user()->id,
            'category_ids' => implode(',', $this->category_ids),
            'required_product' => $product->title,
            'requirement_details' => $args['requirement_details'],
            'quantity' => $args['quantity'],
            'unit' => $args['unit'],
            'budget' => $args['budget'],
            'requirement_urgency' => $args['requirement_urgency'],
            'requirement_frequency' => $args['requirement_frequency'],
            'supplier_location' => $args['supplier_location'],
        ]);
    }

    public function getAllParentCategories($item)
    {
        array_push($this->category_ids, $item->id);
        if($item->allParentCategories) {
            $this->getAllParentCategories($item->allParentCategories);
        } else {
            return true;
        }
    }

}
