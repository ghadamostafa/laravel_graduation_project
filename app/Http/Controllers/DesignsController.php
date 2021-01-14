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
use Illuminate\Support\Facades\Storage;

class DesignsController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Design::class, 'design');
    }


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
            return $this->filter_designs($request->min,$request->max,
            $request->category, $request->sortType,
            $request->tag, $request->material);
        }
        return view('designs.index',compact('categories','materials','tags','desings','maxPrice','minPrice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $design = new Design();
        return view('designs.create',compact('design'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDesignsRequest $request)
    {
            $validated = $request->validated();
            $filePath = $request->file('sourceFile')->store('Files');
            $request['designer_id']=Auth::id();
            $request['source_file']=$filePath;
            $design= Design::create($request->except(['Material','images','sourceFile']));
            $design->materials()->syncWithoutDetaching($request->Material);
            $this->storeNewImages($request->images,$design) ;           
            return redirect("designs/".$design->id)->with('success','Design added successfuly,please wait while your design is verified .');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Design $design)
    {
        return view('designs.edit',compact('design'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDesignsRequest $request,Design $design)
    {
        $validatedData = $request->validated();
        $design->update($request->except(['Material','images','sourceFile']));
        $design->is_verified="pending";
        $file=$request->file('sourceFile');
        if ($file) 
        {   
            $design ->source_file=$file->store('Files') ;   
        }
        $design->materials()->syncWithoutDetaching($request->Material);
        // Images
        if($request->hasFile('images'))
        {
            $this->deletePreviousImages($design);
            $this->storeNewImages($request->images,$design) ;           
        }
        $design->save();
        return redirect("designs/".$design->id)->with('success','Design Upadated Successfuly');
    }

       /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Design $design)
    {
        //write a middleware for design to show it if it is accepted or it is opened by the owner
        $tag=$design->tag->name;
        $designImages=DesignImage::where('design_id','=',$design->id);
        $RelatedDesigns=Design::whereHas('tag', function($query) use ($tag) {
            $query->where('name','=',$tag);})->Accepted()
            ->where('id','!=',$design->id)->get();
        $comments=$design->comments;
        $current_user_votes=$design->votes->where('user_id',Auth::user()->id);
        ($current_user_votes->count() >0) ? $userVoted=True: $userVoted =FALSE;    
        return view('designs.show',compact('comments',
        'userVoted',
        'RelatedDesigns',
        'design',
        'designImages'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Design $design)
    {
        $design->delete();
        return redirect('designer/'.Auth::id())->with('success','Design deleted successfully ');
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vote(Request $request)
    {
        $design=Design::find($request->design_id);
        if($request->vote_action == "add")
        {
            DesignVote::create(['design_id'=>$design->id, 'user_id'=>Auth::id()]);
            $design->total_likes += 1; 
        }
        else if($request->vote_action == "remove")
        {
            DesignVote::where('design_id','=',$design->id)->first()->delete();
            $design->total_likes -= 1;    
        }
        $design->save(); 
        echo $design->total_likes;
    }

    

    public function filter_designs($minPrice,$maxPrice,$category,$sortType,$tag,$material)
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

    public function deletePreviousImages($design)
    {
        foreach ($design->images as $image) {
            Storage::delete($image->image);
            $image->delete();
        }
    }
    public function storeNewImages($images,$design)
    {
        foreach ($images as $image) {
            $filename = $image->store('Designs');
            DesignImage::create(['design_id' => $design->id,'image' => $filename]);       
        }
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

    


}
