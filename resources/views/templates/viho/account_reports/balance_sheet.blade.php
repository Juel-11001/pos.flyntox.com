@extends('templates.viho.layout')
@section('title', __( 'account.balance_sheet' ))

@section('css')
<style>
@page {
  size: auto;
  margin: 10mm;
}

@media print {
  html,
  body {
    overflow: visible !important;
    height: auto !important;
    min-height: 0 !important;
  }

  #scrollable-container,
  .page-body,
  .page-body-wrapper {
    overflow: visible !important;
    height: auto !important;
  }

  .page-main-header,
  .main-nav,
  .no-print,
  .box-footer,
  .scrolltop,
  #toast-container,
  .loader-wrapper,
  .default-header-embedded {
    display: none !important;
  }

  .page-wrapper,
  .page-body-wrapper,
  .page-body,
  .container-fluid,
  .content {
    margin: 0 !important;
    padding: 0 !important;
    background: #fff !important;
    height: auto !important;
    min-height: 0 !important;
  }

  .box,
  .box-header,
  .box-body {
    border: 0 !important;
    box-shadow: none !important;
  }

  .box {
    margin-bottom: 0 !important;
    page-break-inside: auto !important;
    break-inside: auto !important;
  }

  .content > br {
    display: none !important;
  }

  .table {
    width: 100% !important;
  }

  table {
    page-break-inside: auto !important;
    break-inside: auto !important;
  }

  thead {
    display: table-header-group !important;
  }

  tfoot {
    display: table-footer-group !important;
  }

  tr,
  td,
  th {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
  }
}
</style>
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'account.balance_sheet')
  </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row no-print">
    <div class="col-sm-12">
      @component('components.filters', ['title' => __('report.filters')])
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('bal_sheet_location_id', __('purchase.business_location') . ':') !!}
            {!! Form::select('bal_sheet_location_id', $business_locations, null, ['class' => 'form-control select2',
            'style' => 'width:100%']) !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <label for="end_date">@lang('messages.filter_by_date'):</label>
          <div class="input-group flex-nowrap">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input type="text" id="end_date" value="{{@format_date('now')}}" class="form-control" readonly>
          </div>
        </div>
      </div>
      @endcomponent
    </div>
  </div>
  <br>
  <div class="box box-solid">
    <div class="box-header print_section">
      <h3 class="box-title">{{session()->get('business.name')}} - @lang( 'account.balance_sheet') - <span
          id="hidden_date">{{@format_date('now')}}</span></h3>
    </div>
    <div class="box-body">
        <table class="table table-border-center no-border table-pl-12">
          <thead>
            <tr class="bg-gray">
              <th>@lang( 'account.liability')</th>
              <th>@lang( 'account.assets')</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <table class="table">
                  <tr>
                    <th>@lang('account.supplier_due'):</th>
                    <td>
                      <input type="hidden" id="hidden_supplier_due" class="liability">
                      <span class="remote-data" id="supplier_due">
                        <i class="fas fa-sync fa-spin fa-fw"></i>
                      </span>
                    </td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="table" id="assets_table">
                  <tbody>
                    <tr>
                      <th>@lang('account.customer_due'):</th>
                      <td>
                        <input type="hidden" id="hidden_customer_due" class="asset">
                        <span class="remote-data" id="customer_due">
                          <i class="fas fa-sync fa-spin fa-fw"></i>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th>@lang('report.closing_stock'):</th>
                      <td>
                        <input type="hidden" id="hidden_closing_stock" class="asset">
                        <span class="remote-data" id="closing_stock">
                          <i class="fas fa-sync fa-spin fa-fw"></i>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th colspan="2">@lang('account.account_balances'):</th>
                    </tr>
                  </tbody>
                  <tbody id="account_balances" class="pl-20-td">
                    <tr>
                      <td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td>
                    </tr>
                  </tbody>
                  {{--
                                <tbody>
                                    <tr>
                                        <th colspan="2">@lang('account.capital_accounts'):</th>
                                    </tr>
                                </tbody>
                                <tbody id="capital_account_balances" class="pl-20-td">
                                    <tr><td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>
                                </tbody>
                                --}}
                </table>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="bg-gray">
              <td>
                <table class="table bg-gray mb-0 no-border">
                  <tr>
                    <th>
                      @lang('account.total_liability'):
                    </th>
                    <td>
                      <span id="total_liabilty"><i class="fas fa-sync fa-spin fa-fw"></i></span>
                    </td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="table bg-gray mb-0 no-border">
                  <tr>
                    <th>
                      @lang('account.total_assets'):
                    </th>
                    <td>
                      <span id="total_assets"><i class="fas fa-sync fa-spin fa-fw"></i></span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </tfoot>
        </table>
    </div>
    <div class="box-footer">
      <button type="button" class="btn btn-primary text-white no-print pull-right" id="print_balance_sheet_report">
        <i class="fa fa-print"></i> @lang('messages.print')</button>
    </div>
  </div>

</section>
<!-- /.content -->
@stop
@section('javascript')

<script type="text/javascript">
$(document).ready(function() {
  //Date picker
  $('#end_date').datepicker({
    autoclose: true,
    format: datepicker_date_format
  });
  update_balance_sheet();

  $('#end_date').change(function() {
    update_balance_sheet();
    $('#hidden_date').text($(this).val());
  });
  $('#bal_sheet_location_id').change(function() {
    update_balance_sheet();
  });

  $(document).on('click', '#print_balance_sheet_report', function() {
    printBalanceSheetWithIframe();
  });
});

function getBalanceSheetPrintableHtml() {
  var title_html = $('.box-header.print_section').html() || '';
  var table_html = $('.box-body').html() || '';

  return '<div class="balance-sheet-print">' +
    '<div class="print-header">' + title_html + '</div>' +
    '<div class="print-body">' + table_html + '</div>' +
    '</div>';
}

function printBalanceSheetWithIframe() {
  var href = '{{ route('ai-template.account.balance-sheet.print') }}';
  var params = [];
  var end_date = $('input#end_date').val();
  var location_id = $('#bal_sheet_location_id').val();
  var iframe_id = 'balance_sheet_print_iframe';
  var iframe = document.getElementById(iframe_id);
  var print_url;

  if (end_date) {
    params.push('end_date=' + encodeURIComponent(end_date));
  }

  if (location_id) {
    params.push('location_id=' + encodeURIComponent(location_id));
  }

  params.push('print_on_load=1');
  print_url = href + '?' + params.join('&');

  if (iframe) {
    iframe.parentNode.removeChild(iframe);
  }

  iframe = document.createElement('iframe');
  iframe.id = iframe_id;
  iframe.style.position = 'fixed';
  iframe.style.right = '0';
  iframe.style.bottom = '0';
  iframe.style.width = '0';
  iframe.style.height = '0';
  iframe.style.border = '0';
  iframe.style.visibility = 'hidden';
  iframe.onload = function() {
    setTimeout(function() {
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
    }, 300);
  };

  iframe.src = print_url;
  document.body.appendChild(iframe);
}

function update_balance_sheet() {
  var loader = '<i class="fas fa-sync fa-spin fa-fw"></i>';
  $('span.remote-data').each(function() {
    $(this).html(loader);
  });

  $('table#assets_table tbody#account_balances').html(
    '<tr><td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>');
  $('table#assets_table tbody#capital_account_balances').html(
    '<tr><td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>');

  var end_date = $('input#end_date').val();
  var location_id = $('#bal_sheet_location_id').val()
  $.ajax({
    url: "{{action([\App\Http\Controllers\AccountReportsController::class, 'balanceSheet'])}}?end_date=" +
      end_date + '&location_id=' + location_id,
    dataType: "json",
    success: function(result) {
      $('span#supplier_due').text(__currency_trans_from_en(result.supplier_due, true));
      __write_number($('input#hidden_supplier_due'), result.supplier_due);

      $('span#customer_due').text(__currency_trans_from_en(result.customer_due, true));
      __write_number($('input#hidden_customer_due'), result.customer_due);

      $('span#closing_stock').text(__currency_trans_from_en(result.closing_stock, true));
      __write_number($('input#hidden_closing_stock'), result.closing_stock);
      var account_balances = result.account_balances;
      $('table#assets_table tbody#account_balances').html('');
      for (var key in account_balances) {
        var accnt_bal = __currency_trans_from_en(result.account_balances[key]);
        var accnt_bal_with_sym = __currency_trans_from_en(result.account_balances[key], true);
        var account_tr = '<tr><td class="pl-20-td">' + key +
          ':</td><td><input type="hidden" class="asset" value="' + accnt_bal + '">' + accnt_bal_with_sym +
          '</td></tr>';
        $('table#assets_table tbody#account_balances').append(account_tr);
      }
      var capital_account_details = result.capital_account_details;
      $('table#assets_table tbody#capital_account_balances').html('');
      for (var key in capital_account_details) {
        var accnt_bal = __currency_trans_from_en(result.capital_account_details[key]);
        var accnt_bal_with_sym = __currency_trans_from_en(result.capital_account_details[key], true);
        var account_tr = '<tr><td class="pl-20-td">' + key +
          ':</td><td><input type="hidden" class="asset" value="' + accnt_bal + '">' + accnt_bal_with_sym +
          '</td></tr>';
        $('table#assets_table tbody#capital_account_balances').append(account_tr);
      }


      var total_liabilty = 0;
      var total_assets = 0;
      $('input.liability').each(function() {
        total_liabilty += __read_number($(this));
      });
      $('input.asset').each(function() {
        total_assets += __read_number($(this));
      });

      $('span#total_liabilty').text(__currency_trans_from_en(total_liabilty, true));
      $('span#total_assets').text(__currency_trans_from_en(total_assets, true));

    }
  });
}
</script>

@endsection
