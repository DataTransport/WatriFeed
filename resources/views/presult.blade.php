<table class="table table-dark table-striped table-bordered table-hover" id="shapes_data2">
    <thead>
    <tr id="all">
        <th width="30px">#</th>
        <th width="130px">ID</th>
        <th>Name</th>
    </tr>
    </thead>
    <tbody>
    @php

        $i = 0;
    @endphp
    @foreach ($data as $value)
        {{--        @dd($value->shape_id)--}}
        @php
            $trip = \App\Trip::where('shape_id',$value->shape_id)->first();

            if ($trip){
               $route = \App\Route::where('route_id',$trip->route_id)->first();
            }


        @endphp
        <tr id="{{$value->shape_id}}" class="line_selectable">
            <td>{{$numbers[$i++]}}</td>
            <td>{{ $value->shape_id}}</td>
            <td>{{ $route->route_long_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{!! $data->render() !!}
