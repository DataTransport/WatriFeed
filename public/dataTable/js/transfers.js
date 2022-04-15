$(document).ready(function(){

    ConfigJs.fetch_id='transfers_data';
    ConfigJs.pre_url='transfer/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.stopId2', function(){
        const id = $(this).data("id");
        const column_name ="from_stop_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.stopId3', function(){
        const id = $(this).data("id");
        const column_name ="to_stop_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });

    $('#add').click(function(){

        let t= $(this).data('t');
        let g= $(this).data('g');
        let m= $(this).data('m');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get( '/stop/?t='+t+'&g='+g+'&_='+m+'&a=true', function( data1 ) {

            let stopField1;
            let options1='';
            let stopField2;
            let options2='';


            data1.forEach(function(element) {
                options1+=`<option value="`+element.stop_id+`"> `+element.stop_id+`</option>`;
            });
            stopField1 = ` <td>
                                 <select id="stopid_data1" name="" class="" data-id="" >`+
                options1
                +`</select>
                                    </td>`;

            data1.forEach(function(element) {
                options2+=`<option value="`+element.stop_id+`"> `+element.stop_id+`</option>`;
            });
            stopField2 = ` <td>
                                 <select id="stopid_data2" name="" class="" data-id="" >`+
                options2
                +`</select>
                                    </td>`;


            let html = '<tr>';
            html +=stopField1;
            html +=stopField2;
            html += '<td contenteditable id="data3"></td>';
            html += '<td contenteditable id="data4"></td>';
            html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/transfer/">Insert</button></td>';
            html += '</tr>';

            $('#transfers_data tbody').prepend(html);

        });


    });
    $(document).on('click', '#insert', function(){
        const from_stop_id = $('#stopid_data1').find(":selected").text();
        const to_stop_id = $('#stopid_data2').find(":selected").text();
        const transfer_type = $('#data3').text();
        const min_transfer_time = $('#data4').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/transfer/store",
                method:"POST",
                data:{
                    from_stop_id:from_stop_id,
                    to_stop_id:to_stop_id,
                    transfer_type:transfer_type,
                    min_transfer_time:min_transfer_time,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#transfers_data').DataTable().destroy();
                    fetch_data();
                    setInterval(function(){
                        location.reload();
                    }, 2000);
                },
                error:function (data) {
                    const errors = $.parseJSON(data.responseText);
                    let message='';
                    $.each(errors.errors, function (key, value) {
                        message+=value+'<br>';
                    });
                    messageFlash(message,'error');
                }
            });

    });


});
