<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Material;
use App\Tag;
use Auth;
use App\Design;
use App\DesignImage;
use App\User;
use App\DesignerRate;
use App\DesignVote;
use App\Http\Requests\StoreDesignsRequest;
use App\DesignComment;
use App\CommentReply;
use Redirect;
use DB;
use App\Notifications\UserNotifications;

class DesignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $desings=Design::Accepted()->paginate(9, ['*'], 'pages');
        $maxPrice=Design::max('price')+1;
        $minPrice=Design::min('price')-1;
        $tags=Tag::all();
        $materials=Material::all();
        $categories=['women','men','kids','teenagers'];
        if($request->ajax())
        {
            $minPrice=$request->min;
            $maxPrice=$request->max;
            $category=$request->category;
            $sortType=$request->sortType;
            $tag=$request->tag;
            $material=$request->material;
            return $this->designs_filtered($minPrice,$maxPrice,$category,$sortType,$tag,$material);
        }
        return view('designs.index',compact('categories','materials','tags','desings','maxPrice','minPrice'));
    }

    public function designs_filtered($minPrice,$maxPrice,$category,$sortType,$tag,$material)
    {
            $filteredDesigns=Design::Accepted()->whereBetween('price',[$minPrice,$maxPrice]);
            if($category)
            {
                $filteredDesigns->where('category','=',$category);
            }
            if($tag)
            {
                $filteredDesigns->whereHas('tag', function($query) use ($tag) {
                    $query->where('name','=',$tag);});
            }
            if($material)
            {
               $filteredDesigns->whereHas('materials', function($query) use ($material) {
                   $query->where('name','=',$material);});
            }
            if($sortType)
            {
                if($sortType == 'Top Rated')
                {
                    $filteredDesigns->orderBy('total_likes', 'desc');
                }
                 else if($sortType == 'Latest')
                {
                     $filteredDesigns->orderBy('created_at', 'desc');
                }
            }
            $desings=$filteredDesigns->paginate(9, ['*'], 'filteredPages');
            return view('designs.partials.designs',compact('desings'));
    }

    public function search(Request $request)
    {
        $SearchWord=$request->word;
        $designs=Design::whereHas('tag', function($query) use ($SearchWord) {$query->where(DB::raw('lower(name)'), "LIKE", strtolower($SearchWord)."%");})->where('is_verified','=','accepted')->orWhere(DB::raw('lower(category)'), "LIKE", strtolower($SearchWord)."%")->paginate(9);
        return view('designs.SearchResult',compact('designs'));
        //
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function category($type = null)
    // {
    //     // $desings=Design::paginate(9);
    //     if($type != null)
    //     {
    //         $desings=Design::where('category','=',$type)->where('is_verified','=','accepted')->paginate(6, ['*'], 'filtered');
    //         $maxPrice=Design::all()->max('price');
    //         $minPrice=Design::all()->min('price');
    //         $tags=Tag::all();
    //         $materials=Material::all();
    //         $categoryFiltered=True;
    //         $categoryType=$type;
    //         return view('designs.index',compact('categoryType','categoryFiltered','materials','tags','desings','maxPrice','minPrice'));
    //     }
        
    //     //
    // }


    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function vote(Request $request)
    // {
    //     // echo $id;
    //     $design=Design::find($request->design_id);
    //     if($request->vote == "add")
    //     {
    //        DesignVote::create(['design_id'=>$design->id,
    //         'user_id'=>Auth::id()]);
    //         $design->total_likes =$design->total_likes +1;
    //         $design->save(); 
    //     }
    //     else if($request->vote == "remove")
    //     {
    //         $vote=DesignVote::all()->where('design_id','=',$design->id)->first();
    //         $vote->delete();
    //         $design->total_likes =$design->total_likes -1;
    //         $design->save(); 
    //     }
        
    //     echo $design->total_likes;
    // }
    

    // public function commentReply(Request $request,$id)
    // {
    //     $body=$request->Reply_body;
    //     $user_id=Auth::id();
    //     $reply=CommentReply::create(['body' => $body,
    //     'user_id'=>$user_id,
    //     'comment_id' => $id
    //     ]);
    //     $user=Auth::user();
    //     return response()->json([
    //         'reply' => $reply,
    //         'user' => $user
    //     ]);
    // }
    
    // public function comment(Request $request)
    // {
    //     $design_id=$request->design_id;
    //     $body=$request->comment_body;
    //     $user_id=Auth::id();
    //     $comment=DesignComment::create([
    //         'user_id'=>$user_id,
    //         'design_id'=>$request->design_id,
    //         'body'=>$request->comment_body
    //     ]);
    //     $comment->{'user_image'}=$comment->user->image;
    //     $comment->{'user_name'}=$comment->user->name;
    //      return response()->json([
    //         'comment' => $comment
    //     ]);
    // }
    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     $this->authorize('create', Design::class);
    //     $design = new Design();
    //     $designMaterial=Material::all();
    //     $tags=Tag::all();
    //     return view('designs.create',compact('designMaterial','design','tags'));
        
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreDesignsRequest $request)
    // {
    //     $validated = $request->validated();
    //     if($request->hasFile('images') && $request->hasFile('sourceFile') )
    //     {
    //         $file=$request->file('sourceFile');
    //         $filePath = $file->store('Files','public');
    //         $design= Design::create(['title'=> $request->title,
    //                     'description'=>$request->description,
    //                     'price'=>$request->price,
    //                     'category'=>$request->category,
    //                     'designer_id'=>Auth::id(),
    //                     'source_file'=> $filePath,
    //                     'tag_id'=>$request->tag_id
    //         ]);
    //         $design->materials()->attach($request->Material);
    //         foreach ($request->images as $image) {
    //                     $filename = $image->store('Designs','public');
    //                     DesignImage::create([
    //                     'design_id' => $design->id,
    //                     'image' => $filename
    //                 ]);
    //         }
    //         return redirect("design/".$design->id)->with('success','Design added successfuly,please wait while your design is verified .');
            
    //     }
    // }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $design = Design::findOrFail($id);
        $tag=$design->tag->name;
        $voted="False";
        $designImages=DesignImage::all()->where('design_id','=',$id);
        $RelatedDesigns=Design::whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->where('id','!=',$design->id)->get();
        $votes=$design->votes;
        foreach ($votes as $vote) {
            if($vote->user_id == Auth::id())
            {
                $voted="True";
                break;
            }
        }
        $comments=$design->comments;
        return view('designs.show',compact('comments','voted','RelatedDesigns','design','designImages'));
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     $design = Design::find($id);
    //     $this->authorize('update', $design);
    //     $designMaterial=Material::all();
    //     $tags=Tag::all();
    //     $designImages=DesignImage::all()->where('design_id','=',$id);
    //     return view('designs.edit',compact('design','designMaterial','tags','designImages'));
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request,Design $design)
    // {
    //     $this->authorize('update', $design);
    //      $validatedData = $request->validate([
    //         'title' => 'required',
    //         'price' => 'required',
    //         'description' => 'required',
    //         'category' => 'required',
    //         'sourceFile'  => 'sometimes|mimes:pdf|max:10000',
    //         'images' => 'sometimes',
    //         'images.*' => 'mimes:jpg,jpeg,png',
    //         'tag_id' => 'required',
    //         'Material' => 'required'
    //     ]);
    //     $design=Design::find($design->id);
    //     $design->update($request->all());
    //     $design->is_verified="pending";
    //     if ( $request->hasFile('sourceFile') ) 
    //     {
    //         $file=$request->file('sourceFile');
    //         $filePath = $file->store('Files','public');
    //         $design ->source_file=$filePath ;   
    //     }
    //     $design->materials()->sync($request->Material);
    //     // Images
    //     if($request->hasFile('images'))
    //     {
    //         foreach ($design->images as $image) {

    //                     $image->delete();
    //         }

    //         foreach ($request->images as $image) {
    //              $filename = $image->store('Designs','public');
    //                     DesignImage::create([
    //                     'design_id' => $design->id,
    //                     'image' => $filename
    //                     ]);
                        
    //         }              
    //     }
    //     $design->save();
    //     return redirect("design/".$design->id)->with('success','Design Upadated Successfuly');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    //     $design = Design::findOrFail($id);
    //      $this->authorize('delete', $design);
    //     $design->delete();
    //     return redirect('designer/'.Auth::id())->with('success','Design deleted successfully ');
    // }

















    // public function filterBy(Request $request)
    // {
    //     $filterType=$request->filterType ;
    //     $category=$request->category;
    //     $minPrice=$request->minPrice;
    //     $minarr=explode('$', $minPrice);
    //     $min=(int)$minarr[1];
    //     $maxPrice=$request->maxPrice;
    //     $maxarr=explode('$', $maxPrice);
    //     $max=(int)$maxarr[1];
    //     $userRole="";
    //     $tag=$request->tag;
    //     $userExist=Auth::check();
    //     $material=$request->material;
    //     $newArray=[];
    //     $designs=[];

    //     if($filterType && !$category && !$tag && !$material )
    //     {  
    //         if($filterType == 'Top Rated')
    //         {
    //             $designs=Design::all()->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs=Design::all()->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->sortByDesc('created_at');
    //         }
    //     }
    //     else if(!$filterType && $category && !$tag && !$material)
    //     {
    //          $designs=Design::all()->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->where('category',$category);

    //     }
    //     else if($filterType && $category && !$tag && !$material)
    //     {
    //         if($filterType == 'Top Rated')
    //         {
    //             $designs=Design::all()->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->where('category',$category)->sortByDesc('total_likes');

    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs=Design::all()->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->where('category',$category)->sortByDesc('created_at');
    //         }
    //     }
    //     else if(!$filterType & !$category && !$tag && !$material)
    //     {
    //         $designs=Design::all()->where('is_verified','=','accepted')->whereBetween('price',[$min,$max]);
    //     }

    //     else if($filterType && $category && $tag && !$material)
    //     {
    //         if($filterType == 'Top Rated')
    //         {
    //            $designs= Design::whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('category',$category)->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs= Design::whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('category',$category)->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('created_at');
                
    //         }
    //     }
    //     else if( !$filterType && $category && $tag && !$material)
    //     {
    //         $designs= Design::whereHas('tag', function($query) use ($tag){$query->where('name','=',$tag);})->where('category',$category)->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get();
    //     }
    //     else if( $filterType && !$category && $tag && !$material)
    //     {
    //          if($filterType == 'Top Rated')
    //         {
    //            $designs= Design::whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs= Design::whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('created_at');
                
    //         }
    //     }
    //     else if( !$filterType && !$category && $tag && !$material)
    //     {
    //         $designs= Design::whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get();
    //     }

    //     else if( !$filterType && !$category && !$tag && $material)
    //     {

    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get();
    //     }
    //     else if( $filterType && !$category && !$tag && $material)
    //     {
    //          if($filterType == 'Top Rated')
    //         {
    //            $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('created_at');
                
    //         }
    //     }
    //     else if( !$filterType && $category && !$tag && $material)
    //     {

    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->where('category',$category)->whereBetween('price',[$min,$max])->get();
    //     }
    //     else if( !$filterType && $category && !$tag && $material)
    //     {

    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->where('category',$category)->whereBetween('price',[$min,$max])->get();
    //     }
    //     else if( $filterType && $category && !$tag && $material)
    //     {
    //          if($filterType == 'Top Rated')
    //         {
    //            $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->where('category',$category)->whereBetween('price',[$min,$max])->get()->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->where('category',$category)->whereBetween('price',[$min,$max])->get()->sortByDesc('created_at');
                
    //         }
    //     }
    //     else if( !$filterType && !$category && $tag && $material)
    //     {
    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->where('is_verified','=','accepted')->whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->whereBetween('price',[$min,$max])->get();
    //     }
    //     else if( !$filterType && $category && $tag && $material)
    //     {
    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('category',$category)->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get();
    //     }
    //     else if( $filterType && !$category && $tag && $material)
    //     {
    //         if($filterType == 'Top Rated')
    //         {
    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->get()->sortByDesc('created_at');
    //         }
    //     }
    //     else if( $filterType && $category && $tag && $material)
    //     {
           
    //         if($filterType == 'Top Rated')
    //         {
    //         $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->where('category',$category)->get()->sortByDesc('total_likes');
    //         }
    //         else if($filterType == 'Latest')
    //         {
    //             $designs= Design::whereHas('materials', function($query) use ($material) {$query->where('name','=',$material);})->whereHas('tag', function($query) use ($tag) {$query->where('name','=',$tag);})->where('is_verified','=','accepted')->whereBetween('price',[$min,$max])->where('category',$category)->get()->sortByDesc('created_at');
    //         }
    //     }

    //         foreach($designs as $design){ 
    //         $design->{'image'}=$design->images()->first()->image;
    //         $design->{'designer'}=$design->designer->name;
    //         array_push($newArray,$design);
    //         }

        
    //     if($userExist)
    //     {
    //         $userRole=Auth::user()->role;
            
    //     }
    //     return response()->json([
    //         'designs' => $newArray,
    //         'user_exist'=>$userExist,
    //         'user_role'=>$userRole,
    //     ]);
    // }
}
