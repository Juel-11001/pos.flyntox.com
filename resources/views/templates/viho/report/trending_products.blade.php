@extends('templates.viho.layout')
@section('title', __('report.trending_products'))

@push('styles')
<style>
/* Chart container fixes */
.chart-container {
  position: relative;
  height: 400px;
  width: 100%;
}

.print_section,
.print-meta {
  display: none;
}

canvas {
  max-width: 100%;
}

@media print {
  html,
  body,
  .page-wrapper,
  .page-body-wrapper,
  .page-body,
  .container-fluid,
  .content,
  #scrollable-container {
    overflow: visible !important;
    height: auto !important;
    min-height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #fff !important;
  }

  .page-main-header,
  .main-nav,
  .page-header,
  .no-print,
  .box-footer,
  .scrolltop,
  #toast-container,
  .loader-wrapper,
  .default-header-embedded {
    display: none !important;
  }

  .card,
  .card-body {
    border: 0 !important;
    box-shadow: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .print_section,
  .print-meta {
    display: block !important;
  }

  .print_section {
    text-align: center !important;
    margin-bottom: 18px !important;
  }

  .row,
  [class*="col-"] {
    width: 100% !important;
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    float: none !important;
  }

  .chart-container {
    display: block !important;
    width: 100% !important;
    height: auto !important;
    min-height: 300px !important;
    margin: 0 !important;
    padding: 0 !important;
    page-break-inside: avoid !important;
  }

  .chart-container > div,
  .chart-container canvas {
    display: block !important;
    width: 100% !important;
    max-width: none !important;
    height: auto !important;
    margin: 0 !important;
  }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('report.trending_products')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
      <div class="card">
      <div class="card-body">
        <div class="print_section">
          <h3>{{ session()->get('business.name') }} - @lang('report.trending_products')</h3>
        </div>
        <div class="row no-print">
          <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getTrendingProducts']),
            'method' => 'get' ]) !!}
            <div class="row">
              <div class='col-sm-12 col-md-6 col-xl-4 col-xxl-3'>
                <div class="form-group">
                  {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                  {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style'
                  => 'width:100%']) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('category_id', __('product.category') . ':') !!}
                  {!! Form::select('category', $categories, null, ['placeholder' => __('messages.all'), 'class' =>
                  'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                  {!! Form::select('sub_category', array(), null, ['placeholder' => __('messages.all'), 'class' =>
                  'form-control select2', 'style' => 'width:100%', 'id' => 'sub_category_id']) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('brand', __('product.brand') . ':') !!}
                  {!! Form::select('brand', $brands, null, ['placeholder' => __('messages.all'), 'class' =>
                  'form-control select2', 'style' => 'width:100%']) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('unit', __('product.unit') . ':') !!}
                  {!! Form::select('unit', $units, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('trending_product_date_range',__('report.date_range') . ':') !!}
                  {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
                  'form-control', 'id' => 'trending_product_date_range', 'readonly']) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('limit', __('lang_v1.no_of_products') . ':') !!}
                  @show_tooltip(__('tooltip.no_of_products_for_trending_products'))
                  {!! Form::number('limit', 5, ['placeholder' => __('lang_v1.no_of_products'), 'class' =>
                  'form-control',
                  'min' => 1]) !!}
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
                <div class="form-group">
                  {!! Form::label('product_type', __('product.product_type') . ':') !!}
                  {!! Form::select('product_type', ['single' => __('lang_v1.single'), 'variable' =>
                  __('lang_v1.variable'), 'combo' => __('lang_v1.combo')], request()->input('product_type'),
                  ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%'])
                  !!}
                </div>
              </div>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right">@lang('report.apply_filters')</button>
              </div>
            </div>
            {!! Form::close() !!}
            @endcomponent
          </div>
        </div>
        <hr class="no-print">
        <div class="row">
          <div class="col-xs-12">
            <div class="chart-container">
              {!! $chart->container() !!}
            </div>
          </div>
        </div>
        <div class="row no-print">
          <div class="col-sm-12">
            <button type="button" class="btn btn-primary pull-right" aria-label="Print" id="print_trending_products_report">
              <i class="fa fa-print"></i> @lang('messages.print')
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
{!! $chart->script() !!}
<script type="text/javascript">
  $(document).ready(function() {
    function getPrintableChartMarkup() {
      var container = document.querySelector('.chart-container');

      if (!container) {
        return '';
      }

      var svg = container.querySelector('svg');
      if (svg) {
        return '<div class="print-chart-svg">' + svg.outerHTML + '</div>';
      }

      var canvas = container.querySelector('canvas');
      if (canvas) {
        return '<img src="' + canvas.toDataURL('image/png') + '" alt="Trending products chart">';
      }

      var image = container.querySelector('img');
      if (image) {
        return image.outerHTML;
      }

      var chartHtml = container.innerHTML;
      return chartHtml ? '<div class="print-chart-html">' + chartHtml + '</div>' : '';
    }
    $(document).on('click', '#print_trending_products_report', function() {
      var iframe_id = 'trending_products_print_iframe';
      var iframe = document.getElementById(iframe_id);
      var chart_markup = getPrintableChartMarkup();
      var title_html = document.querySelector('.print_section') ? document.querySelector('.print_section').innerHTML : '';

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
      document.body.appendChild(iframe);

      var printable_html =
        '<div class="print-wrap">' +
          '<div class="print-title">' + title_html + '</div>' +
          '<div class="print-chart">' +
            chart_markup +
          '</div>' +
        '</div>';

      var iframe_doc = iframe.contentWindow.document;
      iframe_doc.open();
      iframe_doc.write(
        '<!DOCTYPE html><html><head><title>{{ __("report.trending_products") }}</title>' +
        '<style>' +
        'html,body{margin:0;padding:0;background:#fff;font-family:Arial,sans-serif;color:#111;}' +
        '.print-wrap{padding:24px 28px;}' +
        '.print-title{text-align:center;margin-bottom:10px;}' +
        '.print-title h3{margin:0;font-size:20px;font-weight:700;}' +
        '.print-chart{width:100%;text-align:left;}' +
        '.print-chart img{display:block;width:100%;max-width:100%;height:auto;}' +
        '.print-chart svg{display:block;width:100% !important;max-width:100%;height:auto !important;overflow:visible;}' +
        '.print-chart .highcharts-container{width:100% !important;max-width:100% !important;}' +
        '</style></head><body>' + printable_html + '</body></html>'
      );
      iframe_doc.close();

      setTimeout(function() {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
      }, 300);
    });

  });
</script>
@endsection
