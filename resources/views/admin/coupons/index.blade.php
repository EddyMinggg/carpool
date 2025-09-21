@extends('admin.layout')

@section('title', 'Coupon Management - List')

@section('content')
    <style>
        /* 統一的 DataTables 按鈕樣式 */
        .dt-buttons {
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .dt-button {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 10px 18px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            position: relative !important;
            overflow: hidden !important;
            min-width: 100px !important;
            justify-content: center !important;
            cursor: pointer !important;
        }
        
        .dt-button:before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent) !important;
            transition: left 0.5s !important;
        }
        
        .dt-button:hover:before {
            left: 100% !important;
        }
        
        .dt-button:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }
        
        .dt-button:nth-child(1) i { color: #8b5cf6 !important; }
        .dt-button:nth-child(2) i { color: #10b981 !important; }
        .dt-button:nth-child(3) i { color: #059669 !important; }
        .dt-button:nth-child(4) i { color: #ef4444 !important; }
        .dt-button:nth-child(5) i { color: #6366f1 !important; }
        .dt-button:hover i { color: white !important; }

        /* DataTable 間距調整 */
        .dataTables_length {
            margin-top: 1.5rem !important;
            margin-bottom: 1rem !important;
        }
        
        .dataTables_info {
            padding-top: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .dataTables_paginate {
            padding-top: 1rem !important;
        }

        /* Action 按鈕樣式 */
        .action-btn {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
            border-radius: 0.25rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .action-btn-blue {
            background-color: #3b82f6;
            color: white;
        }
        
        .action-btn-blue:hover {
            background-color: #2563eb;
            color: white;
        }
        
        .action-btn-yellow {
            background-color: #eab308;
            color: white;
        }
        
        .action-btn-yellow:hover {
            background-color: #ca8a04;
            color: white;
        }
        
        .action-btn-red {
            background-color: #ef4444;
            color: white;
        }
        
        .action-btn-red:hover {
            background-color: #dc2626;
            color: white;
        }
    </style>

    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Coupon Management</h2>
            <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Create New Coupon
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <table id="couponsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Enabled</th>
                        <th>Usage Limit</th>
                        <th>Used Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>¥{{ number_format($coupon->discount_amount, 2) }}</td>
                            <td>{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '-' }}</td>
                            <td>{{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '-' }}</td>
                            <td>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $coupon->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $coupon->enabled ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $coupon->usage_limit ?? '-' }}</td>
                            <td>{{ $coupon->used_count }}</td>
                            <td>
                                <div class="flex space-x-1">
                                    <a href="{{ route('admin.coupons.show', $coupon->id) }}" 
                                       class="action-btn action-btn-blue"
                                       title="View Coupon">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                       class="action-btn action-btn-yellow"
                                       title="Edit Coupon">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteCoupon({{ $coupon->id }})" 
                                            class="action-btn action-btn-red"
                                            title="Delete Coupon">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#couponsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: [8], // Actions column
                orderable: false,
                searchable: false
            }
        ],
        dom: 'Bfrtlip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'dt-button',
                titleAttr: 'Copy table data to clipboard'
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'dt-button',
                titleAttr: 'Export to CSV format'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'dt-button',
                titleAttr: 'Export to Excel format'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'dt-button',
                titleAttr: 'Export to PDF format'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'dt-button',
                titleAttr: 'Print table'
            }
        ],
        language: {
            search: "Search coupons:",
            lengthMenu: "Show _MENU_ coupons per page",
            info: "Showing _START_ to _END_ of _TOTAL_ coupons",
            infoEmpty: "No coupons found",
            infoFiltered: "(filtered from _MAX_ total coupons)",
            emptyTable: "No coupon data available",
            zeroRecords: "No coupons match your search criteria",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

function deleteCoupon(couponId) {
    if (confirm('Are you sure you want to delete this coupon?')) {
        document.getElementById('delete-form-' + couponId).submit();
    }
}
</script>
@endpush
