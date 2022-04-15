ConfigJs = {
    fetch_id:'',
    pre_url:''
};

function init_data() {
    const $table = jQuery("#"+ConfigJs.fetch_id);

    const table = $table.DataTable({
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": 10
    });

    // Initalize Select Dropdown after DataTables is created
    $table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
        minimumResultsForSearch: -1
    });

    // Setup - add a text input to each footer cell
    $( '#'+ConfigJs.fetch_id+' tfoot th' ).each( function () {
        const title = $('#'+ConfigJs.fetch_id+' thead th').eq($(this).index()).text();
        $(this).html( '<input type="text" class="form-control" placeholder="Search ' + title + '" />' );
    } );

    // Apply the search
    table.columns().every( function () {
        const that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
}
function messageFlash(message,type="success") {
    let opts = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-full-width",
        "toastClass": "red",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    if (type==='success'){
        toastr.success(message,opts);
    }else if(type==='info'){
        toastr.info(message,opts);
    }else {
        toastr.error(message,opts);
    }
}

function fetch_data()
{
    const dataTable = $('#'+ConfigJs.fetch_id).DataTable({

        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": 10

    });

}

function update_data(id, column_name, value)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        // url:"/agency/"+id+"/edit",
        url:ConfigJs.pre_url+id+"/edit",
        method:"POST",
        data:{id:id, column_name:column_name, value:value},
        success:function(data)
        {
            messageFlash(data,'info');
            $('#'+ConfigJs.fetch_id).DataTable().destroy();
            // fetch_data();
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
    setInterval(function(){
        $('#alert_message').html('');
    }, 2000);
}

function delete_row(btn_class='delete'){
    $(document).on('click', '.'+btn_class, function(){
        const id = $(this).attr("id");
        if(confirm("Are you sure you want to remove this?")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:ConfigJs.pre_url+id+"/delete",
                method:"POST",
                data:{id:id},
                success:function(data){
                    messageFlash(data,'success');
                    $('#'+ConfigJs.fetch_id).DataTable().destroy();
                    fetch_data();
                }
            });
            setInterval(function(){
                location.reload();
            }, 2000);
        }
    });
}

function btn_edit() {
    $('.edit_btn').click(function () {
        let rowid = $(this).data('rowid');
        let fields_contenteditable = $('.'+rowid);
        let save_btn = $('.save_btn'+rowid);
        fields_contenteditable.attr('contenteditable','true');
        fields_contenteditable.css('border','red 1px solid !important');
        save_btn.css('display','inline');
        $(this).hide();

    });
    $('.save_btn').click(function () {
        let rowid = $(this).data('rowid');
        let fields_contenteditable = $('.'+rowid);
        fields_contenteditable.attr('contenteditable','false');
        fields_contenteditable.css('border','#ebebeb 1px solid');
        $('.edit_btn'+rowid).show();
        $(this).hide();

    })
}

function save_form(method='POST',form='#form'){
    $(form).on('submit',function (e) {
        e.preventDefault();
        $('.errors_message').css('display','none');
        $('input').removeClass('border_red');

        let data = $(this).serialize();
        let url = $(this).attr('action');
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method:method,
            url: url,
            data: data,
            success: function(data){
                messageFlash(data,'success');
                setInterval(function(){
                    location.reload();
                }, 1000);
            },
            error:function (data) {
                const errors = $.parseJSON(data.responseText);
                let message='';
                $.each(errors.errors, function (key, value) {
                    $('input[name='+key+']').addClass('border_red');
                    $('.' + key).html(value).css('display','inline');

                    message+=value+'<br>';
                });
                messageFlash(message,'error');
            }
        })

    })
}

function clear_form_modal() {
    $('.errors_message').css('display','none');
    $('input').removeClass('border_red');
    $('#form').find("input[type=text], textarea").val("");

}

function format_time(time){
    let aT = time.split(':');
    return aT['0']+':'+aT['1'];
}
