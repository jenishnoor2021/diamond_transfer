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
            <h4 class="mb-sm-0 font-size-18">Add Diamond</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminDiamondController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'addDiamond',
                'id' => 'addDiamond',
                ]) !!}
                @csrf

                <div class="row">

                    <!-- <div class="col-md-4 mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode_number" class="form-control">
                        </div> -->

                    <div class="col-md-4 mb-3">
                        <label>Stock No <span class="text-danger">*</span></label>
                        <input type="text" name="stock_no" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Certificate No <span class="text-danger">*</span></label>
                        <input type="text" name="certificate_no" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Weight <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="weight" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Shape</label>
                        <select name="shape" class="form-control">
                            <option value="">Select Shape</option>
                            @foreach($shapes as $shape)
                            <option value="{{ $shape->shape_type }}">{{ $shape->shape_type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Color</label>
                        <select name="color" class="form-control">
                            <option value="">Select Color</option>
                            @foreach($colors as $color)
                            <option value="{{ $color->c_name }}">{{ $color->c_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Clarity</label>
                        <select name="clarity" class="form-control">
                            <option value="">Select Clarity</option>
                            @foreach($clarity as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Cut Grade</label>
                        <select name="cut_grade" class="form-control">
                            <option value="">Select Cut</option>
                            @foreach($cut as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Polish</label>
                        <select name="polish" class="form-control">
                            <option value="">Select Polish</option>
                            @foreach($polish as $p)
                            <option value="{{ $p->name }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Symmetry</label>
                        <select name="symmetry" class="form-control">
                            <option value="">Select Symmetry</option>
                            @foreach($symmetry as $s)
                            <option value="{{ $s->name }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Price Per Carat <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_carat" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Lab</label>
                        <input type="text" name="lab" value="IGI" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Availability</label>
                        <input type="text" name="availability" value="Yes" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fancy Color</label>
                        <input type="text" name="fancy_color" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fancy Color Intensity</label>
                        <input type="text" name="fancy_color_intensity" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fancy Color Overtone</label>
                        <input type="text" name="fancy_color_overtone" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fluorescence Intensity</label>
                        <input type="text" name="fluorescence_intensity" value="None" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Measurements</label>
                        <input type="text" name="measurements" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Depth %</label>
                        <input type="text" name="depth_percent" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Table %</label>
                        <input type="text" name="table_percent" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Country</label>
                        <input type="text" name="country" value="India" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>State</label>
                        <input type="text" name="state" value="Gujrat" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>City</label>
                        <input type="text" name="city" value="Surat" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Video URL</label>
                        <input type="text" name="video_url" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Growth Type</label>
                        <input type="text" name="growth_type" value="CVD" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="in_stock">In Stock</option>
                        </select>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="submit" id="uploadBtn" class="btn btn-primary mt-1">
                        Save Diamond
                    </button>

                    <span id="btnLoader" style="display:none;">
                        <i class="fa fa-spinner fa-spin"></i> Uploading...
                    </span>

                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/diamonds') }}">Back</a>
                </div>

                </form>


            </div>
            <!-- end card body -->
        </div>

    </div>
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $("#addDiamond").validate({
            rules: {
                stock_no: {
                    required: true,
                },
                weight: {
                    required: true,
                },
                price_per_carat: {
                    required: true,
                },
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