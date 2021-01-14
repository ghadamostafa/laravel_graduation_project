<?php

namespace App\Http\ViewComposers;

use App\Material;
use App\Repositories\UserRepository;
use App\Tag;
use Illuminate\View\View;

class DesignComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $designMaterials=Material::all();
        $tags=Tag::all();
        $categories=['women','men','kids','teenagers'];

        $view->with([   'designMaterials' => $designMaterials,
                        'tags' =>$tags,
                        'categories' => $categories ]);
    }
}