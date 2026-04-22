<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.template = 'viho';</script>
    @php
        $asset_v = $asset_v ?? config('constants.asset_version');
        $viho_asset = asset('templates/viho/assets');
        $is_viho_template = true;
    @endphp

    <title>@yield('title', config('app.name'))</title>

    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/themify.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/feather-icon.css">

    @include('layouts.partials.css')

    <link rel="icon" href="{{ $viho_asset }}/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/animate.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/style.css">
    <link id="color" rel="stylesheet" href="{{ $viho_asset }}/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ $viho_asset }}/css/responsive.css">
    @includeIf('layouts.templates.viho.css')

    <style>
        .loader-wrapper {
            display: none !important;
        }

        /* Toastr notification styles */
        #toast-container {
            position: fixed;
            z-index: 999999;
            top: 12px;
            right: 12px;
        }
        .toast {
            background-color: #030303;
            border-radius: 4px;
            color: #FFFFFF;
            font-size: 14px;
            margin-bottom: 10px;
            padding: 15px;
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0.9;
            transition: opacity 0.3s ease;
        }
        .toast:hover {
            opacity: 1;
        }
        .toast-success {
            background-color: #24695c;
        }
        .toast-error {
            background-color: #dc3545;
        }
        .toast-warning {
            background-color: #f39c12;
        }
        .toast-info {
            background-color: #3498db;
        }

        /* Embedded default header uses dark gradient; Viho header background is light. */
        .default-header-embedded .tw-border-b.tw-bg-gradient-to-r {
            background-image: none !important;
            background: transparent !important;
            border: 0 !important;
        }

        .default-header-embedded .tw-border-b.tw-bg-gradient-to-r>div {
            padding: 0 !important;
        }

        .default-header-embedded summary,
        .default-header-embedded a.tw-inline-flex,
        .default-header-embedded button {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        .default-header-embedded summary:hover,
        .default-header-embedded a.tw-inline-flex:hover,
        .default-header-embedded button:hover {
            background: #f9fafb !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }

        .text-quantity-remaining {
            color: #2b5fec !important;
            font-weight: 700;
        }

        /* Global DataTable Control Styling */
        /* .dataTables_length select {
            padding: 6px 35px 6px 15px !important;
            border-radius: 6px !important;
            border: 1px solid #e6edef !important;
            height: 38px !important;
            display: inline-block !important;
            background-color: #fff !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237366ff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 10px center !important;
            background-size: 14px !important;
            cursor: pointer;
        }
        .dataTables_filter input {
            padding: 6px 15px !important;
            border-radius: 6px !important;
            border: 1px solid #e6edef !important;
            height: 38px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        } */

        /* Enhanced DataTable Export Buttons */
        /* .dt-buttons.btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
            justify-content: center;
            border-radius: 0;
            box-shadow: none;
        }
        .dt-buttons.btn-group .dt-button {
            background-color: #fff !important;
            border: 1px solid #e0e7ff !important;
            color: #7366ff !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04) !important;
            margin: 0 !important;
        }
        .dt-buttons.btn-group .dt-button:hover {
            background-color: #7366ff !important;
            color: #fff !important;
            border-color: #7366ff !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(115, 102, 255, 0.3) !important;
        }
        .dt-buttons.btn-group .dt-button i, 
        .dt-buttons.btn-group .dt-button svg {
            font-size: 14px !important;
            transition: color 0.3s ease;
        }
        .dt-buttons.btn-group .dt-button:hover i,
        .dt-buttons.btn-group .dt-button:hover svg {
            color: #fff !important;
        }  */

        .default-header-embedded svg,
        .default-header-embedded i {
            color: #111827 !important;
        }

        .default-header-embedded svg[stroke] {
            stroke: #111827 !important;
        }

        .default-header-embedded .tw-ring-white\/10 {
            --tw-ring-color: rgba(17, 24, 39, 0.15) !important;
        }

        .default-header-embedded .small-view-button,
        .default-header-embedded .side-bar-collapse {
            display: none !important;
        }

        .default-header-embedded {
            display: flex !important;
            align-items: center !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative;
            z-index: 5;
        }

        .nav-right.right-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .page-main-header .main-header-right {
            align-items: center;
        }

        /* Modal Vertical Alignment Fix */
        .modal-dialog {
            margin-top: 60px !important;
            z-index: 1060 !important;
        }

        .page-main-header .left-menu-header,
        .page-main-header .left-menu-header ul {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .page-main-header .left-menu-header ul li {
            list-style: none;
            display: flex;
            align-items: center;
        }

        .page-main-header .left-menu-header {
            display: flex;
            align-items: center;
        }

        .page-main-header .left-menu-header .search-form {
            margin: 0;
        }

        .sidebar-user .rounded-circle {
            display: block;
            margin: 0 auto;
        }

        /* DataTables (Viho pages): keep length control on one line. */
        div[id$="_dt_length"] .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0;
            white-space: nowrap;
        }

        div[id$="_dt_length"] .dataTables_length select {
            margin: 0;
            width: auto !important;
            margin-bottom:  4px !important;
        }

        div[id$="_dt_filter"],
        div[id$="_dt_paginate"] {
            display: flex;
            justify-content: flex-end;
        }

        /* Global fix: ensure DataTable body text is always readable (dark) */
        table.dataTable tbody tr td,
        table.dataTable tbody tr th,
        .dataTables_wrapper table tbody tr td,
        .dataTables_wrapper table tbody tr th {
            color: #2c323f !important;
        }

        table.dataTable tbody tr.odd td,
        table.dataTable tbody tr.even td {
            color: #2c323f !important;
        }

        /* Generic DataTables fixes for all report tables */
        .dataTables_wrapper table.dataTable {
            width: 100% !important;
        }

        .dataTables_wrapper table.dataTable tbody tr {
            display: table-row !important;
        }

        .dataTables_wrapper table.dataTable tbody td {
            display: table-cell !important;
        }

        .dataTables_wrapper {
            width: 100% !important;
            display: block !important;
        }

        /* Sidebar: keep icon + text on one line */
        #mainnav .nav-menu a.menu-title,
        #mainnav .nav-menu a.menu-title.link-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        #mainnav .nav-menu a.menu-title i,
        #mainnav .nav-menu a.menu-title svg {
            flex: 0 0 auto;
        }

        #mainnav .nav-menu a.menu-title span {
            display: inline-block;
            flex: 1 1 auto;
        }

        /* iCheck: hide original checkbox to avoid duplicate "shadow" checkbox */
        input.input-icheck[type="checkbox"],
        input.input-icheck[type="radio"] {
            position: absolute !important;
            opacity: 0 !important;
            width: 1px !important;
            height: 1px !important;
            margin: 0 !important;
            padding: 0 !important;
            pointer-events: none !important;
        }

        /* Ensure header doesn't overflow on small laptop screens */
        .page-main-header {
            overflow: visible !important;
        }

        .page-main-header .main-header-right {
            width: 100%;
        }

        /* Fix Font Awesome icons - support both FA4 and FA5/6 syntax */
        .fa {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Map fas/far/fab to FontAwesome for FA4 compatibility */
        .fas,
        .far,
        .fab {
            font-family: FontAwesome !important;
            font-weight: normal;
            font-style: normal;
        }

        /* Laptop Responsiveness Fixes (1024px to 1440px) */
        @media (max-width: 1440px) {
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper header.main-nav {
                width: 240px !important;
                min-width: 240px !important;
            }

            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper .page-body {
                margin-left: 240px !important;
            }

            .viho-template-active .page-main-header .main-header-right .main-header-left {
                width: 240px !important;
            }

            .viho-template-active .page-main-header .main-header-right .nav-right {
                padding-left: 10px !important;
            }

            .viho-template-active .default-header-embedded a.tw-inline-flex {
                padding: 0.375rem 0.5rem !important;
                font-size: 0.75rem !important;
            }

            .viho-template-active .default-header-embedded svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }

            .viho-template-active .page-main-header .main-header-right .left-menu-header {
                max-width: 150px !important;
            }

            .viho-template-active .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }

        @media (max-width: 1200px) {
            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper header.main-nav {
                width: 70px !important;
                min-width: 70px !important;
            }

            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper header.main-nav .main-navbar .nav-menu span {
                display: none !important;
            }

            .viho-template-active .page-wrapper.compact-wrapper .page-body-wrapper .page-body {
                margin-left: 70px !important;
            }

            .viho-template-active .page-main-header .main-header-right .main-header-left {
                width: 70px !important;
            }

            .viho-template-active .logo-wrapper img {
                display: none !important;
            }

            .viho-template-active .main-nav .sidebar-user img {
                width: 40px !important;
                height: 40px !important;
            }

            .viho-template-active .main-nav .sidebar-user h6,
            .viho-template-active .main-nav .sidebar-user p {
                display: none !important;
            }
        }

        .fa-lg {
            font-size: 1.33333em;
            line-height: 0.75em;
            vertical-align: -0.0667em;
        }

        /* POS Tab Content - Hide inactive tabs */
        div.pos-tab-content {
            background-color: #ffffff;
            padding-left: 20px;
            padding-top: 20px;
        }

        div.pos-tab div.pos-tab-content:not(.active) {
            display: none;
        }

        /* POS Tab Menu Styling */
        .pos-tab-menu .list-group-item {
            cursor: pointer;
        }

        .pos-tab-menu .list-group-item.active {
            background-color: #3c8dbc;
            border-color: #3c8dbc;
            color: #fff;
        }

        /* Viho Modal Fix - Only for Viho Template */
        .modal {
            z-index: 999999 !important;
        }
        .modal-backdrop {
            z-index: 999998 !important;
        }
        .modal-dialog {
            z-index: 999999 !important;
            position: relative;
        }
        .modal-content {
            z-index: 999999 !important;
            position: relative;
        }
        .modal-open .page-wrapper {
            overflow: visible !important;
        }
        .modal-open .page-body-wrapper {
            overflow: visible !important;
        }

        /* Bootstrap 3 Offset Shims */
        .col-md-offset-4 {
            margin-left: 33.33333333%;
        }

        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }

        .col-md-offset-8 {
            margin-left: 66.66666667%;
        }

        /* Bootstrap 3 compatibility: many migrated views still use `.input-group-addon` */
        .input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .input-group .input-group-addon {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
        }

        /* Modal Display Fix for Viho */
        .modal.fade.in {
            display: block !important;
            opacity: 1 !important;
        }
        .modal.show {
            display: block !important;
            opacity: 1 !important;
        }
        .modal[style*="display: block"] {
            display: block !important;
        }
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }

        /* =============================================
           VIHO MODAL DESIGN SYSTEM
           Based on Viho Admin Template v2.0
           ============================================= */

        /* Modal Container - Auto Width */
        .modal-dialog {
            z-index: 999999 !important;
            margin: 30px auto !important;
            position: relative !important;
            width: auto !important;
            max-width: 600px !important;
        }

        /* Large modal for bigger content */
        .modal-dialog.modal-lg {
            max-width: 800px !important;
        }

        /* Small modal for compact forms */
        .modal-dialog.modal-sm {
            max-width: 400px !important;
        }

        /* Modal Content - Auto Height */
        .modal-content {
            z-index: 999999 !important;
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(36, 105, 92, 0.15);
            position: relative !important;
            overflow: visible;
            display: flex;
            flex-direction: column;
            max-height: 90vh !important;
        }

        /* Modal Content - Card Style */
        .modal-content {
            z-index: 999999 !important;
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(36, 105, 92, 0.15);
            position: relative !important;
            overflow: hidden;
        }

        .modal.in, .modal.show {
            z-index: 999999 !important;
            display: block !important;
        }

        /* Modal Backdrop - Viho Style */
        .modal-backdrop {
            z-index: 999998 !important;
            background-color: rgba(36, 105, 92, 0.3);
            backdrop-filter: blur(4px);
        }

        /* =============================================
           MODAL HEADER
           ============================================= */
        .modal-header {
            background: linear-gradient(135deg, #24695c 0%, #2e8777 100%);
            border-bottom: none;
            padding: 20px 25px;
            position: relative;
        }

        .modal-header .modal-title {
            color: #000000 !important;
            font-weight: 600;
            font-size: 18px;
            margin: 0;
            text-shadow: none !important;
        }

        /* Close Button - Viho Style */
        .modal-header .close,
        .modal-header .btn-close {
            position: absolute;
            top: 18px;
            right: 20px;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.9) !important;
            border: none;
            border-radius: 6px;
            color: #000000 !important;
            font-size: 20px;
            line-height: 32px;
            text-align: center;
            cursor: pointer;
            opacity: 1;
            transition: all 0.3s ease;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .modal-header .close:hover,
        .modal-header .btn-close:hover {
            background: rgba(255, 255, 255, 1) !important;
            transform: rotate(90deg);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header .close span,
        .modal-header .close:focus,
        .modal-header .btn-close:focus {
            color: #000000 !important;
            outline: none !important;
        }

        .modal-header .close svg,
        .modal-header .btn-close svg {
            fill: #000000 !important;
            stroke: #000000 !important;
        }

        /* =============================================
           MODAL BODY
           ============================================= */
        .modal-body {
            padding: 25px;
            background: #fff;
            overflow-y: auto;
            overflow-x: hidden;
            flex: 1;
            min-height: 0;
        }

        /* Auto height modal - body scrolls if needed */
        .modal-dialog .modal-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
        }

        /* Custom scrollbar for modal body */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #24695c;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #1a4b41;
        }

        /* Form Elements in Modal */
        .modal-body .form-group {
            margin-bottom: 15px;
        }

        /* Only apply full width to standalone inputs not in grid */
        .modal-body .form-group > input:not([class*="col-"]),
        .modal-body .form-group > select:not([class*="col-"]),
        .modal-body .form-group > textarea:not([class*="col-"]) {
            width: 100% !important;
        }

        /* Allow Bootstrap grid to work naturally in modals */
        .modal-body .row {
            margin-left: -15px;
            margin-right: -15px;
        }

        .modal-body .row > [class*="col-"] {
            padding-left: 15px;
            padding-right: 15px;
        }

        .modal-body label {
            color: #242934;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .modal-body .form-control {
            border: 1px solid #e6edef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .modal-body .form-control:focus {
            border-color: #24695c;
            box-shadow: 0 0 0 3px rgba(36, 105, 92, 0.1);
        }

        /* Input Groups in Modal */
        .modal-body .input-group {
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            width: 100%;
        }

        .modal-body .input-group .input-group-addon,
        .modal-body .input-group .input-group-text {
            background: #f5f7fb;
            border: 1px solid #e6edef;
            color: #24695c;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            min-width: 45px;
            justify-content: center;
        }

        .modal-body .input-group .form-control,
        .modal-body .input-group select {
            flex: 1;
            min-width: 0;
        }

        /* Select2 styles are handled per-modal in create.blade.php */

        /* Fix Input Group Addon Icons - Match Input Height */
        .modal-body .input-group-addon,
        .modal-body .input-group-text {
            min-width: 45px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
        }

        .modal-body .input-group-addon i,
        .modal-body .input-group-text i {
            font-size: 16px;
            width: 16px;
            text-align: center;
        }

        /* Ensure all inputs have same height */
        .modal-body .input-group .form-control,
        .modal-body .input-group select {
            height: 44px;
        }

        /* Fix regular select dropdown in modals */
        .modal-body select.form-control {
            height: 44px;
            line-height: normal;
            padding: 10px 12px;
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            appearance: menulist;
            color: #242934;
            font-size: 14px;
        }

        .modal-body select.form-control option {
            color: #242934;
            font-size: 14px;
            padding: 8px;
        }

        .modal-body select.form-control option:first-child {
            color: #6c757d;
        }

        /* Select2 dropdown styles are handled per-modal */

        /* Fix radio/checkbox inline layout */
        .modal-body .form-group .radio-group {
            display: flex;
            align-items: center;
            min-height: 42px;
            gap: 15px;
        }

        .modal-body .radio-inline,
        .modal-body .checkbox-inline {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
            margin-bottom: 0;
        }

        .modal-body .radio-inline input,
        .modal-body .checkbox-inline input {
            margin-right: 5px;
        }

        /* =============================================
           MODAL FOOTER - Always Visible
           ============================================= */
        .modal-footer {
            background: #f8fafb;
            border-top: 1px solid #e6edef;
            padding: 18px 25px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
            position: sticky;
            bottom: 0;
            z-index: 10;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Ensure footer stays visible in fixed height modal */
        .modal-content {
            display: flex;
            flex-direction: column;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
        }

        /* Auto height modal - ensure footer visible */
        .modal-dialog .modal-content {
            display: flex;
            flex-direction: column;
            max-height: 90vh !important;
        }

        .modal-dialog .modal-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
        }

        .modal-dialog .modal-footer {
            position: sticky;
            bottom: 0;
            background: #f8fafb;
            border-top: 1px solid #e6edef;
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        /* =============================================
           BUTTON STYLING - Viho Design System
           ============================================= */
        .modal-footer .btn {
            padding: 10px 24px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            border: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 80px !important;
        }

        /* ALL Buttons - Same Primary Style (with high specificity) */
        .modal-content .modal-footer .btn-primary,
        .modal-content .modal-footer .btn-secondary,
        .modal-content .modal-footer .btn-default,
        .modal-content .modal-footer button[data-dismiss="modal"],
        .modal-dialog .modal-footer .btn,
        .modal.in .modal-footer .btn-primary,
        .modal.show .modal-footer .btn-primary {
            background: linear-gradient(135deg, #24695c 0%, #2e8777 100%) !important;
            background-color: #24695c !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(36, 105, 92, 0.3) !important;
            border: none !important;
            border-color: transparent !important;
        }

        .modal-content .modal-footer .btn-primary:hover,
        .modal-content .modal-footer .btn-secondary:hover,
        .modal-content .modal-footer .btn-default:hover,
        .modal-content .modal-footer button[data-dismiss="modal"]:hover,
        .modal-dialog .modal-footer .btn:hover,
        .modal.in .modal-footer .btn-primary:hover,
        .modal.show .modal-footer .btn-primary:hover {
            background: linear-gradient(135deg, #1a4b41 0%, #24695c 100%) !important;
            background-color: #1a4b41 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(36, 105, 92, 0.4) !important;
            color: #fff !important;
            border: none !important;
        }

        /* Success Button */
        .modal-footer .btn-success {
            background: #28a745;
            color: #fff;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .modal-footer .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        /* Danger/Button */
        .modal-footer .btn-danger {
            background: #dc3545;
            color: #fff;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .modal-footer .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        /* Info Button */
        .modal-footer .btn-info {
            background: #0ea5e9;
            color: #fff;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .modal-footer .btn-info:hover {
            background: #0284c7;
            transform: translateY(-2px);
        }

        /* Warning Button */
        .modal-footer .btn-warning {
            background: #f59e0b;
            color: #fff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .modal-footer .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        /* =============================================
           OVERRIDE TAILWIND AND OTHER CLASSES
           ============================================= */
        /* Force all modal footer buttons to same style */
        .modal-footer [class*="btn"],
        .modal-footer [class*="tw-dw-btn"],
        .modal-footer button,
        .modal-footer .btn,
        .modal-footer .btn-primary,
        .modal-footer .btn-secondary,
        .modal-footer .btn-success,
        .modal-footer .btn-danger,
        .modal-footer .btn-warning,
        .modal-footer .btn-info,
        .modal-footer .btn-default,
        .modal-footer button[data-dismiss="modal"],
        .modal-footer button[type="submit"],
        .modal-footer button[type="button"] {
            background: linear-gradient(135deg, #24695c 0%, #2e8777 100%) !important;
            background-color: #24695c !important;
            color: #fff !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(36, 105, 92, 0.3) !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            min-width: 100px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-transform: capitalize !important;
        }

        .modal-footer [class*="btn"]:hover,
        .modal-footer [class*="tw-dw-btn"]:hover,
        .modal-footer button:hover,
        .modal-footer .btn:hover,
        .modal-footer .btn-primary:hover,
        .modal-footer .btn-secondary:hover,
        .modal-footer .btn-success:hover,
        .modal-footer .btn-danger:hover,
        .modal-footer .btn-warning:hover,
        .modal-footer .btn-info:hover,
        .modal-footer .btn-default:hover,
        .modal-footer button[data-dismiss="modal"]:hover,
        .modal-footer button[type="submit"]:hover,
        .modal-footer button[type="button"]:hover {
            background: linear-gradient(135deg, #1a4b41 0%, #24695c 100%) !important;
            background-color: #1a4b41 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(36, 105, 92, 0.4) !important;
            color: #fff !important;
        }

        /* Close button in footer */
        .modal-footer .btn-secondary,
        .modal-footer button[data-dismiss="modal"] {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
            background-color: #6c757d !important;
        }

        .modal-footer .btn-secondary:hover,
        .modal-footer button[data-dismiss="modal"]:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%) !important;
            background-color: #495057 !important;
        }

        /* =============================================
           MODAL ANIMATIONS
           ============================================= */
        .modal.fade .modal-dialog {
            transform: scale(0.95) translateY(-10px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal.fade.in .modal-dialog,
        .modal.fade.show .modal-dialog {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        /* Modal Sizes */
        .modal-dialog.modal-sm {
            max-width: 400px;
        }

        .modal-dialog.modal-md {
            max-width: 600px;
        }

        .modal-dialog.modal-lg {
            max-width: 800px;
        }

        .modal-dialog.modal-xl {
            max-width: 1140px;
        }

        /* Auto size for modals with many fields */
        .modal-dialog.modal-auto-size {
            max-width: 90vw;
            margin: 30px auto;
        }

        /* Modal with many fields - scrollable content */
        .modal-dialog.modal-scrollable {
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-dialog.modal-scrollable .modal-content {
            flex: 1;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-dialog.modal-scrollable .modal-body {
            flex: 1;
            overflow-y: auto;
        }

        /* =============================================
           RESPONSIVE MODAL - Auto Height
           ============================================= */
        @media (max-width: 767px) {
            .modal-dialog {
                margin: 10px;
                max-width: calc(100% - 20px) !important;
                width: calc(100% - 20px) !important;
            }

            .modal-content {
                max-height: calc(100vh - 100px) !important;
            }

            .modal-header {
                padding: 15px 20px;
            }

            .modal-header .modal-title {
                font-size: 16px;
            }

            .modal-body {
                padding: 20px;
                max-height: calc(100vh - 200px);
            }

            .modal-footer {
                padding: 15px 20px;
                flex-direction: row !important;
                gap: 10px;
                position: relative !important;
                bottom: auto !important;
            }

            .modal-footer .btn,
            .modal-footer button {
                width: auto !important;
                flex: 1;
                min-width: 120px !important;
                padding: 12px 20px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .modal-dialog {
                width: 700px !important;
                max-width: 95vw !important;
            }

            .modal-content {
                max-height: 85vh !important;
            }

            .modal-body {
                max-height: calc(85vh - 130px);
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            .modal-dialog {
                width: 820px !important;
                max-width: 95vw !important;
            }

            .modal-content {
                max-height: 85vh !important;
            }

            .modal-body {
                max-height: calc(85vh - 130px);
            }
        }

        /* Large screens - Auto Height */
        @media (min-width: 1200px) {
            .modal-dialog {
                width: 820px !important;
                max-width: 95vw !important;
            }

            .modal-content {
                max-height: 90vh !important;
            }

            .modal-body {
                max-height: calc(90vh - 130px);
            }
        }

        .input-group .input-group-addon:first-child {
            border-right: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .input-group .input-group-addon:last-child {
            border-left: 0;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        .input-group .form-control {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group .form-control:not(:first-child):not(:last-child) {
            border-radius: 0;
        }

        .input-group .form-control:first-child:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .form-control:last-child:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Fix button alignment in input groups */
        .input-group-btn {
            position: relative;
            display: table-cell;
            vertical-align: middle;
        }

        .input-group-btn .btn {
            border-radius: 0;
            margin: 0;
            border-color: #e6edef;
        }

        .input-group-btn:first-child .btn {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
            margin-right: -1px;
        }

        .input-group-btn:last-child .btn {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
            margin-left: -1px;
        }

        /* =============================================
           FORM ELEMENTS IN MODAL - Viho Style
           ============================================= */

        /* Select Dropdowns */
        .modal-body select.form-control,
        .modal-body .form-select {
            border: 1px solid #e6edef;
            border-radius: 8px;
            padding: 12px 35px 12px 15px;
            font-size: 14px;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2324695c' d='M6 8L1 3h10z'/%3E%3C/svg%3E") no-repeat right 15px center;
            appearance: none;
            transition: all 0.3s ease;
        }

        .modal-body select.form-control:focus,
        .modal-body .form-select:focus {
            border-color: #24695c;
            box-shadow: 0 0 0 3px rgba(36, 105, 92, 0.1);
        }

        /* Textarea */
        .modal-body textarea.form-control {
            border: 1px solid #e6edef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
        }

        .modal-body textarea.form-control:focus {
            border-color: #24695c;
            box-shadow: 0 0 0 3px rgba(36, 105, 92, 0.1);
        }

        /* Checkboxes and Radio Buttons */
        .modal-body .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e6edef;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .modal-body .form-check-input:checked {
            background-color: #24695c;
            border-color: #24695c;
        }

        .modal-body .form-check-label {
            font-size: 14px;
            color: #242934;
            margin-left: 8px;
        }

        /* =============================================
           MODAL LOADING ANIMATION
           ============================================= */
        .modal-loading {
            position: relative;
            min-height: 200px;
        }

        .modal-loading::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            margin: -20px 0 0 -20px;
            border: 3px solid #e6edef;
            border-top-color: #24695c;
            border-radius: 50%;
            animation: modal-spin 1s linear infinite;
        }

        @keyframes modal-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* =============================================
           MODAL SCROLLBAR STYLING
           ============================================= */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #24695c;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #1a4b41;
        }

        /* Fix file input styling */
        .theme-form .form-group input[type=file] {
            padding: 7px 10px;
            height: 46px;
            border: 1px solid #e6edef;
            background-color: #fff;
            color: #898989;
        }

        .theme-form .form-group input[type=file]::-webkit-file-upload-button {
            background: #f8f9fa;
            border: 1px solid #e6edef;
            padding: 5px 15px;
            margin-right: 10px;
            border-radius: 4px;
            cursor: pointer;
            color: #333;
            font-weight: 500;
        }

        .theme-form .form-group input[type=file]::-webkit-file-upload-button:hover {
            background: #e9ecef;
        }

        /* Fix product image field width */
        .image-file-input-wrapper {
            width: 70% !important;
            max-width: 70% !important;
        }

        .image-file-input-wrapper .file-input,
        .product-image-fileinput {
            width: 100% !important;
            max-width: 100% !important;
        }

        .product-image-fileinput .file-caption {
            width: calc(100% - 120px) !important;
        }

        .product-image-fileinput .file-actions,
        .product-image-fileinput .file-preview {
            max-width: 100%;
        }

        /* Laptop and smaller screens */
        @media (max-width: 1366px) {
            .image-file-input-wrapper {
                width: 65% !important;
                max-width: 65% !important;
            }
        }

        @media (max-width: 1024px) {
            .image-file-input-wrapper {
                width: 60% !important;
                max-width: 60% !important;
            }
        }

        /* Better well styling */
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }

        /* DataTables Buttons Responsive Fix */
        .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 4px;
        }

        .dt-buttons .btn {
            margin: 2px;
            white-space: nowrap;
        }

        /* Ensure buttons container doesn't overflow */
        div[id$="_dt_buttons"] {
            min-height: 40px;
        }

        /* Responsive adjustments for DataTables controls */
        @media screen and (max-width: 1200px) {
            .dt-buttons .btn {
                font-size: 11px;
                padding: 4px 8px;
            }
        }

        @media screen and (max-width: 575px) {
            .dt-buttons {
                flex-direction: column;
                align-items: center;
            }

            .dt-buttons .btn {
                width: 100%;
                max-width: 200px;
                margin: 2px 0;
            }

            div[id$="_dt_buttons"] {
                margin-bottom: 10px;
            }

            /* Center search input */
            div[id$="_dt_filter"] .dataTables_filter {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            div[id$="_dt_filter"] .dataTables_filter input {
                width: 100%;
                max-width: 250px;
                margin-left: 0 !important;
            }

            div[id$="_dt_filter"] .dataTables_filter label {
                width: 100%;
                max-width: 250px;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 8px;
            }
        }

        /* Global layout for DataTables top controls (custom *_dt_top containers) */
        div[id$="_dt_top"] {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        div[id$="_dt_top"] > div {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }

        div[id$="_dt_top"] > div:not(:last-child) {
            margin-bottom: 8px;
        }

        div[id$="_dt_top"] > div[id$="_dt_buttons"] {
            order: 1;
            display: flex;
            justify-content: center;
        }

        div[id$="_dt_top"] > div[id$="_dt_length"] {
            order: 2;
            display: flex;
            justify-content: flex-start;
        }

        div[id$="_dt_top"] > div[id$="_dt_filter"] {
            order: 3;
            display: flex;
            justify-content: flex-end;
        }

        div[id$="_dt_filter"] .dataTables_filter {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        div[id$="_dt_filter"] .dataTables_filter label {
            margin-bottom: 0;
        }

        @media screen and (min-width: 576px) {
            div[id$="_dt_top"] > div[id$="_dt_length"],
            div[id$="_dt_top"] > div[id$="_dt_filter"] {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                margin-bottom: 0;
            }

            div[id$="_dt_filter"] .dataTables_filter input {
                margin-left: 8px;
                width: auto;
                min-width: 150px;
            }
        }

        @media screen and (max-width: 575px) {
            div[id$="_dt_top"] > div[id$="_dt_length"],
            div[id$="_dt_top"] > div[id$="_dt_filter"] {
                justify-content: center;
            }

            div[id$="_dt_filter"] .dataTables_filter {
                justify-content: center;
            }

            div[id$="_dt_info"],
            div[id$="_dt_paginate"] {
                justify-content: center;
                text-align: center !important;
            }
        }

        @media screen and (min-width: 1200px) {
            div[id$="_dt_filter"] .dataTables_filter input {
                min-width: 200px;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="viho-template-active">
    <!-- Currency related fields (override symbol for Viho pages) -->
    <input type="hidden" id="__code" value="{{ session('currency')['code'] ?? '' }}">
    <input type="hidden" id="__symbol" value="৳">
    <input type="hidden" id="__thousand" value="{{ session('currency')['thousand_separator'] ?? ',' }}">
    <input type="hidden" id="__decimal" value="{{ session('currency')['decimal_separator'] ?? '.' }}">
    <input type="hidden" id="__symbol_placement"
        value="{{ session('business.currency_symbol_placement') ?? 'before' }}">
    <input type="hidden" id="__precision" value="{{ session('business.currency_precision', 2) }}">
    <input type="hidden" id="__quantity_precision" value="{{ session('business.quantity_precision', 2) }}">
    <!-- End currency related fields -->

    @can('view_export_buttons')
        <input type="hidden" id="view_export_buttons">
    @endcan
    @php
        $whitelist = ['127.0.0.1', '::1'];
    @endphp
    @if (in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist))
        <input type="hidden" id="__is_localhost" value="true">
    @endif
    @if (isMobile())
        <input type="hidden" id="__is_mobile">
    @endif
    @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}"
            data-msg="{{ session('status.msg') }}">
    @endif

    <!-- Loader removed as it was causing visibility issues -->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-main-header">
            <div class="main-header-right row m-0">
                <div class="main-header-left">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}"><img class="img-fluid"
                                src="{{ $viho_asset }}/images/logo/logo.png" alt=""></a>
                    </div>
                    <div class="dark-logo-wrapper">
                        <a href="{{ route('home') }}"><img class="img-fluid"
                                src="{{ $viho_asset }}/images/logo/dark-logo.png" alt=""></a>
                    </div>
                    <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                            id="sidebar-toggle"></i></div>
                </div>
                <div class="left-menu-header col-auto">
                    <ul>
                        <li>
                            <form class="form-inline search-form" style="max-width: 200px;">
                                <div class="search-bg"><i class="fa fa-search"></i>
                                    <input class="form-control-plaintext" placeholder="Search here.....">
                                </div>
                            </form><span class="d-sm-none mobile-search search-bg"><i class="fa fa-search"></i></span>
                        </li>
                    </ul>
                </div>
                <div class="nav-right col pull-right right-menu p-0 box-col-8">
                    <div class="default-header-embedded">
                        @include('layouts.partials.header')
                    </div>
                </div>
                <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
            </div>
        </div>
        <!-- Page Header Ends -->

        <div class="page-body-wrapper sidebar-icon">
            <header class="main-nav">
                <div class="sidebar-user text-center">
                    <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>
                    <img class="img-90 rounded-circle" src="{{ $viho_asset }}/images/dashboard/1.png" alt="">
                    {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
                    <a href="javascript:void(0)">
                        <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->first_name ?? 'User' }}</h6>
                    </a>
                    <p class="mb-0 font-roboto">{{ Session::get('business.name') }}</p>
                    {{-- <ul>
                        <li><span><span class="counter">0</span></span>
                            <p></p>
                        </li>
                        <li><span></span>
                            <p></p>
                        </li>
                        <li><span><span class="counter">0</span></span>
                            <p></p>
                        </li>
                    </ul> --}}
                </div>
                <nav>
                    <div class="main-navbar">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="mainnav">
                            {!! Menu::render('admin-sidebar-menu', 'vihocustom') !!}
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </div>
                </nav>
            </header>

            <div class="page-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section"></section>

    @include('home.todays_profit_modal')

    <audio id="success-audio">
        <source src="{{ asset('/audio/success.ogg?v=' . $asset_v) }}" type="audio/ogg">
        <source src="{{ asset('/audio/success.mp3?v=' . $asset_v) }}" type="audio/mpeg">
    </audio>
    <audio id="error-audio">
        <source src="{{ asset('/audio/error.ogg?v=' . $asset_v) }}" type="audio/ogg">
        <source src="{{ asset('/audio/error.mp3?v=' . $asset_v) }}" type="audio/mpeg">
    </audio>
    <audio id="warning-audio">
        <source src="{{ asset('/audio/warning.ogg?v=' . $asset_v) }}" type="audio/ogg">
        <source src="{{ asset('/audio/warning.mp3?v=' . $asset_v) }}" type="audio/mpeg">
    </audio>

    @if (!empty($__additional_html))
        {!! $__additional_html !!}
    @endif

    @php
        $__is_pusher_enabled = $__is_pusher_enabled ?? false;
        $__system_settings = $__system_settings ?? [];
    @endphp
    @include('layouts.partials.javascripts_viho')
    {{-- Module JS/CSS --}}
    @include('layouts.module-assets')
    <div class="modal fade view_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

    @if (!empty($__additional_views) && is_array($__additional_views))
        @foreach ($__additional_views as $additional_view)
            @includeIf($additional_view)
        @endforeach
    @endif
    <div>
        <div class="overlay tw-hidden"></div>
    </div>

    <!-- feather icon js-->
    <script src="{{ $viho_asset }}/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{ $viho_asset }}/js/icons/feather-icon/feather-icon.js"></script>
    <script src="{{ $viho_asset }}/js/config.js"></script>
    <script src="{{ $viho_asset }}/js/script.js"></script>
    <script src="{{ asset('js/viho-app.js') }}"></script>

    <!-- Toastr notification container -->
    <div id="toast-container" class="toast-top-right"></div>

    <script>
        (function () {
            if (typeof window.jQuery === 'undefined') return;
            var $ = window.jQuery;
            $(function () {
                // Use Viho-like DataTables layout (length left, buttons center, search right; info left, paginate right).
                if ($.fn && $.fn.dataTable && $.fn.dataTable.defaults) {
                    $.extend($.fn.dataTable.defaults, {
                        dom: "<'row mb-3'<'col-12'B>>" +
                            "<'row align-items-center mb-2'<'col-sm-6'l><'col-sm-6 text-sm-end'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>"
                    });
                }

                if ($.fn && typeof $.fn.popover === 'function') {
                    $('[data-toggle="popover"]').popover();
                }
                if ($.fn && typeof $.fn.dropdown === 'function') {
                    $('.dropdown-toggle[data-toggle="dropdown"]').dropdown();
                }

                $(document).on('click', '#mainnav .nav-menu > li.dropdown > a.menu-title', function (e) {
                    e.preventDefault();
                    var $parent = $(this).closest('li.dropdown');
                    var $submenu = $parent.children('ul.nav-submenu');

                    $parent.siblings('li.dropdown').removeClass('open').children(
                        'ul.nav-submenu:visible').slideUp(
                            150);
                    $parent.toggleClass('open');
                    $submenu.stop(true, true).slideToggle(150);
                });
            });
        })();
    </script>

    <!-- Auto Height Modal Script 820px Width -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto height modal: 820px width, auto height
            const modals = document.querySelectorAll('.modal');
            
            modals.forEach(function(modal) {
                // When modal is shown
                modal.addEventListener('show.bs.modal', function() {
                    const modalDialog = this.querySelector('.modal-dialog');
                    const modalContent = this.querySelector('.modal-content');
                    const modalBody = this.querySelector('.modal-body');
                    
                    if (modalDialog && modalContent) {
                        // Set width
                        modalDialog.style.width = '820px';
                        modalDialog.style.maxWidth = '95vw';
                        
                        // Remove fixed height, let content determine size
                        modalContent.style.maxHeight = '90vh';
                        
                        // Ensure body scrolls if needed
                        if (modalBody) {
                            modalBody.style.maxHeight = 'calc(90vh - 130px)'; // 130px for header + footer
                        }
                    }
                });
                
                // Handle dynamic content after modal is fully shown
                modal.addEventListener('shown.bs.modal', function() {
                    const modalDialog = this.querySelector('.modal-dialog');
                    const modalContent = this.querySelector('.modal-content');
                    const modalBody = this.querySelector('.modal-body');
                    
                    if (modalDialog && modalContent && modalBody) {
                        // Recalculate after content is fully rendered
                        setTimeout(function() {
                            const windowHeight = window.innerHeight;
                            const headerHeight = this.querySelector('.modal-header')?.scrollHeight || 70;
                            const footerHeight = this.querySelector('.modal-footer')?.scrollHeight || 60;
                            
                            // Adjust max-height based on screen size
                            let contentMaxHeight = '90vh';
                            let bodyMaxHeight = 'calc(90vh - 130px)';
                            
                            if (windowHeight < 600) {
                                contentMaxHeight = 'calc(100vh - 100px)';
                                bodyMaxHeight = 'calc(100vh - 200px)';
                            } else if (windowHeight < 768) {
                                contentMaxHeight = 'calc(100vh - 120px)';
                                bodyMaxHeight = 'calc(100vh - 200px)';
                            } else if (windowHeight < 992) {
                                contentMaxHeight = '85vh';
                                bodyMaxHeight = 'calc(85vh - 130px)';
                            }
                            
                            modalContent.style.maxHeight = contentMaxHeight;
                            modalBody.style.maxHeight = bodyMaxHeight;
                        }, 100);
                    }
                }.bind(modal));
            });
        });
    </script>

    @yield('javascript')
    @stack('scripts')
</body>

</html>
