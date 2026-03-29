@extends('layouts.admin')
@section('style')
<style>
  label.error {
    color: red;
    font-size: 13px;
  }
</style>
@endsection
@section('content')

<div class="card">
  <div class="card-body">

    <h4>Edit Broker</h4>

    <form method="POST" id="editBroker" action="{{ route('admin.broker.update',$broker->id) }}">

      @csrf

      <div class="row">

        <div class="col-md-4">
          <label>Location <span class="text-danger">*</span></label>

          <select name="location_id" class="form-control">

            @foreach($locations as $location)

            <option value="{{$location->id}}"
              @if($location->id==$broker->location_id) selected @endif>

              {{$location->name}}

            </option>

            @endforeach

          </select>

        </div>


        <div class="col-md-4">
          <label>Name <span class="text-danger">*</span></label>

          <input type="text"
            name="name"
            value="{{$broker->name}}"
            class="form-control">

        </div>


        <div class="col-md-4">
          <label>Phone</label>

          <input type="number"
            name="phone"
            value="{{$broker->phone}}"
            class="form-control">

        </div>

        <div class="col-md-4 mt-3">
          <label>Email</label>
          <input type="text" name="email" value="{{$broker->email}}" class="form-control">
        </div>

        <div class="col-md-4 mt-3">
          <label>City</label>
          <input type="text" name="city" value="{{$broker->city}}" class="form-control">
        </div>

      </div>

      <div class="col-md-4 mt-3">
        <div class="d-flex gap-2 mb-3">
          <button type="submit" class="btn btn-primary w-md">Update Broker</button>
          <a class="btn btn-light w-md" href="{{ URL::to('/admin/broker') }}">Back</a>
        </div>
      </div>

    </form>

  </div>
</div>

@endsection

@section('script')

<script>
  $(document).ready(function() {

    $("#editBroker").validate({

      rules: {

        location_id: {
          required: true
        },

        name: {
          required: true
        },

        phone: {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 10
        },

        email: {
          email: true
        },

        city: {
          required: true
        }

      },

      messages: {

        location_id: {
          required: "Please select location"
        },

        name: {
          required: "Please enter broker name"
        },

        phone: {
          required: "Please enter phone number",
          digits: "Only numbers allowed",
          minlength: "Phone must be 10 digits",
          maxlength: "Phone must be 10 digits"
        },

        email: {
          email: "Enter valid email address"
        },

        city: {
          required: "Please enter city"
        }

      },

      submitHandler: function(form) {
        form.submit();
      }

    });

  });
</script>

@endsection