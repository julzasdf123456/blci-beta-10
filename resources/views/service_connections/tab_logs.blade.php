<table class="table table-hover table-sm">
   @foreach ($timeFrame as $item)
       <tr>
         <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
         <td>{{ $item->Status }}</td>
         <td>{{ $item->Notes }}</td>
         <td>{{ $item->name }}</td>
       </tr>
   @endforeach
</table>