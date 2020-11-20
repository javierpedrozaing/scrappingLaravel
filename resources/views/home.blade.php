@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col card">
        <div class="card-header">
            <h4 class="card-title">Categorías</h4>
            <p class="category">Selecciona una categoría</p>
        </div>
        <div class="card-content">
            <div class="row">
                <ul>
                    @foreach($categories as $cat)
                        <li><a href="/category/<?= ucwords($cat->nombre) ?>"> {{$cat->nombre}}</a></li>
                    @endforeach                
                </ul>                                    
            </div>
        </div>
    </div>

    <div class="col card">
        <div class="card-header">
            <h4 class="card-title">Sub Categorías</h4>
            <p class="category">Selecciona una subcategoría</p>
        </div>
        <div class="card-content">
            <div class="row">
            <ul>
                @foreach($subcategories as $subcat)
                    <li><a href="/category/<?= ucwords($subcat->nombre) ?>">{{$subcat->nombre}}</a></li>
                @endforeach                            
            </ul>
            
            </div>
        </div>
    </div>
</div>
@endsection
