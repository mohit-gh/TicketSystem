@extends('layouts.app')

@section('content')
    <h1>Create Ticket</h1>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Title <span style="color:red">*</span></label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description<span style="color:red">*</span></label>
            <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
