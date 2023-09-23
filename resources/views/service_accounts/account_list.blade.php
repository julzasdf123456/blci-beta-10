@extends('layouts.app')

@push('page_css')
   <style>
      table {
         width: 100%;
      }

      table td,
      table th {
         background-color: rgba(0, 0, 0, 0);
         font-family: monospace;
         font-size: .8em;
      }
   </style>
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account List Exporter</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
   {{-- PARAMS --}}
   <div class="col-lg-12">
       <div class="card shadow-none">
           <div class="card-body">
               <div class="row">
                   <div class="form-group col-md-2">
                       <label for="">Town</label>
                       <select id="Town" name="Town" class="form-control form-control-sm">
                           <option value="All">All</option>
                           @foreach ($towns as $item)
                               <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                           @endforeach
                       </select>
                   </div>
                   <div class="form-group col-md-2">
                     <label for="">Status</label>
                     <select id="Status" name="Status" class="form-control form-control-sm">
                         <option value="All">All</option>
                         @foreach ($status as $item)
                             <option value="{{ $item->AccountStatus }}" {{ isset($_GET['Status']) && $_GET['Status']==$item->AccountStatus ? 'selected' : ''  }}>{{ $item->AccountStatus }}</option>
                         @endforeach
                     </select>
                 </div>
                   <div class="form-group col-md-4">
                       <label for="">Action</label><br>
                       <button id="view-accounts" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                       <button id="download-btn" class="btn btn-sm btn-success"><i class="fas fa-download ico-tab-mini"></i>Download</button>
                       <div id="loader" class="spinner-border text-info gone" role="status">
                           <span class="sr-only">Loading...</span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   {{-- RESULTS --}}
   <div class="col-lg-12">
       <div class="card shadow-none">
            <div class="card-body p-0">
               <table id="res-table">
                  <thead>
                     <th>#</th>
                     <th>Town</th>
                     <th>Route</th>
                     <th>Account No</th>
                     <th>Account Name</th>
                     <th>Barangay</th>
                     <th>Sitio/Purok</th>
                     <th>Account Type</th>
                     <th>Account Status</th>
                  </thead>
                  <tbody>
   
                  </tbody>
               </table>
            </div>
       </div>
   </div>  
</div>
@endsection

@push('page_scripts')
    <script>
      $(document).ready(function() {
         $('#view-accounts').on('click', function() {
            viewAccounts()
         })

         $('#download-btn').on('click', function() {
            window.location.href = "{{ url('/service_accounts/download-account-list') }}/" + $('#Town').val() + "/" + $('#Status').val()
         })
      })

      function viewAccounts() {
         $('#res-table tbody tr').remove()
         $('#loader').removeClass('gone')
         $('#view-accounts').attr('disabled', true)
         $.ajax({
            url : "{{ route('serviceAccounts.view-account-list') }}",
            type : 'GET',
            data : {
               Town : $('#Town').val(),
               Status : $('#Status').val(),
            },
            success : function(res) {
               $.each(res, function(index, element) {
                  $('#res-table tbody').append(
                     addRow(
                        (index + 1),
                        res[index]['OldAccountNo'],
                        res[index]['ServiceAccountName'],
                        res[index]['Town'],
                        res[index]['Barangay'],
                        res[index]['Purok'],
                        res[index]['AreaCode'],
                        res[index]['AccountType'],
                        res[index]['AccountStatus']
                     )
                  )
               })
               $('#loader').addClass('gone')
               $('#view-accounts').removeAttr('disabled')
            },
            error : function(err) {
               Toast.fire({
                  text : 'Error getting accounts',
                  icon : 'error'
               })
               $('#loader').addClass('gone')
               $('#view-accounts').removeAttr('disabled')
            }
         })
      }

      function addRow(index, acctNo, name, town, barangay, purok, route, type, status) {
         return "<tr>" +
                  "<td>" + index + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(town) ? '' : town) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(route) ? '' : route) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(acctNo) ? '' : acctNo) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(name) ? '' : name) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(barangay) ? '' : barangay) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(purok) ? '' : purok) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(type) ? '' : type) + "</td>" +
                  "<td>" + (jQuery.isEmptyObject(status) ? '' : status) + "</td>" +
               "</tr>"
      }
    </script>
@endpush