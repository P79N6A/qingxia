@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    @component('test.modal',['id'=>'qweqwe','title'=>'let\'s rock'])
                        @slot('body')
                            <p>asdasdasd</p>
                        @endslot
                        @slot('footer')
                            <a>确认</a>
                        @endslot
                    @endcomponent
                    @each('test.index',$jobs,'job')
                </div>
            </div>
        </div>
    </div>
@endsection
