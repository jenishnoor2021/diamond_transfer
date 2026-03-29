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

    <h4>Add Broker</h4>

    <form method="POST" id="addBroker" action="{{ route('admin.broker.store') }}">

      @csrf

      <div class="row">

        <div class="col-md-4">
          <label>Location <span class="text-danger">*</span></label>

          <select name="location_id" class="form-control">

            @foreach($locations as $location)

            <option value="{{$location->id}}">
              {{$location->name}}
            </option>

            @endforeach

          </select>
        </div>


        <div class="col-md-4">
          <label>Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control">
        </div>


        <div class="col-md-4">
          <label>Phone</label>
          <input type="number" name="phone" class="form-control">
        </div>


        <div class="col-md-4 mt-3">
          <label>Email</label>
          <input type="text" name="email" class="form-control">
        </div>


        <div class="col-md-4 mt-3">
          <label>City</label>
          <input type="text" name="city" class="form-control">
        </div>

      </div>

      <button class="btn btn-success mt-3">
        Save Broker
      </button>

    </form>

  </div>
</div>

@endsection

@section('script')

<script>
  $(document).ready(function() {

    $("#addBroker").validate({

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