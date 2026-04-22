<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <style type="text/css">
      @media print {
        html,
        body {
          overflow: visible !important;
          height: auto !important;
          max-height: none !important;
        }

        .no-print,
        .modal-footer,
        button.close {
          display: none !important;
        }

        .modal,
        .modal-dialog,
        .modal-content,
        .modal-body,
        .modal-dialog .modal-content,
        .modal-dialog .modal-body {
          position: static !important;
          overflow: visible !important;
          height: auto !important;
          max-height: none !important;
          min-height: 0 !important;
          width: 100% !important;
        }

        .modal-content {
          border: 0 !important;
          box-shadow: none !important;
          page-break-inside: auto !important;
          break-inside: auto !important;
        }

        .row,
        table,
        tbody,
        tr,
        td,
        th {
          page-break-inside: avoid !important;
          break-inside: avoid !important;
        }

        /* Remove any visual scrollbars in print output */
        * {
          scrollbar-width: none !important;
        }
        *::-webkit-scrollbar {
          width: 0 !important;
          height: 0 !important;
        }
      }
    </style>
    <div class="modal-header mini_print">
      <h3 class="modal-title">@lang( 'cash_register.register_details' ) ( {{ \Carbon::createFromFormat('Y-m-d H:i:s', $register_details->open_time)->format('jS M, Y h:i A') }} -  {{\Carbon::createFromFormat('Y-m-d H:i:s', $close_time)->format('jS M, Y h:i A')}} )</h3>
    </div>

    <div class="modal-body">
      @include('cash_register.payment_details')
      <hr>
      @if(!empty($register_details->denominations))
        @php
          $total = 0;
        @endphp
        <div class="row">
          <div class="col-md-8 col-sm-12">
            <h3>@lang( 'lang_v1.cash_denominations' )</h3>
            <table class="table table-slim">
              <thead>
                <tr>
                  <th width="20%" class="text-right">@lang('lang_v1.denomination')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-center">@lang('lang_v1.count')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-left">@lang('sale.subtotal')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($register_details->denominations as $key => $value)
                <tr>
                  <td class="text-right">{{$key}}</td>
                  <td class="text-center">X</td>
                  <td class="text-center">{{$value ?? 0}}</td>
                  <td class="text-center">=</td>
                  <td class="text-left">
                    @format_currency($key * $value)
                  </td>
                </tr>
                @php $total += ((float) $key) * ((float) $value); @endphp
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="text-center">@lang('sale.total')</th>
                  <td>@format_currency($total)</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      @endif
      
      <div class="row">
        <div class="col-xs-6">
          <b>@lang('report.user'):</b> {{ $register_details->user_name}}<br>
          <b>@lang('business.email'):</b> {{ $register_details->email}}<br>
          <b>@lang('business.business_location'):</b> {{ $register_details->location_name}}<br>
        </div>
        @if(!empty($register_details->closing_note))
          <div class="col-xs-6">
            <strong>@lang('cash_register.closing_note'):</strong><br>
            {{$register_details->closing_note}}
          </div>
        @endif
      </div>
    </div>

    <div class="modal-footer">
  <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white no-print print-mini-button" 
          aria-label="Print">
      <i class="fa fa-print"></i> @lang('messages.print_mini')
  </button>
      <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white no-print" 
        aria-label="Print" 
          onclick="$(this).closest('div.modal-content').printThis({importCSS: true, importStyle: true, printContainer: true, pageTitle: ''});">
        <i class="fa fa-print"></i> @lang( 'messages.print_detailed' )
      </button>

      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white no-print" 
        data-dismiss="modal">@lang( 'messages.cancel' )
      </button>
    </div>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
  $(document).ready(function () {
      $(document).on('click', '.print-mini-button', function () {
          $('.mini_print').printThis();
      });
  });
</script>