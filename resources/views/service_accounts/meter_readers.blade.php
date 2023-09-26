@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-6">
               <h4>Meter Readers</h4>
           </div>
       </div>
   </div>
</section>

<div class="row">
   <div class="col-lg-6 offset-lg-3 col-sm-12">
      <div class="card shadow-none">
         <div class="card-header">
            <span class="card-title"><i class="text-muted">Press <strong>F3</strong> to Search</i></span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm">
               <thead>
                  <th>Meter Reader</th>
                  <th></th>
               </thead>
               <tbody>
                  @foreach ($meterReaders as $item)
                      <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                           <a href="{{ route('serviceAccounts.meter-readers-view', [$item->id]) }}"><i class="fas fa-eye"></i></a>
                        </td>
                      </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@endsection

@push('page_scripts')
    <script>
       
    </script>
@endpush

