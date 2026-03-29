@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex justify-content-between">
      <h4 class="mb-0">{{$location}} Memos</h4>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">

    <table id="datatable" class="table table-bordered">

      <thead>

        <tr>
          <th>Action</th>
          <th>Memo No</th>
          <th>Broker</th>
          <th>Date</th>
        </tr>

      </thead>

      <tbody>

        @foreach($memos as $memo)

        <tr>

          <td>

            <div class="d-flex gap-2">

              <a href="{{route('admin.broker.memo.show',$memo->id)}}"
                class="btn btn-outline-primary btn-sm waves-effect waves-light">
                <i class="fa fa-eye"></i>
              </a>

              <form method="POST"
                action="{{route('admin.broker.memo.delete',$memo->id)}}"
                class="deleteMemoForm m-0">

                @csrf
                @method('DELETE')

                <button type="submit"
                  class="btn btn-outline-danger btn-sm waves-effect waves-light">
                  <i class="fa fa-trash"></i>
                </button>

              </form>

              <a href="{{ route('admin.broker.memo.print', $memo->id) }}"
                target="_blank"
                class="btn btn-outline-success btn-sm waves-effect waves-light">
                <i class="fa fa-print" aria-hidden="true"></i>
              </a>

            </div>

          </td>
          <td>{{$memo->memo_number}}</td>

          <td>{{$memo->broker->name}}</td>

          <td>{{$memo->issue_date}}</td>

        </tr>

        @endforeach

      </tbody>

    </table>

  </div>
</div>

@endsection

@section('script')

<script>
  document.querySelectorAll('.deleteMemoForm').forEach(function(form) {

    form.addEventListener('submit', function(e) {

      let confirmDelete = confirm(
        "Are you sure?\n\nDeleting this memo will also delete ALL related memo diamonds."
      );

      if (!confirmDelete) {
        e.preventDefault();
      }

    });

  });
</script>

@endsection