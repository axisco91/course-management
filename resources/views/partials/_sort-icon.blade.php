@if ($sortBy !== $field)
    <i class="text-muted fas fa-sort"></i>
@elseif($sortDitection == 'asc')
    <i style="color:rgba(38, 38 ,236, 0.774)" class="fas fa-sort-up"></i>
@else
    <i style="color:rgba(38, 38 ,236, 0.774)" class="fas fa-sort-down"></i>
@endif
