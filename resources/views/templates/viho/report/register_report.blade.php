@extends('templates.viho.layout')
@section('title', __('report.register_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.register_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])
      {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' =>
      'get', 'id' => 'register_report_filter_form' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('register_user_id', __('report.user') . ':') !!}
            {!! Form::select('register_user_id', $users, null, ['class' => 'form-control select2', 'style' =>
            'width:100%', 'placeholder' => __('report.all_users')]); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('register_status', __('sale.status') . ':') !!}
            {!! Form::select('register_status', ['open' => __('cash_register.open'), 'close' =>
            __('cash_register.close')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder'
            => __('report.all')]); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('register_report_date_range', __('report.date_range') . ':') !!}
            {!! Form::text('register_report_date_range', null , ['placeholder' => __('lang_v1.select_a_date_range'),
            'class' => 'form-control', 'id' => 'register_report_date_range', 'readonly']); !!}
          </div>
        </div>
      </div>
      {!! Form::close() !!}
      @endcomponent
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      @component('components.widget', ['class' => 'box-primary'])
      <div class="d-flex overflow-auto w-100">
        <table class="table table-bordered table-striped" id="register_report_table" style="min-width: 2000px;">
          <thead>
            <tr>
              <th>@lang('report.open_time')</th>
              <th>@lang('report.close_time')</th>
              <th>@lang('sale.location')</th>
              <th>@lang('report.user')</th>
              <th>@lang('cash_register.total_card_slips')</th>
              <th>@lang('cash_register.total_cheques')</th>
              <th>@lang('cash_register.total_cash')</th>
              <th>@lang('lang_v1.total_bank_transfer')</th>
              <th>@lang('lang_v1.total_advance_payment')</th>
              <th>{{$payment_types['custom_pay_1']}}</th>
              <th>{{$payment_types['custom_pay_2']}}</th>
              <th>{{$payment_types['custom_pay_3']}}</th>
              <th>{{$payment_types['custom_pay_4']}}</th>
              <th>{{$payment_types['custom_pay_5']}}</th>
              <th>{{$payment_types['custom_pay_6']}}</th>
              <th>{{$payment_types['custom_pay_7']}}</th>
              <th>@lang('cash_register.other_payments')</th>
              <th>@lang('sale.total')</th>
              <th>@lang('messages.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr class="bg-gray font-17 text-center footer-total">
              <td colspan="4"><strong>@lang('sale.total'):</strong></td>
              <td class="footer_total_card_payment"></td>
              <td class="footer_total_cheque_payment"></td>
              <td class="footer_total_cash_payment"></td>
              <td class="footer_total_bank_transfer_payment"></td>
              <td class="footer_total_advance_payment"></td>
              <td class="footer_total_custom_pay_1"></td>
              <td class="footer_total_custom_pay_2"></td>
              <td class="footer_total_custom_pay_3"></td>
              <td class="footer_total_custom_pay_4"></td>
              <td class="footer_total_custom_pay_5"></td>
              <td class="footer_total_custom_pay_6"></td>
              <td class="footer_total_custom_pay_7"></td>
              <td class="footer_total_other_payments"></td>
              <td class="footer_total"></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
      @endcomponent
    </div>
  </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#register_report_table')) {
      $('#register_report_table').DataTable().destroy();
    }

    $('#register_report_date_range').daterangepicker({
      ranges: ranges,
      autoUpdateInput: false,
      locale: {
        format: moment_date_format,
        cancelLabel: LANG.clear,
        applyLabel: LANG.apply,
        customRangeLabel: LANG.custom_range
      }
    });

    $('#register_report_date_range').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(
        picker.startDate.format(moment_date_format) +
        ' ~ ' +
        picker.endDate.format(moment_date_format)
      );
      updateRegisterReportViho();
    });

    $('#register_report_date_range').on('cancel.daterangepicker', function() {
      $(this).val('');
      updateRegisterReportViho();
    });

    register_report_table = $('#register_report_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      ajax: {
        url: "{{ route('ai-template.reports.register-report') }}",
        data: function(d) {
          d.user_id = $('#register_user_id').val();
          d.status = $('#register_status').val();

          if ($('#register_report_date_range').data('daterangepicker') && $('#register_report_date_range').val()) {
            d.start_date = $('#register_report_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
            d.end_date = $('#register_report_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
          }
        }
      },
      columns: [
        { data: 'created_at', name: 'created_at' },
        { data: 'closed_at', name: 'closed_at' },
        { data: 'location_name', name: 'bl.name' },
        { data: 'user_name', name: 'user_name' },
        { data: 'total_card_payment', name: 'total_card_payment', searchable: false },
        { data: 'total_cheque_payment', name: 'total_cheque_payment', searchable: false },
        { data: 'total_cash_payment', name: 'total_cash_payment', searchable: false },
        { data: 'total_bank_transfer_payment', name: 'total_bank_transfer_payment', searchable: false },
        { data: 'total_advance_payment', name: 'total_advance_payment', searchable: false },
        { data: 'total_custom_pay_1', name: 'total_custom_pay_1', searchable: false },
        { data: 'total_custom_pay_2', name: 'total_custom_pay_2', searchable: false },
        { data: 'total_custom_pay_3', name: 'total_custom_pay_3', searchable: false },
        { data: 'total_custom_pay_4', name: 'total_custom_pay_4', searchable: false },
        { data: 'total_custom_pay_5', name: 'total_custom_pay_5', searchable: false },
        { data: 'total_custom_pay_6', name: 'total_custom_pay_6', searchable: false },
        { data: 'total_custom_pay_7', name: 'total_custom_pay_7', searchable: false },
        { data: 'total_other_payment', name: 'total_other_payment', searchable: false },
        { data: 'total', name: 'total', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ],
      footerCallback: function(row, data) {
        var total_card_payment = 0;
        var total_cheque_payment = 0;
        var total_cash_payment = 0;
        var total_bank_transfer_payment = 0;
        var total_other_payment = 0;
        var total_advance_payment = 0;
        var total_custom_pay_1 = 0;
        var total_custom_pay_2 = 0;
        var total_custom_pay_3 = 0;
        var total_custom_pay_4 = 0;
        var total_custom_pay_5 = 0;
        var total_custom_pay_6 = 0;
        var total_custom_pay_7 = 0;
        var total = 0;

        for (var r in data) {
          total_card_payment += $(data[r].total_card_payment).data('orig-value') ? parseFloat($(data[r].total_card_payment).data('orig-value')) : 0;
          total_cheque_payment += $(data[r].total_cheque_payment).data('orig-value') ? parseFloat($(data[r].total_cheque_payment).data('orig-value')) : 0;
          total_cash_payment += $(data[r].total_cash_payment).data('orig-value') ? parseFloat($(data[r].total_cash_payment).data('orig-value')) : 0;
          total_bank_transfer_payment += $(data[r].total_bank_transfer_payment).data('orig-value') ? parseFloat($(data[r].total_bank_transfer_payment).data('orig-value')) : 0;
          total_other_payment += $(data[r].total_other_payment).data('orig-value') ? parseFloat($(data[r].total_other_payment).data('orig-value')) : 0;
          total_advance_payment += $(data[r].total_advance_payment).data('orig-value') ? parseFloat($(data[r].total_advance_payment).data('orig-value')) : 0;
          total_custom_pay_1 += $(data[r].total_custom_pay_1).data('orig-value') ? parseFloat($(data[r].total_custom_pay_1).data('orig-value')) : 0;
          total_custom_pay_2 += $(data[r].total_custom_pay_2).data('orig-value') ? parseFloat($(data[r].total_custom_pay_2).data('orig-value')) : 0;
          total_custom_pay_3 += $(data[r].total_custom_pay_3).data('orig-value') ? parseFloat($(data[r].total_custom_pay_3).data('orig-value')) : 0;
          total_custom_pay_4 += $(data[r].total_custom_pay_4).data('orig-value') ? parseFloat($(data[r].total_custom_pay_4).data('orig-value')) : 0;
          total_custom_pay_5 += $(data[r].total_custom_pay_5).data('orig-value') ? parseFloat($(data[r].total_custom_pay_5).data('orig-value')) : 0;
          total_custom_pay_6 += $(data[r].total_custom_pay_6).data('orig-value') ? parseFloat($(data[r].total_custom_pay_6).data('orig-value')) : 0;
          total_custom_pay_7 += $(data[r].total_custom_pay_7).data('orig-value') ? parseFloat($(data[r].total_custom_pay_7).data('orig-value')) : 0;
          total += $(data[r].total).data('orig-value') ? parseFloat($(data[r].total).data('orig-value')) : 0;
        }

        $('.footer_total_card_payment').html(__currency_trans_from_en(total_card_payment));
        $('.footer_total_cheque_payment').html(__currency_trans_from_en(total_cheque_payment));
        $('.footer_total_cash_payment').html(__currency_trans_from_en(total_cash_payment));
        $('.footer_total_bank_transfer_payment').html(__currency_trans_from_en(total_bank_transfer_payment));
        $('.footer_total_other_payments').html(__currency_trans_from_en(total_other_payment));
        $('.footer_total_advance_payment').html(__currency_trans_from_en(total_advance_payment));
        $('.footer_total_custom_pay_1').html(__currency_trans_from_en(total_custom_pay_1));
        $('.footer_total_custom_pay_2').html(__currency_trans_from_en(total_custom_pay_2));
        $('.footer_total_custom_pay_3').html(__currency_trans_from_en(total_custom_pay_3));
        $('.footer_total_custom_pay_4').html(__currency_trans_from_en(total_custom_pay_4));
        $('.footer_total_custom_pay_5').html(__currency_trans_from_en(total_custom_pay_5));
        $('.footer_total_custom_pay_6').html(__currency_trans_from_en(total_custom_pay_6));
        $('.footer_total_custom_pay_7').html(__currency_trans_from_en(total_custom_pay_7));
        $('.footer_total').html(__currency_trans_from_en(total));
      },
      initComplete: function() {
        var table = this.api();
        setTimeout(function() {
          table.columns.adjust();
        }, 100);
      },
      drawCallback: function() {
        var table = this.api();
        setTimeout(function() {
          table.columns.adjust();
        }, 0);
      }
    });

    $('.view_register').on('shown.bs.modal', function() {
      __currency_convert_recursively($(this));
    });

    $(document).on('submit', '#register_report_filter_form', function(e) {
      e.preventDefault();
      updateRegisterReportViho();
    });

    $('#register_user_id, #register_status').on('change', function() {
      updateRegisterReportViho();
    });

    $(window).on('resize', function() {
      if (typeof register_report_table !== 'undefined') {
        register_report_table.columns.adjust();
      }
    });
  });

  function updateRegisterReportViho() {
    register_report_table.ajax.reload();
  }
</script>
@endsection
