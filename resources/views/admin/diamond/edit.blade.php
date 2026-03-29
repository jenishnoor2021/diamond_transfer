@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Diamond</h4>
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
                'action' => ['AdminDiamondController@update',$diamond->id],
                'class' => 'form-horizontal',
                'id' => 'editDiamond',
                'name' => 'editDiamond',
                ]) !!}

                @csrf

                <div class="row">

                    <!-- <div class="col-md-4 mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode_number" class="form-control">
                        </div> -->

                    <div class="col-md-4 mb-3">
                        <label>Stock No <span class="text-danger">*</span></label>
                        <input type="text" name="stock_no" class="form-control" value="{{$diamond->stock_no}}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Certificate No <span class="text-danger">*</span></label>
                        <input type="text" name="certificate_no" class="form-control" value="{{$diamond->certificate_no}}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Weight <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="weight" class="form-control" value="{{$diamond->weight}}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Shape</label>
                        <select name="shape" class="form-control">
                            <option value="">Select Shape</option>
                            @foreach($shapes as $shape)
                            <option value="{{ $shape->shape_type }}"
                                {{ trim($diamond->shape) == trim($shape->shape_type) ? 'selected' : '' }}>
                                {{ $shape->shape_type }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Color</label>
                        <select name="color" class="form-control">
                            <option value="">Select Color</option>
                            @foreach($colors as $color)
                            <option value="{{ $color->c_name }}" {{ $diamond->color == $color->c_name ? 'selected' : '' }}>
                                {{ $color->c_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Clarity</label>
                        <select name="clarity" class="form-control">
                            <option value="">Select Clarity</option>
                            @foreach($clarity as $c)
                            <option value="{{ $c->name }}" {{ $diamond->clarity == $c->name ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Cut Grade</label>
                        <select name="cut_grade" class="form-control">
                            <option value="">Select Cut</option>
                            @foreach($cut as $c)
                            <option value="{{ $c->name }}" {{ $diamond->cut_grade == $c->name ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Polish</label>
                        <select name="polish" class="form-control">
                            <option value="">Select Polish</option>
                            @foreach($polish as $p)
                            <option value="{{ $p->name }}" {{ $diamond->polish == $p->name ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Symmetry</label>
                        <select name="symmetry" class="form-control">
                            <option value="">Select Symmetry</option>
                            @foreach($symmetry as $s)
                            <option value="{{ $s->name }}" {{ $diamond->symmetry == $s->name ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Price Per Carat <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_carat" class="form-control" value="{{ $diamond->price_per_carat }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Lab</label>
                        <input type="text" name="lab" value="{{ $diamond->lab }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Availability</label>
                        <input type="text" name="availability" value="{{ $diamond->availability }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fancy Color</label>
                        <input type="text" name="fancy_color" value="{{ $diamond->fancy_color }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fancy Color Intensity</label>
                        <input type="text" name="fancy_color_intensity" value="{{ $diamond->fancy_color_intensity }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fancy Color Overtone</label>
                        <input type="text" name="fancy_color_overtone" value="{{ $diamond->fancy_color_overtone }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Fluorescence Intensity</label>
                        <input type="text" name="fluorescence_intensity" value="{{ $diamond->fluorescence_intensity }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Measurements</label>
                        <input type="text" name="measurements" value="{{ $diamond->measurements }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Depth %</label>
                        <input type="text" name="depth_percent" value="{{ $diamond->depth_percent }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Table %</label>
                        <input type="text" name="table_percent" value="{{ $diamond->table_percent }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Country</label>
                        <input type="text" name="country" value="{{ $diamond->country }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>State</label>
                        <input type="text" name="state" value="{{ $diamond->state }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>City</label>
                        <input type="text" name="city" value="{{ $diamond->city }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Image URL</label>
                        <input type="text" name="image_url" value="{{ $diamond->image_url }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Video URL</label>
                        <input type="text" name="video_url" value="{{ $diamond->video_url }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Growth Type</label>
                        <input type="text" name="growth_type" value="{{ $diamond->growth_type }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="in_stock" {{ $diamond->status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
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
        $("#editDiamond").validate({
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