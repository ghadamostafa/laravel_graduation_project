@extends('layouts.admin')
    
@section('content')
@if (session()->has('message'))
    <div class="alert alert-".{{color}} role="alert">
        <strong>{{message}}</strong>{{session()->get('message')}}
    </div>
@endif
@csrf
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Verify {{ trans($state) }} {{ trans($role) }}</h1>
<p class="mb-4">This is a list of {{ trans($role) }}s which needs to be verified.</a>.</p>

<!-- DataTales Example -->

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Designers Table</h6>
  </div>
  @if($role == "designer")
  <div class="container box">
        <div class="panel panel-default">
            <div class="panel-body">
            <input type = "text" name="search"  id = "search" onkeyup="inputchange(this.value,'{{$state}}')" placeholder="Search For Designers" class="form-control" /> 
            </div>  
        </div>
    </div>
    @endif
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Website</th>
            <th scope="col">Logo</th>
            <th scope="col">Document</th>
            @if($state === 'rejected' || $state === 'pending')
            <th scope="col">Accept</th>
            @endif
            @if($state === 'accepted' || $state === 'pending')
            <th scope="col">Reject</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($pending_users as $user)
            <tr>
            <th class="align-middle" scope="row">{{$loop->iteration}}</th>
                <td class="align-middle">{{$user->name}}</td>
                <td class="align-middle">{{$user->email}}</td>
                <td class="align-middle">{{$user->website}}</td>
                <td class="align-middle"><img class="img-thumbnail" style="width: 300px; height:200px" src={{asset('storage/'.$user->image)}} alt="" srcset=""></td>
                <td class="align-middle">
                    <a target="_blank" href="{{route('admin.view_user_document',$user->id)}}" class="btn btn-success ">Preview Document</a>
                </td>
                @if($state === 'rejected' || $state === 'pending')
                    <td class="align-middle">
                        <button type="button" class="btn btn-primary" onclick="change_verification(this,{{$user->id}},'accepted')">Accept</button>
                    </td>
                @endif
                @if($state === 'accepted' || $state === 'pending')
                    <td class="align-middle">
                        <button type="button" class="btn btn-danger" onclick="change_verification(this,{{$user->id}},'rejected')">Reject</button>
                    </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="alert alert-danger" role="alert">
                        <strong>No {{$state}} {{$role}} yet</strong>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
</table>
{{ $pending_users->links() }}

</div>

@endsection
@push('scripts')
    <script>
        function change_verification(btn,user_id,status) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            answer = confirm('are you sure ?')
            if(answer){
                $.ajax({
                    type:'POST',
                    data:{
                        user_id,
                        status
                    },success:function (data) {
                        console.log("in ver ch");
                        console.log(data);
                             
                        $(btn).parents('tr').hide('1000');
                        alert(data);
                    },error:function (responseJSON){
                        console.log("error");
                        alert(responseJSON.responseText);
                    }
                })
            }
        }
    </script>
    <script>
        // $(document).ready(function(){
            // fetch_designer_data('',state);


            function fetch_designer_data(query = '',state)
            { 
                console.log(query);
                $.ajaxSetup({
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                });
                $.ajax
                ({
                    url:"{{ route('livesearch') }}",
                    method:"GET",
                    data:{query:query,state},
                    dataType: 'json',
                    success:function(data){
                        console.log("success");
                        console.log( data);
                        $('tbody').html(data.table_data);
                        $("#total_records").text(data.total_data);
                    },
                    error:function (responseJSON){
                        console.log("erroe");
                        // alert(responseJSON.responseText);
                    }
                })
            }
            function inputchange(value,state)
            {
                var query = value;
                fetch_designer_data(query,state);
            }
            // $(document).on('keyup','#search',function(){
            //     console.log("keyup");
            //     var query = $(this).val();
            //     fetch_designer_data(query);
            // });
        // });
    </script>
@endpush