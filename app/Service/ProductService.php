<?php
namespace App\Service;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class ProductService
{
    public function create(array $data){
        $data['uuid'] = Str::uuid();
        $data['slug'] = Str::slug($data['name']);
        return Product::create($data);
    }

    public function getByUid(string $uuid){
        return Product::where("uuid", $uuid)->firstOrFail();
    }

    public function update(array $data, string $uuid){
        $data['uuid'] = Str::uuid();
        $data['slug'] = Str::slug($data['name']);
        return Product::where('uuid',$uuid)->update($data);
    }

    public function delete(string $uuid){
        $deleteProduct = Product::where('uuid',$uuid)->firstOrFail();
        if ($deleteProduct->image) {
            Storage::disk('public')->delete('images/'.$deleteProduct->image);
        }
        return $deleteProduct->delete();
    }

    public function getDatatable(){
        $product = Product::get();
        return DataTables::of($product)
        ->addIndexColumn()
        ->addColumn('image', function ($item) {
            return '<div class="text-center">
                        <a href="'.asset('storage/images/'.$item->image).'">
                            <img src="'.asset('storage/images/'.$item->image).'" alt="image" class="img-fluid" />
                        </a>
                        </div>';
            })
        ->addColumn('action', function ($row) {
            return '<div class="text-center">
                    <button class="btn btn-success btn-sm" data-id="' . $row->uuid . '" onClick="editModal(this)">Edit</button>
                    <button class="btn btn-danger btn-sm" data-id="' . $row->uuid . '" onClick="deleteModal(this)">Delete</button>
                </div>';
        })
        ->rawColumns(['image', 'action'])
        ->make(true);
    }

    }

?>
