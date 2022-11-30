@extends('dashboard.layout.master')
@section('title','Create Student Data')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
                <h6>Create Students Data</h6>
                <a href="{{route('indexstudent')}}" class="btn btn-success">Back</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <form action="{{route('storestudent')}}" class="p-3 pt-0" method="POST">
                    @csrf
                    <div class="mb-3 mt-3">
                        <label for="name" class="form-label">Name :</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{old('name', '')}}">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="email" class="form-label">Email :</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="{{old('email', '')}}">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="session" class="form-label">Session :</label>
                        <select class="form-select" name="session_id">
                            @foreach ($sessions as $session)
                                <option value="{{$session->id}}" {{$session['id'] == old('session_id') ? 'selected' : ''}}>{{$session->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="password" class="form-label">Password :</label>
                        <input type="text" class="form-control" id="password" placeholder="Enter Password" name="password" value="{{old('password', '')}}">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="nis" class="form-label">NIS :</label>
                        <input type="text" class="form-control" id="nis" placeholder="Enter NIS" name="nis" value="{{old('nis', '')}}">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="nisn" class="form-label">NISN :</label>
                        <input type="text" class="form-control" id="nisn" placeholder="Enter NISN" name="nisn" value="{{old('nisn', '')}}">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

