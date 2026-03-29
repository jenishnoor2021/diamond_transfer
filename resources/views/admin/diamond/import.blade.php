@extends('layouts.admin')
@section('style')
<style>
  #btnLoader {
    font-size: 14px;
    color: #0d6efd;
  }

  #btnLoader i {
    margin-right: 5px;
  }

  #uploadBtn[disabled] {
    pointer-events: none;
    opacity: 0.7;
  }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0 font-size-18">Diamonds Import</h4>

      </div>
      <a href="{{asset('assets/diam-import-csv-format.csv')}}" download="download" class="btn btn-outline-primary btn-sm waves-effect waves-light">
        <i class="fa fa-download" aria-hidden="true"></i> Import csv Format
      </a>
    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        {!! Form::open([
        'method' => 'POST',
        'action' => 'AdminDiamondController@import',
        'files' => true,
        'class' => 'form-horizontal',
        'name' => 'importDiamond',
        'id' => 'importDiamond',
        ]) !!}
        @csrf

        <div class="col-md-4 mb-3">
          <label>File <span class="text-danger">*</span></label>
          <input type="file" name="file" class="form-control" required>
        </div>

        <div class="d-flex align-items-center gap-2">
          <button type="submit" id="uploadBtn" class="btn btn-primary mt-1">
            Upload CSV
          </button>

          <span id="btnLoader" style="display:none;">
            <i class="fa fa-spinner fa-spin"></i> Uploading...
          </span>

          <a class="btn btn-light w-md" href="{{ URL::to('/admin/diamonds') }}">Back</a>
        </div>

        </form>

      </div>
    </div>

  </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
  $(document).ready(function() {

    $.validator.addMethod('filesize', function(value, element, param) {
      if (element.files.length == 0) return true;
      return element.files[0].size <= param;
    }, 'File size must be less than 2MB');

    $("#importDiamond").validate({
      rules: {
        file: {
          required: true,
          extension: "csv|xlsx",
          filesize: 2097152
        }
      },

      messages: {
        file: {
          required: "Please select a file",
          extension: "Only CSV or Excel file allowed"
        }
      },

      submitHandler: function(form) {

        $("#uploadBtn").prop("disabled", true);
        $("#btnLoader").show();

        form.submit();
      }
    });

  });
</script>
@endsection