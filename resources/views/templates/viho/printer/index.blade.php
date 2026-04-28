@extends('templates.viho.layout')
@section('title', __('printer.printers'))

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('printer.printers')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">@lang('printer.all_your_printer')</h5>
        <div>
          <a class="btn btn-primary btn-sm" href="{{ route('ai-template.printers.create') }}">
            @lang('printer.add_printer')
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex overflow-auto w-100">
          <table class="table table-bordered table-striped" id="printer_table">
            <thead>
              <tr>
                <th>@lang('printer.name')</th>
                <th>@lang('printer.connection_type')</th>
                <th>@lang('printer.character_per_line')</th>
                <th>@lang('printer.profile')</th>
                <th>@lang('messages.action')</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable for printer table
    $('#printer_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/ai-template/printers',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'connection_type', name: 'connection_type' },
            { data: 'char_per_line', name: 'char_per_line' },
            { data: 'capability_profile', name: 'capability_profile' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                targets: 4,
                orderable: false,
                searchable: false,
            },
        ],
        drawCallback: function(settings) {
            // Initialize feather icons after table draw
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        },
        initComplete: function() {
            // Initialize feather icons on initial load
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            var api = this.api();
            api.on('draw.dt', function() {
                // Initialize feather icons after each draw
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        }
    });
});
</script>
@endpush