$(document).ready(function(){


    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1960:с',
        minDate: new Date(1960, 1 - 1, 1),
        maxDate: new Date(1995, 12 - 1, 31),
        dateFormat: 'dd.mm.yy'
    });



//-------------------------------------------------//
// Проверка на уникальность имени игрока
//-------------------------------------------------//
    var inputUN = $('.add_edit_form input[name="username"]');

    inputUN.on('keyup', function() {

        if(inputUN.val().length > 2)
        {
            $.ajax({
                url: '/players/checklogin/format/json',
                data: { username : inputUN.val() },
                type: "POST",
                dataType: "json",
                cache: false,
                success: function(data){
                    if(data.match) {
                        inputUN.addClass('busy').removeClass('not-busy');
                    } else {
                        inputUN.removeClass('busy').addClass('not-busy');
                    }
                }
            });
        }
    });

    $.ajaxSetup({ cache: false });


//-------------------------------------------------//    
// Выделение активного меню
//-------------------------------------------------//
    $('.jqueryslidemenu a').each(function () {    
        var location = window.location.href 
        var link = this.href                
        var result = location.match(link);  

        if(result != null) {               
            $(this).addClass('current'); 
        }
    });
    
//-------------------------------------------------//    
// Кнопка "Сохранить"
//-------------------------------------------------//
    $('a#save').click(function(){
        $('#add_edit_form').submit(); 
        return false;
    });



//-------------------------------------------------//
// Кнопка "Поиск"
//-------------------------------------------------//
    $('#search').click(function(){
        $('#search-cont').toggleClass('visible');
    })


//-------------------------------------------------//
// Выделить все чекбоксы
//-------------------------------------------------//
	$("#check_all").click(function() {
        if( $(this).prop('checked'))
            $('input[type="checkbox"][name*="check"]').prop('checked', true).parent().parent().parent().addClass('tr_select');
        else 
            $('input[type="checkbox"][name*="check"]').prop('checked', false).parent().parent().parent().removeClass('tr_select');
	});
    
//-------------------------------------------------//
// Подсветка чекбокса
//-------------------------------------------------//
    $('input[type="checkbox"]').not(("#check_all")).click(function(){
        if($(this).prop("checked")) 
            $(this).parent().parent().parent().addClass('tr_select');
        else 
            $(this).parent().parent().parent().removeClass('tr_select');
    });

//-------------------------------------------------//
// Диалоговое окно при удалении групы элементов
//-------------------------------------------------//
    $('#event_apply').click(function(){
        
        if($('select[name="event"]').val() == '') 
        {
            alert("Выберите действие!");
            return false;
        } 
        if($('select[name="event"]').val() == 'delete')
        {
            if(confirm("Уверены, что хотите удалить?"))
                $('#view_form').submit();
        }
        else 
            $('#view_form').submit(); 
        return false;     
    });   
    

     
    
});
