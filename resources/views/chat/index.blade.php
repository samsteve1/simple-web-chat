@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <div class="container">
                       <div class="row">
                           <div class="col-md-8">
                               <chat></chat>
                           </div>

                           <div class="col-md-4">
                               <chat-users></chat-users>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
