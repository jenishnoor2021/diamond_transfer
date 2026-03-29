@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">

    <div class="page-title-box d-sm-flex justify-content-between">

      <h4 class="mb-0">Brokers</h4>

      <a href="{{ route('admin.broker.create') }}" class="btn btn-primary">
        Add Broker
      </a>

    </div>

  </div>
</div>


<div class="card">
  <div class="card-body">

    @include('includes.flash_message')

    <table class="table table-bordered">

      <thead>
        <tr>
          <th>#</th>
          <th>Action</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Location</th>
        </tr>
      </thead>

      <tbody>

        @foreach($brokers as $broker)

        <tr>
          <td>{{$loop->iteration}}</td>
          <td>
            <a href="{{ route('admin.broker.edit',$broker->id) }}"
              class="btn btn-outline-primary waves-effect waves-light">
              <i class="fa fa-edit"></i>
            </a>
            <a href="{{ route('admin.broker.delete',$broker->id) }}"
              class="btn btn-outline-danger waves-effect waves-light"
              onclick="return confirm('Delete broker?')">
              <i class="fa fa-trash"></i>
            </a>
          </td>

          <td>{{$broker->name}}</td>
          <td>{{$broker->phone}}</td>
          <td>{{$broker->location->name ?? '-'}}</td>
        </tr>

        @endforeach

      </tbody>

    </table>

  </div>
</div>

@endsection