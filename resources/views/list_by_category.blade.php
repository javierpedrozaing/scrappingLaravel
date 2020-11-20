@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col card">        
        @foreach($announcements as $ads)
            {!! $ads !!}
        @endforeach
    </div>    
</div>
@endsection
