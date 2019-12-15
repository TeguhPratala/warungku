@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit warung</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {!! Form::open(['url' => 'warung/' . $data->id, 'method' => 'PUT']) !!}
                        <div class="form-group">
                            {!! Form::label('nama', 'Nama warung') !!}
                            {!! Form::text('nama', $data->nama, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('alamat', 'Alamat') !!}
                            {!! Form::textarea('alamat', $data->alamat, ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                        {!! Form::submit('Ubah', ['class' => 'btn btn-primary float-right']) !!}

                    {!! Form::close() !!}
                    
                    
                </div>
                @if ($errors->any())
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection